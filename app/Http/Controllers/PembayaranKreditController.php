<?php

namespace App\Http\Controllers;

use App\Models\PembayaranKredit;
use App\Models\Kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PembayaranKreditController extends Controller
{
    public function index()
    {
        try {
            $pembayaranKredits = PembayaranKredit::with(['giling.petani', 'kredits'])->get();
            return response()->json($pembayaranKredits, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'giling_id' => 'required|exists:gilings,id',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $pembayaranKredit = PembayaranKredit::create($validator->validated());

            // Logika pembayaran kredit lainnya...

            DB::commit();
            return response()->json($pembayaranKredit, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $pembayaranKredit = PembayaranKredit::with(['kredit.petani', 'giling'])->find($id);

        if (!$pembayaranKredit) {
            return response()->json(['message' => 'Pembayaran Kredit not found'], 404);
        }

        return response()->json($this->formatPembayaranKreditData($pembayaranKredit));
    }

    public function update(Request $request, $id)
    {
        $pembayaranKredit = PembayaranKredit::find($id);
        if (!$pembayaranKredit) {
            return response()->json(['message' => 'Pembayaran Kredit not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'giling_id' => 'exists:gilings,id',
            'bunga' => 'numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pembayaranKredit->update($validator->validated());
        return response()->json($pembayaranKredit);
    }

    public function destroy($id)
    {
        $pembayaranKredit = PembayaranKredit::find($id);
        if (!$pembayaranKredit) {
            return response()->json(['message' => 'Pembayaran Kredit not found'], 404);
        }
        $pembayaranKredit->delete();
        return response()->json(null, 204);
    }

        private function formatPembayaranKreditData($pembayaranKredit)
    {
        return [
            'id' => $pembayaranKredit->id,
            'bunga' => $pembayaranKredit->bunga,
            'giling' => [
                'id' => $pembayaranKredit->giling->id,
                'giling_kotor' => $pembayaranKredit->giling->giling_kotor,
                // ... other giling fields ...
            ],
            'kredit' => $pembayaranKredit->kredit->map(function ($kredit) {
                return [
                    'id' => $kredit->id,
                    'jumlah' => $kredit->jumlah,
                    'tanggal' => $kredit->tanggal,
                    'keterangan' => $kredit->keterangan,
                    'status' => $kredit->status,
                    'petani' => [
                        'id' => $kredit->petani->id,
                        'nama' => $kredit->petani->nama,
                        'alamat' => $kredit->petani->alamat,
                        'no_telepon' => $kredit->petani->no_telepon,
                    ],
                ];
            }),
            'created_at' => $pembayaranKredit->created_at,
            'updated_at' => $pembayaranKredit->updated_at,
        ];
    }
}