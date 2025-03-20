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
        // Authorize the request first
        $this->authorize('update', $response);

        // Validate the form input
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Map the validated 'message' field to the database 'content' column
        $response->update([
            'content' => $validated['message']
        ]);

        // Get the ticket for redirect
        $ticket = Ticket::findOrFail($response->ticket_id);

        return redirect()->route('tickets.show', $ticket->uuid)
            ->with('success', 'Response updated successfully');
    }

    public function destroy(Response $response)
    {
        $this->authorize('delete', $response);

        $ticket = Ticket::findOrFail($response->ticket_id);
        $response->delete();

        return redirect()->route('tickets.show', $ticket->uuid)
            ->with('success', 'Response deleted successfully');
    }
}
