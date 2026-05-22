<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $sections = \App\Models\HomeSection::all()->keyBy('type');

        // Hero: admin-selected products
        $heroIds = $sections->get('hero')?->product_ids ?? [];
        $heroProducts = !empty($heroIds)
            ? Product::with('categories')->whereIn('id', $heroIds)->get()->sortBy(fn($p) => array_search($p->id, $heroIds))
            : Product::with('categories')->active()->ordered()->take(3)->get();

        // Best-Sellers (2 most sold) + Latest (1)
        $bestsellers = Product::mostSold(2)->get();
        $latestProduct = Product::latestPublished(1)->first();

        // Sélection: left = admin-picked or most sold, right = admin-picked
        $selection = $sections->get('selection');
        $selectionLarge = null;
        $selectionLargeType = 'product';

        // Large card: admin choice or auto (most sold)
        $largeType = $selection?->large_type ?? 'auto';
        if ($largeType === 'product' && $selection?->large_product_id) {
            $selectionLarge = Product::with('categories')->find($selection->large_product_id);
            $selectionLargeType = 'product';
        } elseif ($largeType === 'collection' && $selection?->large_collection_id) {
            $selectionLarge = \App\Models\Collection::find($selection->large_collection_id);
            $selectionLargeType = 'collection';
        }

        if (!$selectionLarge) {
            $selectionLarge = Product::mostSold(1)->first() ?? Product::active()->ordered()->first();
            $selectionLargeType = 'product';
        }

        $selectionSmall = collect();
        $selectionType = 'products'; // 'products' or 'collections'
        $productIds = $selection?->product_ids ?? [];
        $collectionIds = $selection?->collection_ids ?? [];

        if (!empty($productIds)) {
            $selectionSmall = Product::with('categories')->whereIn('id', $productIds)->get()->take(4);
            $selectionType = 'products';
        } elseif (!empty($collectionIds)) {
            $selectionSmall = \App\Models\Collection::active()->whereIn('id', $collectionIds)->get()->take(4);
            $selectionType = 'collections';
        }

        if ($selectionSmall->isEmpty()) {
            $selectionSmall = Product::with('categories')->active()->ordered()->take(4)->get();
            $selectionType = 'products';
        }

        // Notre Boutique: 1 per category, most sold
        $categories = Category::active()->ordered()->get()->take(4);
        $boutiqueProducts = collect();
        $usedIds = [];
        foreach ($categories as $cat) {
            $p = Product::whereHas('categories', fn($q) => $q->where('categories.id', $cat->id))
                ->whereNotIn('products.id', $usedIds)
                ->active()->ordered()->first();
            if ($p) { $boutiqueProducts->push($p); $usedIds[] = $p->id; }
        }
        if ($boutiqueProducts->count() < 4) {
            $extra = Product::whereNotIn('id', $usedIds)->active()->ordered()
                ->take(4 - $boutiqueProducts->count())->get();
            $boutiqueProducts = $boutiqueProducts->concat($extra);
        }

        return view('pages.index', compact(
            'heroProducts', 'bestsellers', 'latestProduct',
            'selectionLarge', 'selectionLargeType', 'selectionSmall', 'selectionType', 'boutiqueProducts'
        ));
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
