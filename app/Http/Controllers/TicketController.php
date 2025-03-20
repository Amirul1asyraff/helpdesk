<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Get search parameter with null fallback
        $search = $request->input('search');

        // Build the query with eager loading and search scope
        $tickets = Ticket::with([
            'createdBy:id,name',
            'responsibleBy:id,name',
            'project:id,name'
        ])
            ->search($search) // Apply the search scope
            ->oldest() // Maintain your original sorting
            ->paginate(5); // Keep your original pagination count

        // Get projects for any dropdowns/filters
        $projects = Project::select('id', 'name')->get();

        // Return the view with all necessary data
        return view('ticket.index', compact('tickets', 'projects', 'search'));
    }
    public function show($uuid)
    {
        // Retrieve ticket by UUID with all necessary relationships
        $ticket = Ticket::where('uuid', $uuid)
            ->with([
                'createdBy:id,name',
                'responsibleBy:id,name',
                'project:id,name',
                'sla:id,status,response_time,resolution_time,penalty',
                'responses' => function ($query) {
                    $query->latest()->with('user:id,name');
                }
            ])
            ->firstOrFail();

        // Optional: Check for missing SLA and handle it gracefully
        if (!$ticket->sla && $ticket->sla_id) {
            // Log potential data integrity issue
            \Log::warning("Ticket with UUID {$uuid} references non-existent SLA ID: {$ticket->sla_id}");
        }

        return view('ticket.show', compact('ticket'));
    }
    public function create()
    {
        $users = User::all();
        $projects = Project::all();
        $slas = Sla::all();

        return view('ticket.create', compact('users', 'projects', 'slas'));
    }
    public function store(Request $request)
    {

        // Validate the incoming request WITHOUT codes field
        $validated = $request->validate([
            // 'created_by' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'responsible_by' => 'required|exists:users,id',
            'description' => 'required|string',
            'sla_id' => 'required|exists:slas,id',
            'status' => 'required|in:0,1,2',
        ]);

        // Generate ticket code with format #MMYYYY-XX
        $currentDate = now();
        $monthYear = $currentDate->format('mY'); // e.g., 032025 for March 2025

        // Find the highest ticket number for the CURRENT month/year INCLUDING soft-deleted records
        $currentMonth = $currentDate->format('m');
        $currentYear = $currentDate->format('Y');

        $latestTicket = Ticket::withTrashed() // Include soft-deleted records
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('codes', 'like', "#$monthYear-%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(codes, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1; // Default to 1 for the first ticket of the month
        if ($latestTicket) {
            // Extract the number part and increment
            preg_match('/#\d+-(\d+)/', $latestTicket->codes, $matches);
            if (isset($matches[1])) {
                $nextNumber = (int) $matches[1] + 1;
            }
        }

        // Format the new code with leading zeros for the number part (minimum 2 digits)
        $ticketCode = "#{$monthYear}-" . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        // Get the selected SLA
        $sla = Sla::findOrFail($request->sla_id);

        // Convert status string to integer if needed
        $statusMap = [
            'open' => 0,
            'closed' => 1,
            'escalated' => 2,
        ];

        // Store the ticket with auto-generated code
        $ticket = Ticket::create([
            'codes' => $ticketCode, // Use the generated code instead of validated input
            // 'created_by' => $validated['created_by'],
            'created_by' => auth()->id(),
            'project_id' => $validated['project_id'],
            'responsible_by' => $validated['responsible_by'],
            'description' => $validated['description'],
            'status' => is_numeric($validated['status']) ? $validated['status'] : $statusMap[$validated['status'] ?? 'open'],
            'response_time' => $sla->response_time,
            'resolution_time' => $sla->resolution_time,
        ]);


        return redirect()->route('tickets.index')
            ->with('success', "Ticket $ticketCode created successfully.");
    }

    public function edit($uuid)
    {
        try {
            // Retrieve the ticket with eager loaded relationships for efficiency
            $ticket = Ticket::where('uuid', $uuid)->firstOrFail();

            // Get necessary data for dropdown selections
            $users = User::select('id', 'name')->orderBy('name')->get();
            $projects = Project::select('id', 'name')->orderBy('name')->get();
            $slas = Sla::all();

            return view('ticket.edit', compact('ticket', 'users', 'projects', 'slas'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error retrieving ticket data: ' . $e->getMessage());

            return redirect()->route('tickets.index')->with('error', 'Unable to edit ticket. ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        // Find the ticket
        $ticket = Ticket::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'responsible_by' => 'required|exists:users,id',
            'description' => 'required|string',
            'sla_id' => 'required|exists:slas,id',
            'status' => 'required|in:0,1,2',
        ]);

        // Get the selected SLA
        $sla = Sla::findOrFail($validated['sla_id']);

        // Update the ticket
        $ticket->update([
            'responsible_by' => $validated['responsible_by'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'sla_id' => $validated['sla_id'],
            'response_time' => $sla->response_time,
            'resolution_time' => $sla->resolution_time,
        ]);

        return redirect()->route('tickets.index')
            ->with('success', "Ticket {$ticket->codes} updated successfully.");
    }
    public function destroy($id)
    {
        ticket::destroy($id);
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}

