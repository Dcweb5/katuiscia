const fs = require('fs');
const path = require('path');

const rootDir = path.resolve(__dirname, '..');

// Fichiers à convertir et leurs titres
const pages = {
  'maison.html': 'Maison — KATUISCIA | Histoire & Origine',
  'blog.html': 'Blog — KATUISCIA | Le Journal Beauté',
  'diagnostic.html': 'Diagnostic IA — KATUISCIA',
  'formation.html': 'Formation — Maîtrisez l\'Art du Soin',
  'grossiste.html': 'Grossiste — Devenez Partenaire',
  'avantage-en-ligne.html': 'Avantage en Ligne — KATUISCIA',
  'valeurs-beaute-responsable.html': 'Beauté Responsable — KATUISCIA',
  'valeurs-emballage-durable.html': 'Emballage Durable — KATUISCIA',
  'valeurs-personne-biodiversite.html': 'Personne & Biodiversité — KATUISCIA',
  'mentions-legales.html': 'Mentions Légales — KATUISCIA',
  'politique-confidentialite.html': 'Politique de Confidentialité — KATUISCIA',
  'politique-expedition.html': 'Politique d\'Expédition — KATUISCIA',
  'politique-remboursement.html': 'Politique de Remboursement — KATUISCIA',
};

function convertToBlade(html, pageName, title) {
  let content = html;

  // Enlever les balises DOCTYPE, html, head et body
  content = content.replace(/<!DOCTYPE html>[\s\S]*?<head>/i, '');
  content = content.replace(/<\/head>[\s\S]*?<body[^>]*>/i, '');
  content = content.replace(/<\/body>[\s\S]*?<\/html>/i, '');
  content = content.replace(/<\/html>[\s\S]*$/i, '');

  // Enlever les liens CSS du head (déjà dans le layout)
  content = content.replace(/<link rel="stylesheet"[^>]*?>/g, '');
  content = content.replace(/<meta[^>]*?>/g, '');
  content = content.replace(/<title>[^<]*<\/title>/g, '');
  content = content.replace(/<link rel="icon"[^>]*?>/g, '');

  // Remplacer les partiels Handlebars
  content = content.replace(/\{\{> header \}\}/g, "@include('components.header')");
  content = content.replace(/\{\{> footer \}\}/g, "@include('components.footer')");

  // Remplacer les liens et assets
  content = content.replace(/href="(?!http|https|#|mailto|tel)([^"]+)\.html"/g, "href=\"{{ url('$1') }}\"");
  content = content.replace(/href="(?!http|https|#|mailto|tel|\{\{|asset)(?![^"]*\.(css|js|png|jpg|jpeg|svg|ico|webp|gif|woff|woff2)")([^"]+)"/g, "href=\"{{ url('$3') }}\"");
  content = content.replace(/src="(?!(http|https|data)\:)([^"]+)"/g, "src=\"{{ asset('$2') }}\"");
  content = content.replace(/src="\/([^"]+)"/g, "src=\"{{ asset('$1') }}\"");

  // Corriger les doubles asset() ou style= inline
  content = content.replace(/<script[^>]*src="\{\{ asset\('js\/([^']+)'\) \}\}"[^>]*><\/script>/g, '@section(\'scripts\')\n<script type="module" src="{{ asset(\'js/\/\1\') }}"><\/script>\n@endsection');
  content = content.replace(/<script type="module" src="\{\{ asset\('js\/main\.js'\) \}\}"><\/script>/g,
    '@section(\'scripts\')\n<script type="module" src="{{ asset(\'js/main.js\') }}"><\/script>\n@endsection');

  // Extraire les scripts restants (hors main.js)
  // Trouver la fin du contenu avant les scripts
  let scriptsSection = '';
  const scriptMatches = content.match(/<script[^>]*>[\s\S]*?<\/script>/g);
  if (scriptMatches) {
    scriptMatches.forEach(s => {
      // Ne pas inclure les scripts déjà convertis
      if (!s.includes('@section') && !s.includes('main.js')) {
        scriptsSection += s + '\n';
      }
      content = content.replace(s, '');
    });
  }

  // Nettoyer
  content = content.replace(/^\s*\{\{> header \}\}/m, '');
  content = content.replace(/\{\{> footer \}\}/g, '');

  // Créer le template Blade
  return `@extends('layouts.public')

@section('title', '${title}')

@section('head')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
@endsection

@section('content')
${content.trim()}
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
${scriptsSection}
@endsection
`;
}

let converted = 0;
Object.entries(pages).forEach(([file, title]) => {
  const htmlPath = path.join(rootDir, file);
  if (!fs.existsSync(htmlPath)) {
    console.log('❌ ' + file + ' - not found');
    return;
  }

  const html = fs.readFileSync(htmlPath, 'utf-8');
  const blade = convertToBlade(html, file.replace('.html', ''), title);

  const bladeName = file.replace('.html', '.blade.php');
  const bladePath = path.join(rootDir, 'resources', 'views', 'pages', bladeName);
  
  fs.writeFileSync(bladePath, blade, 'utf-8');
  console.log('✅ ' + file + ' → ' + bladeName);
  converted++;
});

console.log('\n✅ Converted ' + converted + ' files.');
