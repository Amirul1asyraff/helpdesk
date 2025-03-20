<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ResponseController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'message' => 'required|string|max:2000',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Create the response with the correct column name
        Response::create([
            'content' => $validated['message'],
            'ticket_id' => $validated['ticket_id'],
            'response_by' => $user->id,  // Changed from user_id to response_by
        ]);

        // Find the ticket to redirect back to
        $ticket = Ticket::findOrFail($validated['ticket_id']);

        return redirect()->route('tickets.show', $ticket->uuid)
            ->with('success', 'Your response has been added successfully.');
    }
    public function update(Request $request, Response $response)
    {
        // Validation
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Update
        $response->content = $validated['message'];
        $response->save();

        return redirect()->back()->with('success', 'Response updated successfully');
    }

    public function destroy(Request $request, Response $response)
    {
         // Authorization check
        // $this->authorize('delete', $response);

        // Delete
        $response->delete();

        return redirect()->back()->with('success', 'Response deleted successfully');
    }
}
