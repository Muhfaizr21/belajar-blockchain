<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process; // gunakan Symfony Process
use Symfony\Component\Process\Process as SymfonyProcess;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'spent_at' => 'required|date',
        ]);

        $expense = Expense::create($data);

        // generate hash unik dari data
        $hash = hash('sha256', $expense->title . '|' . $expense->amount . '|' . $expense->spent_at);
        $expense->update(['hash' => $hash]);

        // kirim hash ke blockchain (panggil node script)
        $scriptPath = base_path('node_scripts/logExpense.js');
        $process = new SymfonyProcess(['node', $scriptPath, $hash]);
        $process->run();

        if ($process->isSuccessful()) {
            $txHash = trim($process->getOutput());
            $expense->update(['blockchain_tx' => $txHash]);
        } else {
            Log::error('Blockchain Error: ' . $process->getErrorOutput());
        }

        return redirect('/')->with('success', 'Transaksi berhasil dicatat!');
    }
}
