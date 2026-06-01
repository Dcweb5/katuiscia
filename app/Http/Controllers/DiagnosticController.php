<?php

namespace App\Http\Controllers;

use App\Models\SkinDiagnostic;
use App\Services\GeminiService;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiagnosticController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Submit onboarding skin diagnostic request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'skin_type' => 'required|string|max:100',
            'concern' => 'required|string|max:255',
            'current_products' => 'nullable|string|max:2000',
            'selfie' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240', // Max 10MB
        ]);

        try {
            // Store and optimize selfie image
            $file = $request->file('selfie');
            $path = ImageOptimizer::store($file, 'diagnostics', 1200);

            // Formulate answers array for Gemini
            $answers = [
                'name' => $validated['name'],
                'skin_type' => $validated['skin_type'],
                'concern' => $validated['concern'],
                'current_products' => $validated['current_products'] ?? 'Aucun',
            ];

            // Resolve absolute file path for Gemini analysis
            $absolutePath = storage_path('app/public/' . $path);

            // Perform skin analysis
            $analysisResult = $this->gemini->analyze($absolutePath, $answers);

            // Enrich recommendations with actual product details from database
            if (isset($analysisResult['recommended_products']) && is_array($analysisResult['recommended_products'])) {
                foreach ($analysisResult['recommended_products'] as &$rec) {
                    $productId = $rec['product_id'] ?? null;
                    $dbProduct = null;
                    if ($productId) {
                        $dbProduct = \App\Modules\Product\Models\Product::find($productId);
                    }
                    
                    if ($dbProduct) {
                        $rec['image'] = $dbProduct->image_url;
                        $rec['slug'] = $dbProduct->slug;
                        $rec['price'] = $dbProduct->display_price;
                    } else {
                        $rec['image'] = asset('assets/images/product-placeholder.jpg');
                        $rec['slug'] = '';
                        $rec['price'] = '';
                    }
                }
                unset($rec);
            }

            // Create db entry
            $diagnostic = SkinDiagnostic::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'skin_type' => $validated['skin_type'],
                'concern' => $validated['concern'],
                'current_products' => $validated['current_products'],
                'image_path' => 'storage/' . $path,
                'analysis_result' => $analysisResult,
            ]);

            return response()->json([
                'success' => true,
                'diagnostic' => $diagnostic,
                'result' => $analysisResult,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'analyse de votre peau : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin index: List all skin diagnostics.
     */
    public function adminIndex(Request $request)
    {
        $query = SkinDiagnostic::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('concern', 'like', "%{$search}%");
            });
        }

        $diagnostics = $query->paginate(15);

        return view('admin.diagnostics.index', compact('diagnostics'));
    }

    /**
     * Admin delete: Remove a diagnostic.
     */
    public function adminDestroy(SkinDiagnostic $diagnostic)
    {
        // Delete image file from storage
        $relativePath = str_replace('storage/', '', $diagnostic->image_path);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $diagnostic->delete();

        return back()->with('success', 'Diagnostic supprimé avec succès.');
    }
}
