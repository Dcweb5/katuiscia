<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ========== CATÉGORIES ==========
        $categories = [
            ['slug' => 'soin-peau',       'name' => 'Soin de la peau',  'description' => 'Soins visage pour sublimer votre peau au quotidien.',      'order' => 1],
            ['slug' => 'corps',            'name' => 'Corps',            'description' => 'Soins corporels pour nourrir et hydrater votre corps.',      'order' => 2],
            ['slug' => 'parfum',           'name' => 'Parfum',           'description' => 'Parfums et eaux de senteur aux notes botaniques.',            'order' => 3],
            ['slug' => 'outils',           'name' => 'Outils de Rituel', 'description' => 'Accessoires et outils pour sublimer votre rituel beauté.',    'order' => 4],
            ['slug' => 'routine-capillaires', 'name' => 'Capillaire',   'description' => 'Soins capillaires naturels pour des cheveux en pleine santé.', 'order' => 5],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }

        $this->command->info('Catégories créées.');

        // ========== LIENS CATÉGORIES ==========
        $catSoin   = Category::where('slug', 'soin-peau')->first();
        $catCorps  = Category::where('slug', 'corps')->first();
        $catParfum = Category::where('slug', 'parfum')->first();
        $catOutils = Category::where('slug', 'outils')->first();

        // ========== PRODUITS ==========
        $products = [
            [
                'name' => 'Nectar Lumineux',
                'slug' => 'nectar-lumineux',
                'description' => 'Huile Visage Réparatrice',
                'long_description' => 'Une huile précieuse aux actifs botaniques concentrés pour restaurer l\'éclat naturel du visage. Enrichie en vitamines et acides gras essentiels.',
                'price' => 125,
                'badge' => 'Best-seller',
                'need' => 'eclat',
                'image_primary' => 'assets/images/product-1a.png',
                'image_secondary' => 'assets/images/product-1b.png',
                'size' => '30ml',
                'categories' => [$catSoin->id],
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Botanique de Minuit',
                'slug' => 'botanique-de-minuit',
                'description' => 'Essence Nocturne',
                'long_description' => 'Un soin nocturne régénérant qui agit en profondeur pendant votre sommeil. Formulé à base d\'extraits botaniques rares.',
                'price' => 160,
                'badge' => null,
                'need' => 'restauration',
                'image_primary' => 'assets/images/product-2a.png',
                'image_secondary' => 'assets/images/product-2b.png',
                'size' => '50ml',
                'categories' => [$catSoin->id],
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Gommage Terracotta',
                'slug' => 'gommage-terracotta',
                'description' => 'Masque Purifiant à l\'Argile',
                'long_description' => 'Un gommage doux à l\'argile terracotta qui purifie et affine le grain de peau. Idéal pour les peaux mixtes à grasses.',
                'price' => 85,
                'badge' => 'Nouveau',
                'need' => 'eclat',
                'image_primary' => 'assets/images/product-3a.png',
                'image_secondary' => 'assets/images/product-3b.png',
                'size' => '75ml',
                'categories' => [$catSoin->id],
                'order' => 3,
            ],
            [
                'name' => 'Baume Aura',
                'slug' => 'baume-aura',
                'description' => 'Nettoyant Fondant',
                'long_description' => 'Un baume nettoyant fondant qui se transforme en huile puis en lait. Démaquille en douceur tout en respectant le film hydrolipidique.',
                'price' => 85,
                'badge' => null,
                'need' => 'hydratation',
                'image_primary' => 'assets/images/product-4a.png',
                'image_secondary' => 'assets/images/product-4b.png',
                'size' => '100ml',
                'categories' => [$catCorps->id, $catSoin->id],
                'order' => 4,
            ],
            [
                'name' => 'Émulsion Soyeuse',
                'slug' => 'emulsion-soyeuse',
                'description' => 'Hydratant Corporel',
                'long_description' => 'Une émulsion légère et soyeuse qui hydrate le corps sans effet gras. Pénètre rapidement pour un confort immédiat.',
                'price' => 95,
                'badge' => null,
                'need' => 'hydratation',
                'image_primary' => 'assets/images/product-5a.png',
                'image_secondary' => 'assets/images/product-5b.png',
                'size' => '200ml',
                'categories' => [$catCorps->id],
                'order' => 5,
            ],
            [
                'name' => 'Sérum Éclat',
                'slug' => 'serum-eclat',
                'description' => 'Concentré Vitamine C',
                'long_description' => 'Un sérum concentré à la Vitamine C stabilisée pour illuminer le teint et réduire les taches pigmentaires. Texture légère à pénétration rapide.',
                'price' => 110,
                'badge' => 'Best-seller',
                'need' => 'eclat',
                'image_primary' => 'assets/images/product-6a.png',
                'image_secondary' => 'assets/images/product-6b.png',
                'size' => '30ml',
                'categories' => [$catSoin->id],
                'is_featured' => true,
                'order' => 6,
            ],
            [
                'name' => 'Crème Velours',
                'slug' => 'creme-velours',
                'description' => 'Soin Nuit Régénérant',
                'long_description' => 'Une crème onctueuse à la texture velours qui régénère la peau pendant la nuit. Résultats visibles dès le réveil.',
                'price' => 145,
                'badge' => null,
                'need' => 'restauration',
                'image_primary' => 'assets/images/product-7a.png',
                'image_secondary' => 'assets/images/product-7b.png',
                'size' => '50ml',
                'categories' => [$catSoin->id],
                'order' => 7,
            ],
            [
                'name' => 'Lait Céleste',
                'slug' => 'lait-celeste',
                'description' => 'Démaquillant Doux',
                'long_description' => 'Un lait démaquillant doux et onctueux qui élimine en douceur le maquillage et les impuretés. Enrichi en extraits de camomille.',
                'price' => 65,
                'badge' => 'Nouveau',
                'need' => 'hydratation',
                'image_primary' => 'assets/images/product-8a.png',
                'image_secondary' => 'assets/images/product-8b.png',
                'size' => '150ml',
                'categories' => [$catSoin->id],
                'order' => 8,
            ],
            [
                'name' => 'Brume Sacrée',
                'slug' => 'brume-sacree',
                'description' => 'Eau de Parfum Florale',
                'long_description' => 'Une eau de parfum aux notes florales délicates. Un voyage sensoriel au cœur de la botanique.',
                'price' => 180,
                'badge' => null,
                'need' => null,
                'image_primary' => 'assets/images/product-9a.png',
                'image_secondary' => 'assets/images/product-9b.png',
                'size' => '50ml',
                'categories' => [$catParfum->id],
                'order' => 9,
            ],
            [
                'name' => 'Crème Douce',
                'slug' => 'creme-douce',
                'description' => 'Soin Hydratant Quotidien',
                'long_description' => 'Une crème légère et confortable pour une hydratation quotidienne. Texture fondante qui laisse la peau douce et souple.',
                'price' => 75,
                'badge' => 'TOP',
                'need' => 'hydratation',
                'image_primary' => 'assets/images/product-4a.png',
                'image_secondary' => 'assets/images/product-4b.png',
                'size' => '50ml',
                'categories' => [$catSoin->id],
                'order' => 10,
            ],
            [
                'name' => 'Lotion Purifiante',
                'slug' => 'lotion-purifiante',
                'description' => 'Tonicaire Purifiant',
                'long_description' => 'Une lotion tonique purifiante qui resserre les pores et tonifie la peau. Parfaite pour les peaux à tendance acnéique.',
                'price' => 55,
                'badge' => 'TOP',
                'need' => 'eclat',
                'image_primary' => 'assets/images/product-8a.png',
                'image_secondary' => 'assets/images/product-8b.png',
                'size' => '200ml',
                'categories' => [$catSoin->id],
                'order' => 11,
            ],
        ];

        foreach ($products as $data) {
            $categoriesIds = $data['categories'] ?? [];
            unset($data['categories']);

            $product = Product::create($data);
            if (!empty($categoriesIds)) {
                $product->categories()->sync($categoriesIds);
            }

            // Créer les entrées product_images à partir de image_primary / image_secondary
            if ($data['image_primary'] ?? null) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $data['image_primary'],
                    'is_primary' => true,
                    'order' => 0,
                ]);
            }
            if (($data['image_secondary'] ?? null) && $data['image_secondary'] !== ($data['image_primary'] ?? '')) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $data['image_secondary'],
                    'is_primary' => false,
                    'order' => 1,
                ]);
            }
        }

        $this->command->info('Produits créés : ' . count($products));
    }
}
