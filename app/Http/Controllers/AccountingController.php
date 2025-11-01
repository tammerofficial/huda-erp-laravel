<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {
        $accountings = Accounting::with('createdBy')->paginate(15);
        return view('accounting.index', compact('accountings'));
    }

    public function create()
    {
        return view('accounting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:revenue,expense,asset,liability,equity',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
        ]);

        Accounting::create([
            'date' => $request->date,
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('accounting.index')->with('success', 'Accounting entry created successfully');
    }

    public function show(Accounting $accounting)
    {
        $accounting->load('createdBy');
        return view('accounting.show', compact('accounting'));
    }

    public function edit(Accounting $accounting)
    {
        return view('accounting.edit', compact('accounting'));
    }

    public function update(Request $request, Accounting $accounting)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:revenue,expense,asset,liability,equity',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $accounting->update($request->all());
        return redirect()->route('accounting.index')->with('success', 'Accounting entry updated successfully');
    }

    public function destroy(Accounting $accounting)
    {
        $accounting->delete();
        return redirect()->route('accounting.index')->with('success', 'Accounting entry deleted successfully');
    }

    public function journalEntries()
    {
        $entries = JournalEntry::with('createdBy')->paginate(15);
        return view('accounting.journal.index', compact('entries'));
    }

    public function createJournalEntry()
    {
        return view('accounting.journal.create');
    }

    public function storeJournalEntry(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'debit_account' => 'required|string',
            'credit_account' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
        ]);

        JournalEntry::create([
            'entry_date' => $request->entry_date,
            'description' => $request->description,
            'debit_account' => $request->debit_account,
            'credit_account' => $request->credit_account,
            'amount' => $request->amount,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('accounting.journal.index')->with('success', 'Journal entry created successfully');
    }

    public function reports()
    {
        $revenue = Accounting::where('type', 'revenue')->sum('amount');
        $expenses = Accounting::where('type', 'expense')->sum('amount');
        $profit = $revenue - $expenses;

        $monthlyData = Accounting::select(
            DB::raw('DATE_FORMAT(date, \'%Y-%m\') as month'),
            DB::raw('SUM(CASE WHEN type = \'revenue\' THEN amount ELSE 0 END) as revenue'),
            DB::raw('SUM(CASE WHEN type = \'expense\' THEN amount ELSE 0 END) as expense')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return view('accounting.reports', compact('revenue', 'expenses', 'profit', 'monthlyData'));
    }

    public function showJournalEntry(JournalEntry $entry)
    {
        $entry->load('createdBy');
        return view('accounting.journal.show', compact('entry'));
    }

    public function editJournalEntry(JournalEntry $entry)
    {
        return view('accounting.journal.edit', compact('entry'));
    }

    public function updateJournalEntry(Request $request, JournalEntry $entry)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string|max:255',
            'debit_account' => 'required|string|max:100',
            'credit_account' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'reference_type' => 'nullable|string|max:100',
            'reference_id' => 'nullable|integer',
        ]);

        $entry->update($request->all());
        return redirect()->route('accounting.journal.index')->with('success', 'Journal entry updated successfully');
    }

    public function destroyJournalEntry(JournalEntry $entry)
    {
        $entry->delete();
        return redirect()->route('accounting.journal.index')->with('success', 'Journal entry deleted successfully');
    }
}
