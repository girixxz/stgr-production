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

        return redirect(route('owner.manage-data.users-sales.index') . '#users')
            ->with('message', 'User added successfully.')
            ->with('alert-type', 'success');
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

        // Filter null values kecuali password kosong
        $updateData = array_filter($validated, function ($value, $key) {
            return $key !== 'password' || !empty($value);
        }, ARRAY_FILTER_USE_BOTH);

        $user->update($updateData);

        return redirect(route('owner.manage-data.users-sales.index') . '#users')
            ->with('message', 'User updated successfully.')
            ->with('alert-type', 'success');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect(route('owner.manage-data.users-sales.index') . '#users')
            ->with('message', 'User deleted successfully.')
            ->with('alert-type', 'success');
    }
}
