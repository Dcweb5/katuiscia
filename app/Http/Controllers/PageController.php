<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('categories')->active()->featured()->ordered()->take(8)->get();
        return view('pages.index', compact('featuredProducts'));
    }

    public function boutique(Request $request)
    {
        $categories = Category::active()->ordered()->get();
        $products = Product::with('categories')->active()->ordered()->get();
        $collections = \App\Models\Collection::active()->with(['products', 'category'])->get();
        return view('pages.boutique', compact('categories', 'products', 'collections'));
    }

    public function produit($slug = null)
    {
        $product = null;
        if ($slug) {
            $product = Product::with(['categories', 'images'])->where('slug', $slug)->active()->firstOrFail();
        }
        $related = Product::with(['categories', 'images'])->active()->where('id', '!=', $product?->id)->ordered()->take(4)->get();
        return view('pages.produit', compact('product', 'related'));
    }

    public function panier()
    {
        return view('pages.panier');
    }

    public function paiement()
    {
        return view('pages.paiement');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function maison()
    {
        return view('pages.maison');
    }

    public function blog(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\BlogPost::published()->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $posts = $query->paginate(9);
        $categories = \App\Models\BlogPost::published()->select('category')->distinct()->pluck('category');

        return view('pages.blog', compact('posts', 'categories'));
    }

    public function blogShow($slug)
    {
        $post = \App\Models\BlogPost::where('slug', $slug)->published()->firstOrFail();
        $post->increment('views');
        $recentPosts = \App\Models\BlogPost::published()->where('id', '!=', $post->id)->orderBy('created_at', 'desc')->take(3)->get();
        return view('pages.blog-single', compact('post', 'recentPosts'));
    }

    public function collectionShow($slug)
    {
        $collection = \App\Models\Collection::where('slug', $slug)->active()->with(['products', 'category'])->firstOrFail();
        return view('pages.collection-show', compact('collection'));
    }
    public function diagnostic()
    {
        return view('pages.diagnostic');
    }

    public function formation()
    {
        return view('pages.formation', ['source' => 'formation']);
    }

    public function grossiste()
    {
        return view('pages.grossiste', ['source' => 'grossiste']);
    }

    public function avantageEnLigne()
    {
        return view('pages.avantage-en-ligne');
    }

    public function valeursBeauteResponsable()
    {
        return view('pages.valeurs-beaute-responsable');
    }

    public function valeursEmballageDurable()
    {
        return view('pages.valeurs-emballage-durable');
    }

    public function valeursPersonneBiodiversite()
    {
        return view('pages.valeurs-personne-biodiversite');
    }

    public function mentionsLegales()
    {
        return view('pages.mentions-legales');
    }

    public function politiqueConfidentialite()
    {
        return view('pages.politique-confidentialite');
    }

    public function politiqueExpedition()
    {
        return view('pages.politique-expedition');
    }

    public function politiqueRemboursement()
    {
        return view('pages.politique-remboursement');
    }
}
