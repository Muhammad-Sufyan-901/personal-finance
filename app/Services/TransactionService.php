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
     * Create a new transaction.
     */
    public function createTransaction($userId, $data)
    {
        $proofPath = null;
        if (isset($data['proof']) && $data['proof'] instanceof \Illuminate\Http\UploadedFile) {
            $proofPath = $data['proof']->store('proofs', 'public');
        }

        return Transaction::create([
            'user_id' => $userId,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'date' => $data['date'],
            // Assuming your migration has a proof column; if not, you might need to add it or remove this.
            // 'proof' => $proofPath, 
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

        // Handle proof update if applicable
        /*
        if (isset($data['proof']) && $data['proof'] instanceof \Illuminate\Http\UploadedFile) {
            if ($transaction->proof) {
                Storage::disk('public')->delete($transaction->proof);
            }
            $transaction->proof = $data['proof']->store('proofs', 'public');
        }
        */

        $transaction->update([
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'date' => $data['date'],
        ]);

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

        // if ($transaction->proof) {
        //     Storage::disk('public')->delete($transaction->proof);
        // }

        return $transaction->delete();
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
}
