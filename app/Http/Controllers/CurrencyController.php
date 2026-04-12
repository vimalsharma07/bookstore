<?php

namespace App\Http\Controllers;

use App\Services\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'currency' => ['required', 'string', 'in:USD,EUR,INR'],
        ]);

        Currency::set($request->string('currency'));

        return back();
    }
}
