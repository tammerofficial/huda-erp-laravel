<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Accounting;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('order.customer')->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $orders = Order::where('status', 'completed')->whereDoesntHave('invoice')->with('customer')->get();
        return view('invoices.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id|unique:invoices',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'tax_amount' => 'numeric|min:0',
            'discount_amount' => 'numeric|min:0',
        ]);

        $order = Order::find($request->order_id);
        $totalAmount = $order->final_amount;
        $taxAmount = $request->tax_amount ?? 0;
        $discountAmount = $request->discount_amount ?? 0;
        $finalAmount = $totalAmount + $taxAmount - $discountAmount;

        $invoice = Invoice::create([
            'order_id' => $request->order_id,
            'invoice_number' => 'INV' . time(),
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['order.customer', 'order.orderItems.product']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'payment_status' => 'required|in:pending,partial,paid,overdue',
            'payment_method' => 'nullable|in:cash,bank_transfer,card,cheque',
            'payment_date' => 'nullable|date',
        ]);

        $invoice->update($request->all());

        if ($request->payment_status === 'paid' && $invoice->wasChanged('payment_status')) {
            Accounting::create([
                'date' => $request->payment_date ?? now(),
                'type' => 'revenue',
                'category' => 'sales',
                'amount' => $invoice->final_amount,
                'description' => 'Payment received for Invoice ' . $invoice->invoice_number,
                'reference_type' => 'invoice',
                'reference_id' => $invoice->id,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
    }

    public function send(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent']);
        return redirect()->back()->with('success', 'Invoice sent successfully');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        Accounting::create([
            'date' => now(),
            'type' => 'revenue',
            'category' => 'sales',
            'amount' => $invoice->final_amount,
            'description' => 'Payment received for Invoice ' . $invoice->invoice_number,
            'reference_type' => 'invoice',
            'reference_id' => $invoice->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Invoice marked as paid');
    }
}
