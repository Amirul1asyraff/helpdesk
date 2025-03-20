<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        $slas = Sla::all();
        return view('sla.index', compact('slas'));
    }

    public function create()
    {
        return view('sla.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'penalty' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', // Allow decimal currency values with up to 2 decimal places
        ]);

        // Format the penalty value to ensure consistent decimal precision
        $validated['penalty'] = number_format((float) $validated['penalty'], 2, '.', '');

        Sla::create($validated);

        return redirect()->route('slas.index')->with('success', 'SLA created successfully.');
    }

    public function edit(Sla $sla)
    {
        return view('sla.edit', compact('sla'));
    }

    public function update(Request $request, Sla $sla)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'penalty' => 'required|numeric|min:0|max:99999999.99',
        ]);

        $sla->update($validated);

        return redirect()->route('slas.index')
            ->with('success', 'SLA updated successfully');
    }

    public function destroy(Sla $sla)
    {
        $sla->delete();

        return redirect()->route('slas.index')
            ->with('success', 'SLA deleted successfully');
    }
}
