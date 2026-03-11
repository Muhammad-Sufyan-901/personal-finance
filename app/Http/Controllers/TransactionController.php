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
            'proofs' => 'nullable|array|max:5',
            'proofs.*' => 'image|mimes:jpeg,png,jpg|max:2048',
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
            'proofs' => 'nullable|array|max:5',
            'proofs.*' => 'image|mimes:jpeg,png,jpg|max:2048',
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

    public function downloadAllProofs(Request $request, $id)
    {
        $transaction = \App\Models\Transaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (empty($transaction->proofs) || !is_array($transaction->proofs)) {
            abort(404, 'Bukti transaksi tidak ditemukan.');
        }

        $proofsToDownload = $transaction->proofs;

        // Custom selection
        if ($request->has('indices')) {
            $indicesArray = explode(',', $request->query('indices'));
            $filteredProofs = [];
            foreach ($indicesArray as $idx) {
                if (isset($transaction->proofs[$idx])) {
                    $filteredProofs[$idx] = $transaction->proofs[$idx];
                }
            }
            if (empty($filteredProofs)) {
                 abort(404, 'Gambar terpilih tidak valid atau tidak ditemukan.');
            }
            $proofsToDownload = $filteredProofs;
        }

        $zip = new \ZipArchive;
        $zipFileName = 'proofs_transaction_' . $transaction->id . '_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/private/' . $zipFileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($proofsToDownload as $index => $proof) {
                if (\Illuminate\Support\Facades\Storage::exists($proof)) {
                    $originalExtension = pathinfo($proof, PATHINFO_EXTENSION);
                    $fileName = 'proof_' . ($index + 1) . '.' . $originalExtension;
                    $zip->addFile(storage_path('app/private/' . $proof), $fileName);
                }
            }
            $zip->close();
        } else {
            abort(500, 'Gagal membuat file zip.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function showProofImage($id, $index)
    {
        $transaction = \App\Models\Transaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (empty($transaction->proofs) || !isset($transaction->proofs[$index])) {
            abort(404, 'Gambar tidak ditemukan.');
        }

        $path = $transaction->proofs[$index];
        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            abort(404, 'File gambar tidak ditemukan di server.');
        }

        $fullPath = storage_path('app/private/' . $path);
        
        $mimeType = \Illuminate\Support\Facades\Storage::mimeType($path);
        return response()->file($fullPath, ['Content-Type' => $mimeType]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        try {
            $result = $this->transactionService->importTransactions(
                auth()->id(),
                $request->file('file')
            );

            if ($result['success']) {
                $msg = $result['count'] . ' transaksi berhasil diimpor.';
                if (count($result['errors']) > 0) {
                    $msg .= ' Namun, ' . count($result['errors']) . ' baris gagal diproses.';
                }
                return response()->json(['message' => $msg, 'errors' => $result['errors']]);
            } else {
                return response()->json(['message' => 'Gagal mengimpor data. Format mungkin tidak sesuai.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:transactions,id',
        ]);

        try {
            $deletedCount = $this->transactionService->bulkDeleteTransactions(
                $request->input('ids'),
                auth()->id()
            );

            return response()->json(['message' => "$deletedCount transaksi berhasil dihapus."]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus transaksi.'], 500);
        }
    }
}
