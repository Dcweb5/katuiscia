<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      background-color: #faf7f2;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #3d2b2b;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #ffffff;
      border: 1px solid #ede4db;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .header {
      background-color: #c4967a;
      padding: 30px;
      text-align: center;
    }
    .body {
      padding: 40px 30px;
      line-height: 1.6;
      font-size: 15px;
      color: #3d2b2b;
    }
    .footer {
      background-color: #f7f3ed;
      padding: 24px 30px;
      text-align: center;
      font-size: 11px;
      color: #8c7e7e;
      border-top: 1px solid #ede4db;
      line-height: 1.5;
    }
    .footer a {
      color: #c4967a;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div style="background-color:#faf7f2; padding: 20px 10px; min-height: 100%;">
    <div class="container">
      <div class="header">
        <span style="color:#ffffff; font-family:'Georgia', serif; font-size: 26px; letter-spacing: 4px; text-transform: uppercase;">KATUISCIA</span>
      </div>
      <div class="body">
        {!! $content !!}
      </div>
      <div class="footer">
        <p style="margin: 0 0 8px 0;">© {{ date('Y') }} KATUISCIA. Tous droits réservés.</p>
        <p style="margin: 0 0 8px 0;">Vous recevez ce message car vous êtes abonné(e) à la newsletter KATUISCIA.</p>
        <p style="margin: 0;"><a href="{{ $unsubscribeUrl }}">Se désabonner de cette liste</a></p>
      </div>
    </div>
  </div>
</body>
</html>
