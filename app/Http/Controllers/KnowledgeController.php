<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KnowledgeController extends Controller
{
    public function index()
    {
        $articles = Knowledge::orderByDesc('date')->get();
        $categories = Knowledge::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('knowledge.index', compact('articles', 'categories'));
    }

    public function show(Knowledge $knowledge)
    {
        return view('knowledge.show', ['article' => $knowledge]);
    }

    /**
     * Display knowledge article image
     */
    public function showImage(Knowledge $knowledge)
    {
        if (!$knowledge->image || Str::startsWith($knowledge->image, ['http://', 'https://'])) {
            abort(404);
        }

        $path = storage_path('app/public/' . $knowledge->image);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function store(Request $request)
    {
        try {
            $data = $this->validatedData($request, 'knowledgeStore');
            $data['image'] = $this->resolveImagePath($request);

            Knowledge::create($data);

            return redirect()
                ->route('knowledge')
                ->with('success', 'Artikel berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['knowledgeStore' => ['image_file' => $e->getMessage()]]);
        }
    }

    public function update(Request $request, Knowledge $knowledge)
    {
        try {
            $data = $this->validatedData($request, 'knowledgeUpdate');
            $data['image'] = $this->resolveImagePath($request, $knowledge->image);

            $knowledge->update($data);

            return redirect()
                ->route('knowledge')
                ->with('success', 'Artikel berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['knowledgeUpdate' => ['image_file' => $e->getMessage()]]);
        }
    }

    public function destroy(Knowledge $knowledge)
    {
        $this->deleteImageIfLocal($knowledge->image);
        $knowledge->delete();

        return redirect()
            ->route('knowledge')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    private function validatedData(Request $request, string $errorBag): array
    {
        $validated = $request->validateWithBag($errorBag, [
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|max:2048',
        ]);

        unset($validated['image_url'], $validated['image_file']);

        return $validated;
    }

    private function resolveImagePath(Request $request, ?string $currentImage = null): string
    {
        // Priority 1: Upload file (if provided) - highest priority
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            
            // Validate file
            if ($file->isValid()) {
                try {
                    $this->deleteImageIfLocal($currentImage);
                    
                    // Ensure storage directory exists
                    if (!Storage::disk('public')->exists('knowledge')) {
                        Storage::disk('public')->makeDirectory('knowledge');
                    }
                    
                    // Store file
                    $path = $file->store('knowledge', 'public');
                    
                    if ($path) {
                        return $path;
                    }
                } catch (\Exception $e) {
                    // If file upload fails, log error
                    \Log::error('Failed to upload knowledge image: ' . $e->getMessage());
                    // Don't fall through - throw error so user knows
                    throw new \Exception('Gagal mengupload gambar: ' . $e->getMessage());
                }
            } else {
                throw new \Exception('File gambar tidak valid atau rusak.');
            }
        }

        // Priority 2: URL (if provided and no file was uploaded)
        if ($request->filled('image_url')) {
            $this->deleteImageIfLocal($currentImage);
            return $request->input('image_url');
        }

        // Priority 3: Keep current image or use placeholder
        return $currentImage ?? 'https://placehold.co/800x600/5DB996/ffffff?text=DailyMoo';
    }

    private function deleteImageIfLocal(?string $path): void
    {
        if (!$path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
