<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;color:#2d2117;line-height:1.6;max-width:600px;margin:0 auto;">

<div style="text-align:center;padding:20px 0;">
  <h1 style="color:#c4967a;font-size:24px;margin:0;">KATUISCIA</h1>
  <p style="color:#8b7b6e;font-size:13px;">Beauté Botanique</p>
</div>

<h2 style="font-size:18px;color:#2d2117;">Merci pour votre commande, {{ $order->firstname }} !</h2>

<p>Votre commande <strong>{{ $order->order_number }}</strong> a bien été confirmée. La facture est jointe à cet email.</p>

<div style="background:#faf7f2;padding:16px;border-radius:8px;margin:20px 0;">
  <strong>📦 Récapitulatif :</strong><br>
  @foreach($order->items as $item)
  {{ $item->product_name }} ×{{ $item->quantity }} — {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} €<br>
  @endforeach
  <strong style="font-size:16px;">Total : {{ number_format($order->total, 0, ',', ' ') }} €</strong>
</div>

<div style="background:#faf7f2;padding:16px;border-radius:8px;margin:20px 0;">
  <strong>📬 Adresse de livraison :</strong><br>
  {{ $order->firstname }} {{ $order->lastname }}<br>
  {{ $order->address }}<br>
  {{ $order->postal_code }} {{ $order->city }}, {{ $order->country }}
</div>

<div style="background:#faf7f2;padding:16px;border-radius:8px;margin:20px 0;">
  <strong>🚚 Délai de livraison estimé :</strong><br>
  Expédition sous <strong>72 heures</strong> — livraison sous <strong>2 à 5 jours ouvrés</strong>.<br>
  Vous recevrez un email avec votre numéro de suivi dès l'expédition.
</div>

<div style="text-align:center;margin:25px 0;">
  <a href="{{ $invoice->download_url }}" style="display:inline-block;background:#c4967a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;">📄 Télécharger la facture</a>
</div>

<p style="font-size:12px;color:#8b7b6e;text-align:center;">
  Votre facture est également disponible dans votre espace client :<br>
  <a href="{{ url('compte/commandes') }}" style="color:#c4967a;">katuiscia.com/compte/commandes</a>
</p>

<p style="font-size:12px;color:#a39688;text-align:center;margin-top:30px;">
  KATUISCIA — 9 bis route de Corbeil, 91360 Villemoisson-sur-Orge<br>
  contact@katuiscia.com
</p>

</body>
</html>
