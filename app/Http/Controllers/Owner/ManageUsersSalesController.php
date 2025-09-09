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
        $users = User::all();
        $sales = Sales::all();

        return view('pages.owner.manage-users-sales', compact(
            'users',
            'sales'
        ));
    }
}
