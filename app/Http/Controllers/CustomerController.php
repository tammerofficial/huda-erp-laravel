<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(15);
        return view('customers.index', compact('customers'));
    }


    public function show(Customer $customer)
    {
        // Load orders with their items and relationships
        $customer->load(['orders' => function($query) {
            $query->with(['orderItems', 'customer'])
                  ->orderBy('created_at', 'desc');
        }]);
        
        // Calculate statistics
        $stats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->sum('final_amount'),
            'pending_orders' => $customer->orders->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders->where('status', 'completed')->count(),
            'confirmed_orders' => $customer->orders->where('status', 'confirmed')->count(),
            'in_production_orders' => $customer->orders->where('status', 'in_production')->count(),
            'cancelled_orders' => $customer->orders->where('status', 'cancelled')->count(),
        ];
        
        return view('customers.show', compact('customer', 'stats'));
    }

}
