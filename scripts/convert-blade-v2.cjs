const fs = require('fs');
const path = require('path');

const rootDir = path.resolve(__dirname, '..');

const pages = {
  'maison.html':     { title: 'Maison — KATUISCIA | Histoire & Origine', extraCss: ['about.css'] },
  'blog.html':       { title: 'Blog — KATUISCIA | Le Journal Beauté',       extraCss: [] },
  'diagnostic.html': { title: 'Diagnostic IA — KATUISCIA',                  extraCss: [] },
  'formation.html':  { title: 'Formation — Maîtrisez l\'Art du Soin',       extraCss: ['about.css','landing.css','booking.css'] },
  'grossiste.html':  { title: 'Grossiste — Devenez Partenaire',             extraCss: ['about.css','landing.css','booking.css'] },
  'avantage-en-ligne.html':          { title: 'Avantage en Ligne — KATUISCIA',          extraCss: ['about.css','club.css'] },
  'valeurs-beaute-responsable.html':  { title: 'Beauté Responsable — KATUISCIA',        extraCss: ['about.css'] },
  'valeurs-emballage-durable.html':   { title: 'Emballage Durable — KATUISCIA',         extraCss: ['about.css'] },
  'valeurs-personne-biodiversite.html':{ title: 'Personne & Biodiversité — KATUISCIA',  extraCss: ['about.css'] },
  'mentions-legales.html':           { title: 'Mentions Légales — KATUISCIA',           extraCss: [] },
  'politique-confidentialite.html':  { title: 'Politique de Confidentialité — KATUISCIA',extraCss: [] },
  'politique-expedition.html':       { title: 'Politique d\'Expédition — KATUISCIA',    extraCss: [] },
  'politique-remboursement.html':    { title: 'Politique de Remboursement — KATUISCIA', extraCss: [] },
};

function extractBodyContent(html) {
  // Extraire le contenu entre <body> et </body>  
  let match = html.match(/<body[^>]*>([\s\S]*)<\/body>/i);
  if (!match) return html;
  let body = match[1];

  // Enlever {{> header }} et {{> footer }} (layout les a déjà)
  body = body.replace(/\{\{> header \}\}/g, '');
  body = body.replace(/\{\{> footer \}\}/g, '');

  // Nettoyer les espaces au début/fin
  body = body.trim();

  return body;
}

function extractScripts(html) {
  const scripts = [];
  // Extraire les balises <script> qui ne sont pas des modules importants
  let match;
  const regex = /<script(?![^>]*src="\{\{)[^>]*>([\s\S]*?)<\/script>/gi;
  while ((match = regex.exec(html)) !== null) {
    const scriptContent = match[0];
    // Ne pas inclure les scripts déjà gérés (auth, guard, etc.)
    if (!scriptContent.includes('auth.js') && 
        !scriptContent.includes('auth-guard') && 
        !scriptContent.includes('admin-guard') &&
        !scriptContent.includes('admin-crud') &&
        !scriptContent.includes('account.js')) {
      scripts.push(scriptContent);
    }
  }
  return scripts;
}

function convertBody(body) {
  // Remplacer les chemins d'assets
  body = body.replace(/src="(?!http|https|\{\{|data\:)\/?([^"]+)"/g, (match, p1) => {
    // Si c'est déjà un asset() ou url(), ne pas toucher
    if (p1.startsWith('{{')) return match;
    return `src="{{ asset('${p1}') }}"`;
  });
  
  // Remplacer les href internes (pas http, pas #, pas {{)
  body = body.replace(/href="(?!http|https|#|mailto|tel|\{\{)([^"]+)"/g, (match, p1) => {
    if (p1.startsWith('{{')) return match;
    // Ne pas convertir les liens vers .css, .js, images
    if (p1.match(/\.(css|js|png|jpg|jpeg|svg|ico|webp|gif|woff|woff2|pdf)$/i)) {
      return `href="{{ asset('${p1}') }}"`;
    }
    // Enlever .html
    const clean = p1.replace(/\.html$/, '');
    return `href="{{ url('${clean}') }}"`;
  });

  return body;
}

function convertToBlade(html, pageName, title, extraCss) {
  const bodyContent = extractBodyContent(html);
  const convertedBody = convertBody(bodyContent);
  
  const cssLinks = extraCss.map(c => 
    `<link rel="stylesheet" href="{{ asset('css/${c}') }}">`
  ).join('\n');

  // Extraire les scripts spécifiques à la page
  const pageScripts = extractScripts(html);
  const scriptsBlock = pageScripts.length > 0 ? 
    '\n' + pageScripts.map(s => s.replace(/src="\/js\/([^"]+)"/g, 'src="{{ asset(\'js/$1\') }}"')).join('\n') : '';

  return `@extends('layouts.public')

@section('title', '${title}')

@section('head')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
${cssLinks}
@endsection

@section('content')
${convertedBody}
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>${scriptsBlock}
@endsection
`;
}

let converted = 0;
Object.entries(pages).forEach(([file, config]) => {
  const htmlPath = path.join(rootDir, file);
  if (!fs.existsSync(htmlPath)) {
    console.log('❌ ' + file + ' — fichier introuvable');
    return;
  }

  const html = fs.readFileSync(htmlPath, 'utf-8');
  const blade = convertToBlade(html, file, config.title, config.extraCss);
  
  const bladeName = file.replace('.html', '.blade.php');
  const bladePath = path.join(rootDir, 'resources', 'views', 'pages', bladeName);
  
  fs.writeFileSync(bladePath, blade, 'utf-8');
  console.log('✅ ' + bladeName);
  converted++;
});

console.log('\n📊 ' + converted + ' fichiers convertis.');
