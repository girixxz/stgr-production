<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sales;
use Illuminate\Http\Request;

class ManageUsersSalesController extends Controller
{
    public function index(Request $request)
    {
        // ambil data tanpa CRUD, hanya untuk page
        $usersQuery = User::query();
        if ($request->filled('search_user')) {
            $search = $request->search_user;
            $usersQuery->where('fullname', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%");
        }
        $users = $usersQuery->get();

        $salesQuery = Sales::query();
        if ($request->filled('search_sales')) {
            $search = $request->search_sales;
            $salesQuery->where('sales_name', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%");
        }
        $sales = $salesQuery->get();

        return view('pages.owner.manage-users-sales', compact('users', 'sales'));
    }
}
