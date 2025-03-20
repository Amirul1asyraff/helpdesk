<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users') );
    }
    public function create()
    {
        return view("user.create");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Create user with validated data plus default password
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully with default password.');
    }
    public function edit(User $user)
    {
        return view("user.edit", compact("user"));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=> 'required',
            'email'=> 'required',
            'password' => Hash::make('password'), // Optional password
            ]);
            $user->update($request->except('password'));
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }
            return redirect()->route("users.index")->with("success", "User updated successfully");

    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route("users.index")->with("success", "User deleted successfully");
    }
    public function show(User $user)
    {
        return view("user.show", compact("user"));
    }
}
