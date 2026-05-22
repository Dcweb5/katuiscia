<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderBy('created_at', 'desc')->get();
        $published = BlogPost::where('status', 'published')->count();
        $drafts = BlogPost::where('status', 'draft')->count();
        $categories = ['Soins Capillaires', 'Ingrédients', 'Tutoriels', 'Bien-être', 'Actualités', 'Rituels de Soin'];

        return view('admin.blog.index', compact('posts', 'published', 'drafts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:2000',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'tags' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageOptimizer::store($request->file('image'), 'blog', 800);
        }

        $validated['slug'] = $this->uniqueSlug($validated['title']);

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Article créé.');
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:2000',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'tags' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageOptimizer::store($request->file('image'), 'blog', 800);
        }

        if ($validated['title'] !== $post->title) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $post->id);
        }

        $post->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return back()->with('success', 'Article supprimé.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:8192']);
        $path = ImageOptimizer::store($request->file('file'), 'blog/content', 1200);
        return response()->json(['location' => asset('storage/' . $path)]);
    }

    private function uniqueSlug($title, $excludeId = null)
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (true) {
            $query = BlogPost::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if (!$query->exists()) break;
            $slug = $base . '-' . ++$counter;
        }

        return $slug;
    }
}
