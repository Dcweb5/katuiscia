<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Facture {{ $invoice->invoice_number }}</title>
<style>
@page { margin: 60px 50px; }
body { font-family:'DejaVu Sans',sans-serif;color:#2d2117;font-size:12px;line-height:1.5; }
.header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:40px;padding-bottom:20px;border-bottom:2px solid #c4967a; }
.header-left h1 { font-size:22px;color:#c4967a;margin:0;font-weight:600; }
.header-left p { margin:4px 0;font-size:10px;color:#8b7b6e; }
.header-right { text-align:right; }
.header-right h2 { font-size:18px;color:#2d2117;margin:0 0 4px;text-transform:uppercase;letter-spacing:0.1em; }
.header-right span { font-size:11px;color:#8b7b6e; }
.info { display:flex;gap:50px;margin-bottom:40px; }
.info-box { flex:1; }
.info-box strong { display:block;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#c4967a;margin-bottom:8px; }
.info-box p { margin:2px 0;font-size:11px; }
.order-info { margin-bottom:30px;padding:12px 16px;background:#faf7f2;border-radius:8px;font-size:11px; }
.order-info td { padding:2px 12px 2px 0; }
table { width:100%;border-collapse:collapse;margin-bottom:30px; }
th { background:#faf7f2;padding:10px 12px;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#8b6f5a;border-bottom:2px solid #c4967a; }
td { padding:10px 12px;border-bottom:1px solid #ede4db;font-size:11px; }
.text-right { text-align:right; }
.totals { margin-left:auto;width:280px; }
.totals td { border:none;padding:4px 12px;font-size:11px; }
.totals .total-row td { font-weight:700;font-size:14px;padding-top:12px;border-top:2px solid #c4967a; }
.footer { margin-top:40px;padding-top:20px;border-top:1px solid #ede4db;text-align:center;font-size:10px;color:#a39688; }
.footer p { margin:2px 0; }
</style>
</head>
<body>
<div class="header">
  <div class="header-left">
    <h1>KATUISCIA</h1>
    <p>9 bis route de Corbeil</p>
    <p>91360 Villemoisson-sur-Orge, France</p>
    <p>contact@katuiscia.com</p>
  </div>
  <div class="header-right">
    <h2>Facture</h2>
    <span>{{ $invoice->invoice_number }}</span><br>
    <span>{{ $order->created_at->format('d/m/Y') }}</span>
  </div>
</div>

<div class="info">
  <div class="info-box">
    <strong>Facturé à</strong>
    <p>{{ $order->firstname }} {{ $order->lastname }}</p>
    <p>{{ $order->address }}</p>
    @if($order->address2)<p>{{ $order->address2 }}</p>@endif
    <p>{{ $order->postal_code }} {{ $order->city }}</p>
    <p>{{ $order->country }}</p>
    <p>{{ $order->email }}</p>
  </div>
  <div class="info-box">
    <strong>Commande</strong>
    <p>N° {{ $order->order_number }}</p>
    <p>Paiement : {{ $order->payment_method === 'card' ? 'Carte bancaire' : 'Paiement à la livraison' }}</p>
    <p>Statut : Confirmée</p>
  </div>
</div>

<table>
  <thead>
    <tr><th>Produit</th><th class="text-right">Qté</th><th class="text-right">Prix unitaire</th><th class="text-right">Total</th></tr>
  </thead>
  <tbody>
    @foreach($order->items as $item)
    <tr>
      <td>{{ $item->product_name }}</td>
      <td class="text-right">{{ $item->quantity }}</td>
      <td class="text-right">{{ number_format($item->price, 0, ',', ' ') }} €</td>
      <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} €</td>
    </tr>
    @endforeach
  </tbody>
</table>

<table class="totals">
  <tr><td>Sous-total</td><td class="text-right">{{ number_format($order->subtotal, 0, ',', ' ') }} €</td></tr>
  @if($order->discount > 0)
  <tr><td>Réduction @if($order->coupon_code)({{ $order->coupon_code }})@endif</td><td class="text-right">-{{ number_format($order->discount, 0, ',', ' ') }} €</td></tr>
  @endif
  <tr><td>Livraison</td><td class="text-right">OFFERTE</td></tr>
  <tr class="total-row"><td>TOTAL</td><td class="text-right">{{ number_format($order->total, 0, ',', ' ') }} €</td></tr>
</table>

<div style="padding:14px 18px;background:#faf7f2;border-radius:8px;margin-bottom:20px;font-size:11px;">
  <strong style="color:#c4967a;">📦 Livraison</strong><br>
  Délai estimé : <strong>2 à 5 jours ouvrés</strong><br>
  Expédition sous 72h — vous recevrez un email avec le numéro de suivi dès l'expédition.
</div>

<div class="footer">
  <p>KATUISCIA — Beauté Botanique</p>
  <p>Cette facture est disponible dans votre espace client sur katuiscia.com</p>
  <p>Un lien de téléchargement direct vous a été envoyé par email</p>
</div>
</body>
</html>
