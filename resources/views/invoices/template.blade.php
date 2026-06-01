<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture {{ $invoice->invoice_number }}</title>
<style>
@page { margin: 40px 40px; }
body { font-family: 'DejaVu Sans', sans-serif; color: #3d2928; font-size: 11px; line-height: 1.4; }
.text-right { text-align: right; }
.text-left { text-align: left; }
.bold { font-weight: bold; }
.color-warm { color: #c4967a; }

/* Header table (logo & invoice meta) */
.header-table { width: 100%; border-bottom: 2px solid #ede4db; padding-bottom: 12px; margin-bottom: 20px; }
.header-table td { border: none; padding: 0; }
.logo { font-size: 24px; font-weight: bold; color: #c4967a; margin: 0; letter-spacing: 0.05em; }
.company-info { font-size: 9px; color: #8c7e74; margin-top: 4px; line-height: 1.3; }
.invoice-title { font-size: 18px; color: #3d2928; margin: 0; text-transform: uppercase; letter-spacing: 0.08em; }
.invoice-meta { font-size: 10px; color: #8c7e74; margin-top: 4px; line-height: 1.3; }

/* Info table (billing details & order summary) */
.info-table { width: 100%; margin-bottom: 20px; background: #faf7f2; border-radius: 8px; border: 1px solid #ede4db; }
.info-table td { border: none; padding: 12px 16px; vertical-align: top; }
.info-section-title { font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: #c4967a; font-weight: bold; margin-bottom: 6px; }

/* Products Table */
.products-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.products-table th { background: #faf7f2; padding: 8px 12px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: #8c7e74; border-bottom: 1px solid #ede4db; }
.products-table td { padding: 10px 12px; border-bottom: 1px solid #ede4db; font-size: 11px; }

/* Totals */
.totals-container-table { width: 100%; margin-bottom: 20px; }
.totals-container-table td { border: none; padding: 0; }
.totals-table { width: 260px; float: right; border-collapse: collapse; }
.totals-table td { padding: 4px 8px; font-size: 11px; border: none; }
.totals-table .total-row td { font-weight: bold; font-size: 13px; color: #3d2928; padding-top: 8px; border-top: 1px solid #ede4db; }

/* Delivery note */
.delivery-note { padding: 10px 14px; background: #faf7f2; border-radius: 6px; border: 1px solid #ede4db; font-size: 10px; margin-bottom: 20px; clear: both; }

/* Footer */
.footer { border-top: 1px solid #ede4db; padding-top: 15px; text-align: center; font-size: 9px; color: #8c7e74; margin-top: 20px; }
.footer p { margin: 2px 0; }
</style>
</head>
<body>

<!-- Header (Logo & Facture Details) -->
<table class="header-table">
  <tr>
    <td style="width: 50%;">
      <div class="logo">KATUISCIA</div>
      <div class="company-info">
        9 bis route de Corbeil, 91360 Villemoisson-sur-Orge, France<br>
        contact@katuiscia.com | katuiscia.com
      </div>
    </td>
    <td style="width: 50%;" class="text-right">
      <div class="invoice-title">Facture</div>
      <div class="invoice-meta">
        Référence : <span class="bold" style="color:#3d2928;">{{ $invoice->invoice_number }}</span><br>
        Date d'émission : {{ $order->created_at->format('d/m/Y') }}
      </div>
    </td>
  </tr>
</table>

<!-- Billing Info & Order Info (Table layout instead of Flexbox) -->
<table class="info-table">
  <tr>
    <td style="width: 55%; border-right: 1px solid #ede4db;">
      <div class="info-section-title">Facturé à</div>
      <div style="font-size: 11px; line-height: 1.4;">
        <span class="bold">{{ $order->firstname }} {{ $order->lastname }}</span> ({{ $order->email }})<br>
        {{ $order->address }}{{ $order->address2 ? ', ' . $order->address2 : '' }}<br>
        {{ $order->postal_code }} {{ $order->city }}, {{ $order->country }}
      </div>
    </td>
    <td style="width: 45%; padding-left: 20px;">
      <div class="info-section-title">Commande</div>
      <div style="font-size: 11px; line-height: 1.4;">
        N° : <span class="bold">{{ $order->order_number }}</span><br>
        Date : {{ $order->created_at->format('d/m/Y') }}<br>
        Paiement : {{ $order->payment_method === 'card' ? 'Carte bancaire' : 'Paiement à la livraison' }}
      </div>
    </td>
  </tr>
</table>

<!-- Products Table -->
<table class="products-table">
  <thead>
    <tr>
      <th>Désignation du produit</th>
      <th style="width: 10%;" class="text-right">Qté</th>
      <th style="width: 20%;" class="text-right">Prix unitaire</th>
      <th style="width: 20%;" class="text-right">Montant HT</th>
    </tr>
  </thead>
  <tbody>
    @foreach($order->items as $item)
    <tr>
      <td>{{ $item->product_name }}</td>
      <td class="text-right">{{ $item->quantity }}</td>
      <td class="text-right">{{ number_format($item->price, 2, ',', ' ') }} €</td>
      <td class="text-right">{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
    </tr>
    @endforeach
  </tbody>
</table>

<!-- Totals Area -->
<table class="totals-container-table">
  <tr>
    <td style="width: 50%;"></td>
    <td style="width: 50%;">
      <table class="totals-table">
        <tr>
          <td>Sous-total</td>
          <td class="text-right">{{ number_format($order->subtotal, 2, ',', ' ') }} €</td>
        </tr>
        @if($order->discount > 0)
        <tr>
          <td>Réduction @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
          <td class="text-right" style="color: #c4967a;">-{{ number_format($order->discount, 2, ',', ' ') }} €</td>
        </tr>
        @endif
        <tr>
          <td>Livraison</td>
          <td class="text-right">
            @if($order->shipping == 0)
              Gratuite
            @else
              {{ number_format($order->shipping, 2, ',', ' ') }} €
            @endif
          </td>
        </tr>
        <tr class="total-row">
          <td>TOTAL NET</td>
          <td class="text-right">{{ number_format($order->total, 2, ',', ' ') }} €</td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!-- Delivery Info -->
<div class="delivery-note">
  <span class="bold color-warm">📦 Note de livraison</span><br>
  Expédition sous 48h/72h — un email contenant votre numéro de suivi vous sera envoyé dès la prise en charge du colis par le transporteur.
</div>

<!-- Footer -->
<div class="footer">
  <p>KATUISCIA — Beauté Botanique & Soin Holistique</p>
  <p>Merci pour votre confiance ! Cette facture est disponible à tout moment dans votre espace client sur katuiscia.com</p>
</div>

</body>
</html>
