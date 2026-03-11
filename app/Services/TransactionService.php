<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class TransactionService
{
    /**
     * Get paginated transactions with optional filters.
     */
    public function getTransactions($userId, $filters = [], $perPage = 10)
    {
        $query = Transaction::where('user_id', $userId);
        $this->applyFilters($query, $filters);
        
        return $query->orderBy('date', 'desc')->paginate($perPage)->withQueryString();
    }

    /**
     * Get all transactions without pagination for export.
     */
    public function getAllTransactions($userId, $filters = [])
    {
        $query = Transaction::where('user_id', $userId);
        $this->applyFilters($query, $filters);
        
        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get recap summaries (income, expense, balance) with optional date filters.
     */
    public function getRecaps($userId, $filters = [])
    {
        $query = Transaction::where('user_id', $userId);
        
        // We only apply date filters for recaps typically, or type if needed.
        if (!empty($filters['from_date'])) {
            $query->whereDate('date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('date', '<=', $filters['to_date']);
        }

        $income = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $expense = (clone $query)->where('type', 'pengeluaran')->sum('amount');
        $balance = $income - $expense;

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
        ];
    }

    /**
     * Menyimpan data transaksi baru ke dalam basis data.
     *
     * @param int $userId ID pengguna yang sedang aktif
     * @param array $data Data transaksi mentah dari input pengguna
     * @return \App\Models\Transaction Mengembalikan objek transaksi yang baru dibuat
     */
    public function createTransaction($userId, $data)
    {
        $proofPaths = [];
        if (isset($data['proofs']) && is_array($data['proofs'])) {
            foreach ($data['proofs'] as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $proofPaths[] = $file->store('private/proofs');
                }
            }
        }

        return Transaction::create([
            'user_id' => $userId,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'date' => $data['date'],
            'proofs' => !empty($proofPaths) ? $proofPaths : null, 
        ]);
    }

    /**
     * Update an existing transaction.
     */
    public function updateTransaction($transactionId, $userId, $data)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $updateData = [
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'date' => $data['date'],
        ];

        // Handle proof updates: if new proofs are uploaded, replace the old ones.
        if (isset($data['proofs']) && is_array($data['proofs'])) {
            // Delete old proofs first
            if (!empty($transaction->proofs) && is_array($transaction->proofs)) {
                Storage::delete($transaction->proofs);
            }

            // Store new proofs
            $proofPaths = [];
            foreach ($data['proofs'] as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $proofPaths[] = $file->store('private/proofs');
                }
            }
            $updateData['proofs'] = $proofPaths;
        }

        $transaction->update($updateData);

        return $transaction;
    }

    /**
     * Delete a transaction.
     */
    public function deleteTransaction($transactionId, $userId)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (!empty($transaction->proofs) && is_array($transaction->proofs)) {
            Storage::delete($transaction->proofs);
        }

        return $transaction->delete();
    }

    /**
     * Bulk Delete transactions.
     */
    public function bulkDeleteTransactions(array $transactionIds, $userId)
    {
        $transactions = Transaction::whereIn('id', $transactionIds)
            ->where('user_id', $userId)
            ->get();

        $deletedCount = 0;
        foreach ($transactions as $transaction) {
            // Delete associated proofs if they exist
            if (!empty($transaction->proofs) && is_array($transaction->proofs)) {
                Storage::delete($transaction->proofs);
            }
            /** @var \App\Models\Transaction $transaction */
            $transaction->delete();
            $deletedCount++;
        }

        return $deletedCount;
    }

    /**
     * Helper to apply common filters.
     */
    private function applyFilters($query, $filters)
    {
        if (!empty($filters['from_date'])) {
            $query->whereDate('date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('date', '<=', $filters['to_date']);
        }
        if (!empty($filters['search'])) {
            $query->where('description', 'like', '%' . $filters['search'] . '%');
        }
        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('type', $filters['type']);
        }
    }

    /**
     * Import transactions from CSV file.
     */
    public function importTransactions($userId, \Illuminate\Http\UploadedFile $file)
    {
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) <= 1) {
            throw new \Exception('File CSV kosong atau hanya berisi header.');
        }

        // Header mapping check (optional strictness, but let's just skip row 0)
        $header = array_shift($data);

        $monthMap = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December'
        ];

        $importedCount = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            if (count($row) < 4) continue; // Skip malformed rows

            try {
                // Tanggal, Deskripsi, Jenis, Nominal (Rp)
                $dateStr = trim($row[0]);
                $desc = trim($row[1]);
                $typeStr = strtolower(trim($row[2]));
                $amountStr = trim($row[3]);

                // Replace months
                $englishDateStr = str_ireplace(array_keys($monthMap), array_values($monthMap), $dateStr);
                $date = \Carbon\Carbon::createFromFormat('d F Y', $englishDateStr)->format('Y-m-d');

                // Validate Type
                $type = 'pengeluaran';
                if (in_array($typeStr, ['pemasukan', 'income'])) {
                    $type = 'pemasukan';
                }

                // Clean Amount
                $amount = floatval(preg_replace('/[^0-9.]/', '', $amountStr));

                Transaction::create([
                    'user_id' => $userId,
                    'type' => $type,
                    'amount' => $amount,
                    'description' => $desc,
                    'date' => $date,
                    'proofs' => null,
                ]);
                $importedCount++;

            } catch (\Exception $e) {
                \Log::error("Failed to import CSV row $index: " . $e->getMessage());
                $errors[] = "Baris " . ($index + 2) . " gagal diimpor.";
            }
        }

        return [
            'success' => $importedCount > 0,
            'count' => $importedCount,
            'errors' => $errors
        ];
    }
}
