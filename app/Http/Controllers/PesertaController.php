<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;

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

        $name = $request->input('name');
        $tiu = $request->input('tiu');
        $twk = $request->input('twk');
        $tkp = $request->input('tkp');
        $method = $request->input('method');

        $peserta = new Peserta();
        $peserta->nama = $name;
        $peserta->tiu = $tiu;
        $peserta->twk = $twk;
        $peserta->tkp = $tkp;
        $peserta->save();

        // Filter peserta yang memenuhi syarat
        $pesertas = Peserta::all()->filter(function ($peserta) {
            return $peserta->twk >= 65 && $peserta->tiu >= 80 && $peserta->tkp >= 156;
        });

        // Tambahkan peserta yang tidak memenuhi syarat dengan keterangan "Tidak Lulus"
        $tidakLulusPesertas = Peserta::all()->reject(function ($peserta) {
            return $peserta->twk >= 65 && $peserta->tiu >= 80 && $peserta->tkp >= 156;
        })->map(function ($peserta) {
            return [
                'id' => $peserta->id,
                'nama' => $peserta->nama,
                'twk' => $peserta->twk,
                'tiu' => $peserta->tiu,
                'tkp' => $peserta->tkp,
                'total' => $peserta->twk + $peserta->tiu + $peserta->tkp,
                'keterangan' => 'Tidak Lulus'
            ];
        });

        // Gabungkan koleksi peserta yang lulus dan tidak lulus
        $pesertas = $pesertas->concat($tidakLulusPesertas);

        // Hitung total nilai dan tentukan kelulusan
        $rankings = $pesertas->map(function($peserta) use ($method) {
            $total = $peserta['twk'] + $peserta['tiu'] + $peserta['tkp'];
            $lulus = $total >= (65 + 80 + 156); // Contoh syarat total minimal
            $keterangan = $lulus ? 'Lulus' : 'Tidak Lulus';

            // Jika peserta masuk dalam kuota dan metode adalah "WASPAS" atau "SAW", ubah keterangan menjadi "Lulus Lanjut"
            if ($lulus) {
                $keterangan = 'Lulus Lanjut';
            }

            return [
                'id' => $peserta['id'],
                'nama' => $peserta['nama'],
                'twk' => $peserta['twk'],
                'tiu' => $peserta['tiu'],
                'tkp' => $peserta['tkp'],
                'total' => $total,
                'keterangan' => $keterangan
            ];
        });

        // Urutkan peserta berdasarkan total nilai dari yang tertinggi ke terendah
        $rankings = $rankings->sortByDesc('total')->values();

        // Temukan indeks peserta yang baru saja dimasukkan
        $newIndex = $rankings->search(function ($peserta) use ($name) {
            return $peserta['nama'] === $name;
        });

        // Tentukan rentang indeks yang ingin ditampilkan (2 di atas dan 2 di bawah peserta yang baru dimasukkan)
        $start = max(0, $newIndex - 2);
        $end = min(count($rankings), $newIndex + 3);

        // Potong array peringkat untuk menampilkan hanya 5 peserta, 2 di atas dan 2 di bawah peserta yang baru dimasukkan
        $rankings = $rankings->slice($start, $end - $start);

        // Jika peserta berada di luar peringkat 1251 dan memenuhi syarat minimal, ubah keterangan menjadi "Lulus"
        if ($newIndex !== false && $newIndex >= 1251) {
            $pesertaTotal = $rankings[$newIndex]['total'];
            if ($pesertaTotal >= (65 + 80 + 156)) {
                $rankings = $rankings->map(function ($item, $key) use ($newIndex) {
                    if ($key == $newIndex) {
                        $item['keterangan'] = 'Lulus';
                    }
                    return $item;
                });
            }
        }

        return view('rankings', ['rankings' => $rankings, 'method' => $method]);
    }
}