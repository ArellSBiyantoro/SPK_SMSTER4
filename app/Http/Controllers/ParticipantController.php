<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    public function index()
    {
        return view('participant.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tiu' => 'required|integer',
            'twk' => 'required|integer',
            'tkp' => 'required|integer',
            'method' => 'required|string',
        ]);

        $participant = new Participant([
            'name' => $request->input('name'),
            'tiu' => $request->input('tiu'),
            'twk' => $request->input('twk'),
            'tkp' => $request->input('tkp'),
        ]);
        $participant->save();

        $method = $request->input('method');
        $status = $this->determineStatus($participant, $method);

        return view('participant.result', compact('participant', 'status', 'method'));
    }

    private function determineStatus(Participant $participant, $method)
    {
        // Kriteria nilai minimal
        $minTWK = 65;
        $minTIU = 80;
        $minTKP = 156;

        if ($participant->twk < $minTWK || $participant->tiu < $minTIU || $participant->tkp < $minTKP) {
            return 'Tidak Lulus';
        }

        if ($method == 'SAW') {
            return $this->determineStatusSAW($participant);
        } elseif ($method == 'WASPAS') {
            return 'Sedang dicoba';
            // return $this->determineStatusWASPAS($participant);
        }

        return 'Lulus Tidak Lanjut';
    }

    private function determineStatusSAW(Participant $participant)
    {
        $criteria = ['tiu', 'twk', 'tkp'];
        $alternatives = Participant::all()->toArray();

        $results = $this->calculateSAW($criteria, $alternatives);

        foreach ($results as $index => $result) {
            if ($result['name'] == $participant->name) {
                $rank = $index + 1;
                break;
            }
        }

        $kuotaFormasi = 417;
        $totalLanjut = $kuotaFormasi * 3;

        return $rank <= $totalLanjut ? 'Lulus Lanjut' : 'Lulus Tidak Lanjut';
    }

    // private function determineStatusWASPAS(Participant $participant)
    // {
    //     $pdo = DB::connection()->getPdo();
    //     $new_student = $participant->toArray();
    //     $new_student['total_score'] = $this->calculateTotalScore($new_student['tiu'], $new_student['twk'], $new_student['tkp']);

    //     $students = Participant::all()->toArray();
    //     $students[] = $new_student;

    //     $results = $this->calculateWASPAS($students);

    //     foreach ($results as $index => $result) {
    //         if ($result['name'] == $participant->name) {
    //             $rank = $index + 1;
    //             break;
    //         }
    //     }

    //     $kuotaFormasi = 417;
    //     $totalLanjut = $kuotaFormasi * 3;

    //     return $rank <= $totalLanjut ? 'Lulus Lanjut' : 'Lulus Tidak Lanjut';
    // }

    private function calculateTotalScore($tiu, $twk, $tkp)
    {
        return ($tkp * 0.5) + ($tiu * 0.3) + ($twk * 0.2);
    }

    private function calculateSAW($criteria, $alternatives)
    {
        $maxValues = [];
        foreach ($criteria as $criterion) {
            $maxValues[$criterion] = max(array_column($alternatives, $criterion));
        }

        $normalized = [];
        foreach ($alternatives as $alternative) {
            $norm = [];
            foreach ($criteria as $criterion) {
                $norm[$criterion] = $alternative[$criterion] / $maxValues[$criterion];
            }
            $normalized[] = $norm;
        }

        $results = [];
        foreach ($normalized as $index => $norm) {
            $score = 0;
            foreach ($criteria as $criterion) {
                $weight = $this->getCriterionWeight($criterion);
                $score += $norm[$criterion] * $weight;
            }
            $results[] = [
                'name' => $alternatives[$index]['name'],
                'score' => $score
            ];
        }

        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $results;
    }

    private function getCriterionWeight($criterion)
    {
        $weights = [
            'tkp' => 0.5,
            'tiu' => 0.3,
            'twk' => 0.2,
        ];
        return $weights[$criterion];
    }

    // private function calculateWASPAS($students)
    // {
    //     $ws_sum = [];
    //     $wp_product = [];
    //     foreach ($students as $student) {
    //         $ws_sum[] = $this->calculateTotalScore($student['tiu'], $student['twk'], $student['tkp']);
    //         $wp_product[] = pow($student['tkp'], 0.5) * pow($student['tiu'], 0.3) * pow($student['twk'], 0.2);
    //     }

    //     $results = [];
    //     foreach ($students as $index => $student) {
    //         $score = (0.5 * $ws_sum[$index]) + (0.5 * $wp_product[$index]);
    //         $results[] = [
    //             'name' => $student['name'],
    //             'score' => $score
    //         ];
    //     }

    //     usort($results, function($a, $b) {
    //         return $b['score'] <=> $a['score'];
    //     });

    //     return $results;
    // }
}