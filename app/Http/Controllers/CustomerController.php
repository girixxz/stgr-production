<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['province', 'city'])->get();
        $provinces = Province::orderBy('name', 'asc')->get();
        $cities = City::orderBy('name', 'asc')->get();
        return view('pages.admin.customers', compact('customers', 'provinces', 'cities'));
    }

    public function store(Request $request)
    {
        // biar modal add kebuka kalau gagal
        session()->flash('openModal', 'addCustomer');

        $validated = $request->validateWithBag('addCustomer', [
            'name'        => 'required|max:100',
            'phone'       => 'required|max:20|unique:customers,phone',
            'province_id' => 'required|exists:provinces,id',
            'city_id'     => 'required|exists:cities,id',
            'address'     => 'required',
        ]);

        Customer::create($validated);

        return redirect()->to(route('admin.customers.index') . '#customers')
            ->with('success_add', 'Customer added successfully.');
    }
    public function update(Request $request, Customer $customer)
    {
        // biar modal edit kebuka kalau gagal
        session()->flash('openModal', 'editCustomer');
        session()->flash('editCustomerId', $customer->id);

        $validated = $request->validateWithBag('editCustomer', [
            'name'        => 'required|max:100',
            'phone' => 'required|max:20|unique:customers,phone,' . $customer->id,
            'province_id' => 'required|exists:provinces,id',
            'city_id'     => 'required|exists:cities,id',
            'address'     => 'required',
        ]);

        $customer->update($validated);

        return redirect()->to(route('admin.customers.index') . '#customers')
            ->with('success_edit', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->to(route('admin.customers.index') . '#customers')
            ->with('success_delete', 'Customer deleted successfully.');
    }
}
