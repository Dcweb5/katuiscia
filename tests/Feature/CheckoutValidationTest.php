<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Modules\Product\Models\Product;
use App\Models\ShippingZone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a default shipping zone for France (other) and Ile-de-France
        ShippingZone::create([
            'name' => 'Île-de-France',
            'code' => 'ile_de_france',
            'price' => 10.00,
            'delivery_time' => '24-48h',
            'is_active' => true,
        ]);

        ShippingZone::create([
            'name' => 'France Métropolitaine',
            'code' => 'france_other',
            'price' => 5.90,
            'delivery_time' => '2-4 jours',
            'is_active' => true,
        ]);
    }

    private function createCartWithProduct($user)
    {
        $product = Product::where('sku', 'BOT-MIN-01')->first() ?: Product::create([
            'name' => 'Botanique de Minuit',
            'price' => 160.00,
            'sku' => 'BOT-MIN-01',
            'stock' => 10,
            'is_active' => true,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 160.00,
        ]);

        return $cart;
    }

    public function test_cod_allowed_only_for_paris()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->createCartWithProduct($user);

        // Attempt checkout with COD in Paris (should succeed and create order)
        $response = $this->post(route('checkout.store'), [
            'email' => 'test@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'address' => '10 Rue de la Paix',
            'postal_code' => '75002',
            'city' => 'Paris',
            'country' => 'FR',
            'region' => 'Île-de-France',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'city' => 'Paris',
            'payment_method' => 'cod',
            'shipping' => 0.00, // COD shipping must be free (0.0)
            'total' => 160.00,  // 160.00 subtotal + 0.00 shipping
        ]);

        // Attempt checkout with COD in Lyon (should fail and redirect back with error)
        $cart2 = $this->createCartWithProduct($user); // cart is cleared on success, create new
        $response2 = $this->post(route('checkout.store'), [
            'email' => 'test@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'address' => '20 Rue Victor Hugo',
            'postal_code' => '69002',
            'city' => 'Lyon',
            'country' => 'FR',
            'region' => 'Auvergne-Rhône-Alpes',
            'payment_method' => 'cod',
        ]);

        $response2->assertSessionHas('error', 'Le paiement à la livraison est disponible uniquement pour la ville de Paris.');
        $this->assertDatabaseMissing('orders', [
            'city' => 'Lyon',
            'payment_method' => 'cod',
        ]);
    }

    public function test_card_payment_respects_admin_shipping_zones()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->createCartWithProduct($user);

        // Card payment in France (other than Île-de-France, e.g. Lyon)
        // Should fetch zone 'france_other' price (5.90 €)
        $response = $this->post(route('checkout.store'), [
            'email' => 'test@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'address' => '20 Rue Victor Hugo',
            'postal_code' => '69002',
            'city' => 'Lyon',
            'country' => 'FR',
            'region' => 'Auvergne-Rhône-Alpes',
            'payment_method' => 'card',
        ]);

        // Card redirect is Stripe checkout page, which returns redirect.
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'city' => 'Lyon',
            'payment_method' => 'card',
            'shipping' => 5.90, // Card shipping should be 5.90 € from the zone
            'total' => 165.90,  // 160.00 subtotal + 5.90 shipping
        ]);
    }
}
