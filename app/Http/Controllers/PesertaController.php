<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PesertaController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'tiu' => 'required|integer',
            'twk' => 'required|integer',
            'tkp' => 'required|integer',
            'method' => 'required|string|in:SAW,WASPAS',
        ]);

        // Tambahkan default value pada kolom 'keterangan'
        Schema::table('pesertas', function (Blueprint $table) {
            $table->string('keterangan')->default('')->change();
        });

        $name = $request->input('name');
        $tiu = $request->input('tiu');
        $twk = $request->input('twk');
        $tkp = $request->input('tkp');
        $method = $request->input('method');

        // Simpan data peserta baru
        $peserta = new Peserta();
        $peserta->nama = $name;
        $peserta->tiu = $tiu;
        $peserta->twk = $twk;
        $peserta->tkp = $tkp;
        $peserta->save();

        // Ambil semua peserta
        $allPesertas = Peserta::all();

        // Normalisasi nilai jika diperlukan oleh metode
        $maxTiw = $allPesertas->max('twk');
        $maxTiu = $allPesertas->max('tiu');
        $maxTkp = $allPesertas->max('tkp');

        $pesertas = $allPesertas->map(function ($peserta) use ($method, $maxTiw, $maxTiu, $maxTkp) {
            if ($method == 'SAW' || $method == 'WASPAS') {
                $normalizedTwk = $peserta->twk / $maxTiw;
                $normalizedTiu = $peserta->tiu / $maxTiu;
                $normalizedTkp = $peserta->tkp / $maxTkp;

                if ($method == 'SAW') {
                    $totalWeighted = $normalizedTwk + $normalizedTiu + $normalizedTkp;
                } else { // WASPAS
                    $sumPart = 0.5 * ($normalizedTwk + $normalizedTiu + $normalizedTkp);
                    $prodPart = 0.5 * (pow($normalizedTwk, 1/3) * pow($normalizedTiu, 1/3) * pow($normalizedTkp, 1/3));
                    $totalWeighted = $sumPart + $prodPart;
                }
            } else {
                $totalWeighted = 0;
            }

            $total = $peserta->twk + $peserta->tiu + $peserta->tkp;

            return [
                'id' => $peserta->id,
                'nama' => $peserta->nama,
                'twk' => $peserta->twk,
                'tiu' => $peserta->tiu,
                'tkp' => $peserta->tkp,
                'total' => $total,
                'totalWeighted' => $totalWeighted,
                'keterangan' => $peserta->keterangan
            ];
        });

        // Urutkan peserta berdasarkan total nilai dari yang tertinggi ke terendah
        $rankings = $pesertas->sortByDesc('totalWeighted')->values();

        // Update keterangan setiap peserta berdasarkan peringkat dan simpan ke database
        $rankings->each(function ($peserta, $index) {
            $pesertaModel = Peserta::find($peserta['id']);
            $keterangan = ($index < 1251) ? 'Lulus Lanjut' : (($peserta['twk'] >= 65 && $peserta['tiu'] >= 80 && $peserta['tkp'] >= 156) ? 'Lulus' : 'Tidak Lulus');

            $pesertaModel->keterangan = $keterangan;
            $pesertaModel->save();
            $peserta['keterangan'] = $keterangan; // Update array key
        });

        // Ambil ulang data peserta dari database untuk menyertakan keterangan yang sudah diperbarui
        $allPesertas = Peserta::all();

        // Temukan indeks peserta yang baru saja dimasukkan
        $newIndex = $rankings->search(function ($peserta) use ($name) {
            return $peserta['nama'] === $name;
        });

        // Tentukan rentang indeks yang ingin ditampilkan (2 di atas dan 2 di bawah peserta yang baru dimasukkan)
        $start = max(0, $newIndex - 2);
        $end = min(count($rankings), $newIndex + 3);

        // Potong array peringkat untuk menampilkan hanya 5 peserta, 2 di atas dan 2 di bawah peserta yang baru dimasukkan
        $rankings = $rankings->slice($start, $end - $start);

        return view('rankings', ['rankings' => $rankings, 'method' => $method, 'allPesertas'=>$allPesertas]);
    }
}