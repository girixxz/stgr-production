<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sales;
use Illuminate\Http\Request;

class ManageUsersSalesController extends Controller
{
    public function index()
    {
        $users = User::all();
        $sales = Sales::all();

        return view('pages.owner.manage-users-sales', compact('users', 'sales'));
    }

    // Store new user
    public function storeUser(Request $request)
    {
        // simpan state modal dulu sebelum validasi
        session()->flash('openModal', 'addUser');

        $validated = $request->validateWithBag('addUser', [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'phone_number' => 'nullable|string|max:100',
            'role' => 'required|string|in:owner,admin,pm,karyawan',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'fullname' => $validated['fullname'],
            'username' => $validated['username'],
            'phone_number' => $validated['phone_number'] ?? null,
            'role' => $validated['role'],
            'password' => $validated['password'],
        ]);

        return redirect()->route('owner.manage-users-sales')
            ->with('success_add', 'User added successfully.');
    }

    // Update existing user
    public function updateUser(Request $request, User $user)
    {
        session()->flash('openModal', 'editUser');
        session()->flash('editUserId', $user->id);

        $validated = $request->validateWithBag('editUser', [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone_number' => 'nullable|string|max:100',
            'role' => 'required|string|in:owner,admin,pm,karyawan',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // cek perubahan
        $changes = false;
        foreach (['fullname', 'username', 'phone_number', 'role'] as $field) {
            if ($user->$field !== $validated[$field]) {
                $changes = true;
                $user->$field = $validated[$field];
            }
        }

        if (!empty($validated['password'])) {
            $changes = true;
            $user->password = $validated['password'];
        }

        if ($changes) {
            $user->save();
            return redirect()->route('owner.manage-users-sales')
                ->with('success_edit', 'User updated successfully.');
        } else {
            return redirect()->route('owner.manage-users-sales')
                ->with('info_edit', 'No changes were made.');
        }
    }

    // Delete user
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('owner.manage-users-sales')->with('success', 'User deleted successfully.');
    }

    // Store new sales
    public function storeSales(Request $request)
    {
        session()->flash('openModal', 'addSales');

        $validated = $request->validateWithBag('addSales', [
            'sales_name' => 'required|string|max:100|unique:sales,sales_name',
            'phone_number' => 'nullable|string|max:100',
        ]);

        Sales::create($validated);

        return redirect()->route('owner.manage-users-sales')
            ->with('success_add_sales', 'Sales added successfully.');
    }

    // Update existing sales
    public function updateSales(Request $request, Sales $sale)
    {
        session()->flash('openModal', 'editSales');
        session()->flash('editSalesId', $sale->id);

        $validated = $request->validateWithBag('editSales', [
            'sales_name' => 'required|string|max:100|unique:sales,sales_name,' . $sale->id,
            'phone_number' => 'nullable|string|max:100',
        ]);

        $changes = false;
        foreach (['sales_name', 'phone_number'] as $field) {
            if ($sale->$field !== $validated[$field]) {
                $changes = true;
                $sale->$field = $validated[$field];
            }
        }

        if ($changes) {
            $sale->save();
            return redirect()->route('owner.manage-users-sales')
                ->with('success_edit_sales', 'Sales updated successfully.');
        } else {
            return redirect()->route('owner.manage-users-sales')
                ->with('info_edit_sales', 'No changes were made.');
        }
    }


    // Delete sales
    public function destroySales(Sales $sale)
    {
        $sale->delete();
        return redirect()->route('owner.manage-users-sales')->with('success', 'Sales deleted successfully.');
    }
}
