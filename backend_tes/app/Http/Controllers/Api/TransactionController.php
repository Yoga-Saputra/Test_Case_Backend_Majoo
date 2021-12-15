<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    protected function omzet()
    {
        $omzet = Transaction::join('Merchants', 'Merchants.id', '=', 'Transactions.merchant_id')
            ->where('Merchants.user_id', Auth::user()->id)
            ->sum('bill_total');

        return $omzet;
    }
    public function laporanC(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'merchant_id'      => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $merchant = Transaction::join('Merchants', 'Merchants.id', '=', 'Transactions.merchant_id')
                ->select([
                    'Merchants.merchant_name',
                    'Transactions.bill_total',
                ])
                ->where('Transactions.merchant_id', $request->merchant_id)
                ->where('Merchants.user_id', Auth::user()->id)
                ->whereBetween('Transactions.created_at', ["2021-11-01 00:00:00", "2021-11-30 23:59:59"])
                ->paginate();

            $omzet = Transaction::join('Merchants', 'Merchants.id', '=', 'Transactions.merchant_id')
                ->where('Merchants.user_id', Auth::user()->id)
                ->where('Transactions.merchant_id', $request->merchant_id)
                ->sum('bill_total');

            if ($omzet == null) {
                return response()->json([
                    'omzet' => 0
                ], 404);
            } else {
                return response()->json([
                    'All Omzet' => $this->omzet($request->merchant_id),
                    $merchant
                ], 200);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function laporanD(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'outlet_id'      => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $merchant = Transaction::join('Merchants', 'Merchants.id', '=', 'Transactions.merchant_id')
                ->join('Outlets', 'Outlets.id', '=', 'Transactions.outlet_id')
                ->select([
                    'Merchants.merchant_name',
                    'Outlets.outlet_name',
                    'Transactions.bill_total',
                ])
                ->where('Transactions.merchant_id', $request->outlet_id)
                ->where('Merchants.user_id', Auth::user()->id)
                ->whereBetween('Transactions.created_at', ["2021-11-01 00:00:00", "2021-11-30 23:59:59"])
                ->paginate(10);

            $omzet = Transaction::join('Merchants', 'Merchants.id', '=', 'Transactions.merchant_id')
                ->where('Merchants.user_id', Auth::user()->id)
                ->where('Transactions.merchant_id', $request->outlet_id)
                ->sum('bill_total');

            if ($omzet == null) {
                return response()->json([
                    'omzet' => 0
                ], 404);
            } else {
                return response()->json([
                    'omzet' => $this->omzet($request->merchant_id),
                    $merchant
                ], 200);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
