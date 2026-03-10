<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;

class DashboardController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $filters = $request->only(['from_date', 'to_date', 'search', 'type']);

        $recaps = $this->transactionService->getRecaps($userId, $filters);
        $transactions = $this->transactionService->getTransactions($userId, $filters);

        return view('dashboard.index', [
            'transactions' => $transactions,
            'income' => $recaps['income'],
            'expense' => $recaps['expense'],
            'balance' => $recaps['balance'],
        ]);
    }
}
