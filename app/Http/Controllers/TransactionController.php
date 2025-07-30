<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function myTransactions(Request $request)
    {
        return $request->user()->transactions()->select('amount', 'provider', 'status')->get();
    }
}

