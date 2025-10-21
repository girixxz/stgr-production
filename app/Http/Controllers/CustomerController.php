<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::with(['province', 'city', 'district', 'village', 'orders'])
            ->when($search, function ($query, $search) {
                return $query->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->withCount('orders')
            ->withSum('orders', 'total_qty')
            ->orderBy('created_at', 'desc')
            ->get();

        $provinces = Province::orderBy('province_name')->get();

        return view('pages.admin.customers', compact('customers', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        session()->flash('openModal', 'addCustomer');

        $validated = $request->validateWithBag('addCustomer', [
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('message', 'Customer added successfully.')
            ->with('alert-type', 'success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        session()->flash('openModal', 'editCustomer');
        session()->flash('editCustomerId', $customer->id);

        $validated = $request->validateWithBag('editCustomer', [
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'village_id' => 'nullable|exists:villages,id',
            'address' => 'nullable|string',
        ]);

        $customer->update(array_filter($validated, function($value) {
            return $value !== null;
        }));

        return redirect()->route('admin.customers.index')
            ->with('message', 'Customer updated successfully.')
            ->with('alert-type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('message', 'Customer deleted successfully.')
            ->with('alert-type', 'success');
    }

    /**
     * Get cities by province (for AJAX)
     */
    public function getCities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)
            ->orderBy('city_name')
            ->get();

        return response()->json($cities);
    }

    /**
     * Get districts by city (for AJAX)
     */
    public function getDistricts($cityId)
    {
        $districts = District::where('city_id', $cityId)
            ->orderBy('district_name')
            ->get();

        return response()->json($districts);
    }

    /**
     * Get villages by district (for AJAX)
     */
    public function getVillages($districtId)
    {
        $villages = Village::where('district_id', $districtId)
            ->orderBy('village_name')
            ->get();

        return response()->json($villages);
    }
}
