<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $this->transactionService->createTransaction(auth()->id(), $validated);

        return response()->json(['message' => 'Transaction saved successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $this->transactionService->updateTransaction($id, auth()->id(), $validated);

        return response()->json(['message' => 'Transaction updated successfully']);
    }

    public function destroy($id)
    {
        $this->transactionService->deleteTransaction($id, auth()->id());

        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function edit($id)
    {
        $transaction = \App\Models\Transaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json($transaction);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'search', 'type']);
        $transactions = $this->transactionService->getAllTransactions(auth()->id(), $filters);

        $filename = "transactions_export_" . date('Ymd_His') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Deskripsi', 'Jenis', 'Nominal (Rp)'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $transaction) {
                // Determine type label
                $typeLabel = $transaction->type === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran';
                
                // Format Date using Carbon to Indonesian Locale
                $formattedDate = \Carbon\Carbon::parse($transaction->date)->locale('id')->translatedFormat('d F Y');

                $row = [
                    $formattedDate,
                    $transaction->description,
                    $typeLabel,
                    $transaction->amount
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
