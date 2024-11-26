<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\PembayaranKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KreditPembayaranKreditController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembayaran_kredit_id' => 'required|exists:pembayaran_kredits,id',
            'kredit_ids' => 'required|array',
            'kredit_ids.*' => 'exists:kredits,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $pembayaranKredit = PembayaranKredit::findOrFail($request->pembayaran_kredit_id);

            foreach ($request->kredit_ids as $kreditId) {
                $kredit = Kredit::findOrFail($kreditId);

                // Attach the credit to the payment
                $pembayaranKredit->kredits()->attach($kreditId);

                // Update the credit status to paid (true)
                $kredit->update(['status' => true]);
            }

            DB::commit();
            return response()->json(['message' => 'Kredits attached to payment successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index($pembayaranKreditId)
    {
        try {
            $pembayaranKredit = PembayaranKredit::with('kredits')->findOrFail($pembayaranKreditId);
            return response()->json($pembayaranKredit->kredits, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($pembayaranKreditId, $kreditId)
    {
        try {
            DB::beginTransaction();

            $pembayaranKredit = PembayaranKredit::findOrFail($pembayaranKreditId);
            $kredit = Kredit::findOrFail($kreditId);

            // Detach the credit from the payment
            $pembayaranKredit->kredits()->detach($kreditId);

            // Update the credit status back to unpaid (false)
            $kredit->update(['status' => false]);

            DB::commit();
            return response()->json(['message' => 'Kredit detached from payment successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
