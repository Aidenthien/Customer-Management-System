<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        // Apply middleware to all methods of the controller
        $this->middleware('auth.customer');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = \App\Models\Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email|max:255',
            'phone' => 'required|string|max:15',
        ]);
    
        try {
            \App\Models\Customer::create($validatedData);
            return redirect()->route('customers.index')->with('alert', 'New Customer Added!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['alert' => 'Failed to add customer. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = \App\Models\Customer::find($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = \App\Models\Customer::find($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id . '|max:255',
            'phone' => 'required|string|max:15',
        ]);

        try {
            $customer->update($validatedData);
            return redirect()->route('customers.index')->with('alert', "Customer Details Updated!");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['alert' => 'Failed to update customer details. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = \App\Models\Customer::find($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('alert', "Customer Deleted!");
    }
}
