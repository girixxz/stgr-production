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
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
            'address' => 'required|string|max:255',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'phone.required' => 'Phone number is required.',
            'province_id.required' => 'Province is required.',
            'city_id.required' => 'City is required.',
            'district_id.required' => 'District is required.',
            'village_id.required' => 'Village is required.',
            'address.required' => 'Address is required.',
        ]);

        $customer = Customer::create($validated);

        // Check if request is from create order page
        if ($request->has('from_create_order') && $request->from_create_order == '1') {
            return redirect()->route('admin.orders.create')
                ->with('message', 'Customer added successfully.')
                ->with('alert-type', 'success')
                ->with('select_customer_id', $customer->id);
        }

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
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
            'address' => 'required|string|max:255',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'phone.required' => 'Phone number is required.',
            'province_id.required' => 'Province is required.',
            'city_id.required' => 'City is required.',
            'district_id.required' => 'District is required.',
            'village_id.required' => 'Village is required.',
            'address.required' => 'Address is required.',
        ]);

        $customer->update($validated);

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
