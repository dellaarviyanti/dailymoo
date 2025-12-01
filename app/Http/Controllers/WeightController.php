<?php

namespace App\Http\Controllers;

use App\Models\CowWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WeightController extends Controller
{
    /**
     * Display weight management page
     */
    public function index()
    {
        // Get latest weights for all 10 cows
        $latestWeights = [];
        for ($i = 1; $i <= 10; $i++) {
            $latestWeight = CowWeight::where('cow_id', $i)
                ->latest('measured_at')
                ->first();
            
            $latestWeights[$i] = $latestWeight;
        }

        // Get weight history for chart
        $weightHistory = CowWeight::getWeightsForChart();

        // Get all weights with pagination
        $allWeights = CowWeight::orderBy('measured_at', 'desc')
            ->orderBy('cow_id', 'asc')
            ->paginate(10);

        // Jika request AJAX, return hanya bagian history table
        if (request()->ajax()) {
            return response()->json([
                'html' => view('weight.partials.history-table', compact('allWeights'))->render(),
                'total' => $allWeights->total()
            ]);
        }

        return view('weight.index', compact('latestWeights', 'weightHistory', 'allWeights'));
    }

    /**
     * Store weight measurements
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'measured_at' => 'required|date',
            'weights' => 'required|array|size:10',
            'weights.*' => 'required|numeric|min:0',
            'feed_suggestions' => 'required|array|size:10',
            'feed_suggestions.*' => 'nullable|numeric|min:0',
            'agreements' => 'required|array|size:10',
            'agreements.*' => 'required|in:agree,reject',
            'custom_feed' => 'nullable|array',
            'custom_feed.*' => 'nullable|numeric|min:0|max:1000',
        ]);

        $agreements = $validated['agreements'];
        $customFeed = array_replace(
            array_fill(0, 10, null),
            $validated['custom_feed'] ?? []
        );

        foreach ($agreements as $index => $agreement) {
            if ($agreement === 'reject' && empty($customFeed[$index])) {
                throw ValidationException::withMessages([
                    "custom_feed.$index" => 'Masukkan bobot baru untuk sapi #' . ($index + 1) . ' jika tidak menyetujui saran.',
                ]);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($validated['weights'] as $cowId => $weight) {
                $suggestion = $validated['feed_suggestions'][$cowId] ?? null;
                $agreement = $agreements[$cowId] ?? 'agree';
                $revisedFeed = $customFeed[$cowId] ?? null;

                CowWeight::create([
                    'cow_id' => $cowId + 1, // Array index starts at 0, cow_id starts at 1
                    'weight' => $weight,
                    'measured_at' => $validated['measured_at'],
                    'notes' => $this->buildFeedNote($suggestion, $agreement, $revisedFeed),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('weight.index')
                ->with('success', 'Data bobot sapi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    private function buildFeedNote(?float $suggestion, string $agreement, ?float $revised): ?string
    {
        $parts = [];

        if (!is_null($suggestion)) {
            $parts[] = 'Saran pakan: ' . number_format($suggestion, 1) . ' kg';
        }

        $parts[] = 'Persetujuan: ' . ($agreement === 'agree' ? 'Setuju' : 'Tidak Setuju');

        if ($agreement === 'reject' && !is_null($revised)) {
            $parts[] = 'Bobot baru: ' . number_format($revised, 1) . ' kg';
        }

        return empty($parts) ? null : implode(' | ', $parts);
    }

    /**
     * Show edit form for specific weight record
     */
    public function edit(CowWeight $weight)
    {
        // Get all weights for the same cow
        $cowWeights = CowWeight::where('cow_id', $weight->cow_id)
            ->orderBy('measured_at', 'desc')
            ->get();

        return view('weight.edit', compact('weight', 'cowWeights'));
    }

    /**
     * Update weight record
     */
    public function update(Request $request, CowWeight $weight)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0|max:1000',
            'measured_at' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $weight->update($validated);

        return redirect()
            ->route('weight.index')
            ->with('success', 'Data bobot sapi berhasil diperbarui!');
    }

    /**
     * Delete weight record
     */
    public function destroy(CowWeight $weight)
    {
        $cowId = $weight->cow_id;
        $weight->delete();

        return redirect()
            ->route('weight.index')
            ->with('success', 'Data bobot sapi berhasil dihapus!');
    }

    /**
     * Clear all weight history (for testing/development)
     */
    public function clearAll()
    {
        CowWeight::truncate();

        return redirect()
            ->route('weight.index')
            ->with('success', 'Semua history data bobot sapi berhasil dikosongkan!');
    }

    /**
     * Get weight data for ML processing
     */
    // public function getWeightData()
    // {
    //     $weights = CowWeight::select('cow_id', 'weight', 'measured_at')
    //         ->orderBy('measured_at', 'desc')
    //         ->orderBy('cow_id', 'asc')
    //         ->get();

    //     return response()->json([
    //         'data' => $weights,
    //         'total_records' => $weights->count(),
    //     ]);
    // }

    // Fungsi prediksi ML
    public function predictBK(Request $request)
    {
        try {
            $weight = $request->input('weight');
            $umur = $request->input('umur');

            // Validasi input
            if (empty($weight) || empty($umur)) {
                return response()->json([
                    'error' => 'Weight dan umur harus diisi'
                ], 400);
            }

            // Path ke Python script
            $pythonScript = public_path('model/predict.py');
            $scriptDir = public_path('model');
            
            // Pastikan file script ada
            if (!file_exists($pythonScript)) {
                return response()->json([
                    'error' => 'Python script tidak ditemukan: ' . $pythonScript
                ], 500);
            }

            // Pastikan model file ada
            $modelFile = $scriptDir . '/model_pakan_2fitur.joblib';
            if (!file_exists($modelFile)) {
                return response()->json([
                    'error' => 'Model file tidak ditemukan: ' . $modelFile
                ], 500);
            }

            // Simpan working directory saat ini
            $originalDir = getcwd();
            
            // Ubah working directory ke folder model
            chdir($scriptDir);
            
            try {
                // Jalankan Python script dengan parameter weight dan umur
                // Redirect stderr ke null untuk menghindari warning (NUL untuk Windows, /dev/null untuk Linux)
                $nullDevice = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'NUL' : '/dev/null';
                
                // Gunakan path relatif karena sudah di folder model
                $scriptName = basename($pythonScript);
                
                // Coba python dulu, jika tidak ada coba python3
                $pythonCmd = 'python';
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Windows: coba python.exe atau python
                    $pythonCmd = 'python';
                } else {
                    // Linux/Mac: coba python3 dulu
                    $pythonCmd = 'python3';
                }
                
                $command = sprintf(
                    '%s "%s" %s %s 2>%s',
                    $pythonCmd,
                    escapeshellarg($scriptName),
                    escapeshellarg($weight),
                    escapeshellarg($umur),
                    $nullDevice
                );
                
                $output = shell_exec($command);
                
                // Jika output kosong, coba dengan python3 (untuk Windows yang punya keduanya)
                if (empty($output) && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $command = sprintf(
                        'python3 "%s" %s %s 2>%s',
                        escapeshellarg($scriptName),
                        escapeshellarg($weight),
                        escapeshellarg($umur),
                        $nullDevice
                    );
                    $output = shell_exec($command);
                }
            } finally {
                // Kembalikan working directory ke semula
                chdir($originalDir);
            }
            
            // Debug: log output untuk troubleshooting
            \Log::info('Python script output', [
                'command' => $command,
                'output' => $output,
                'output_length' => strlen($output ?? ''),
                'is_null' => is_null($output)
            ]);
            
            // Jika output null atau kosong, coba tanpa redirect stderr untuk melihat error
            if (empty($output) || is_null($output)) {
                // Coba lagi tanpa redirect stderr untuk melihat error
                chdir($scriptDir);
                try {
                    $scriptName = basename($pythonScript);
                    $debugCommand = sprintf(
                        'python "%s" %s %s',
                        escapeshellarg($scriptName),
                        escapeshellarg($weight),
                        escapeshellarg($umur)
                    );
                    $debugOutput = shell_exec($debugCommand);
                } finally {
                    chdir($originalDir);
                }
                
                return response()->json([
                    'error' => 'Python script tidak menghasilkan output',
                    'debug' => [
                        'command' => $command,
                        'output' => $output,
                        'debug_output' => $debugOutput,
                        'script_path' => $pythonScript,
                        'script_dir' => $scriptDir,
                        'model_file' => $modelFile,
                        'current_dir' => getcwd()
                    ]
                ], 500);
            }
            
            // Bersihkan output dari whitespace dan ambil angka
            $output = trim($output);
            
            // Cari angka float (termasuk desimal) di output
            // Pattern: angka yang bisa memiliki titik desimal
            if (preg_match('/\d+\.\d+|\d+/', $output, $matches)) {
                // Ambil match pertama (hasil prediksi)
                $cleanOutput = $matches[0];
            } else {
                // Jika tidak ada match, coba langsung parse sebagai float
                $cleanOutput = $output;
            }

            // Validasi bahwa output adalah angka yang valid
            if (empty($cleanOutput) || !is_numeric($cleanOutput)) {
                return response()->json([
                    'error' => 'Gagal mendapatkan hasil prediksi',
                    'debug' => [
                        'raw_output' => $output,
                        'cleaned_output' => $cleanOutput,
                        'command' => $command
                    ]
                ], 500);
            }

            $bk = floatval($cleanOutput);

            return response()->json(['bk' => $bk]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}


