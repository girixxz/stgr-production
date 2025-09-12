<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // simpan state modal
        session()->flash('openModal', 'addUser');

        $validated = $request->validateWithBag('addUser', [
            'fullname' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'phone_number' => 'nullable|max:100',
            'role' => 'required|in:owner,admin,pm,karyawan',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create($validated);

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('success_add', 'User added successfully.');
    }

    public function update(Request $request, User $user)
    {
        // supaya modal edit tetap kebuka kalau error
        session()->flash('openModal', 'editUser');
        session()->flash('editUserId', $user->id);

        $validated = $request->validateWithBag('editUser', [
            'fullname' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,' . $user->id,
            'phone_number' => 'nullable|max:100',
            'role' => 'required|in:owner,admin,pm,karyawan',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update(array_filter($validated));

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('success_edit', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('owner.manage-data.users-sales.index')
            ->with('success', 'User deleted successfully.');
    }
}
