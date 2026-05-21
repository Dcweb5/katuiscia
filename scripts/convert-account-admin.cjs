const fs = require('fs');
const path = require('path');
const glob = require('glob');

const rootDir = path.resolve(__dirname, '..');

// Définir quel layout pour chaque type de page
const layoutMap = {
  'compte-': 'account',
  'admin-': 'admin',
  'admin': 'admin',
};

function getLayout(fileName) {
  if (fileName.startsWith('compte-')) return 'account';
  if (fileName.startsWith('admin') || fileName === 'admin') return 'admin';
  return 'public';
}

function extractBodyContent(html) {
  let match = html.match(/<body[^>]*>([\s\S]*)<\/body>/i);
  if (!match) return html;
  let body = match[1];

  // Enlever les partiels Handlebars (le layout les a déjà)
  body = body.replace(/\{\{> [a-z-]+ \}\}/g, '');

  // Enlever les scripts auth/guard (ils ne sont plus utiles)
  body = body.replace(/<script[^>]*src="[^"]*auth[^"]*"[^>]*><\/script>/gi, '');
  body = body.replace(/<script[^>]*src="[^"]*guard[^"]*"[^>]*><\/script>/gi, '');

  return body.trim();
}

function convertAssets(body) {
  // Chemins d'images et autres assets
  body = body.replace(/src="(?!http|https|\{\{|data\:)\/?([^"]+)"/g, (match, p1) => {
    if (p1.startsWith('{{')) return match;
    return `src="{{ asset('${p1}') }}"`;
  });
  
  // Liens internes (href)
  body = body.replace(/href="(?!http|https|#|mailto|tel|\{\{)([^"]+)"/g, (match, p1) => {
    if (p1.startsWith('{{')) return match;
    // Assets avec extension
    if (p1.match(/\.(css|js|png|jpg|jpeg|svg|ico|webp|gif|woff|woff2|pdf)$/i)) {
      return `href="{{ asset('${p1}') }}"`;
    }
    const clean = p1.replace(/\.html$/, '');
    return `href="{{ url('${clean}') }}"`;
  });

  return body;
}

function extractPageScripts(html) {
  const scripts = [];
  const regex = /<script(?![^>]*src="(?:[^"]*auth|guard|crud)[^"]*")[^>]*>([\s\S]*?)<\/script>/gi;
  let match;
  while ((match = regex.exec(html)) !== null) {
    const content = match[0];
    if (!content.includes('main.js') && !content.includes('auth.') && !content.includes('guard')) {
      scripts.push(content);
    }
  }
  return scripts.join('\n');
}

function convertToBlade(html, fileName, title) {
  const layout = getLayout(fileName);
  let bodyContent = extractBodyContent(html);
  bodyContent = convertAssets(bodyContent);
  
  // Enlever les balises <script> du body
  bodyContent = bodyContent.replace(/<script[\s\S]*?<\/script>/g, '');
  
  // Extraire les scripts
  const pageScripts = extractPageScripts(html);

  return `@extends('layouts.${layout}')

@section('title', '${title}')

@section('content')
${bodyContent}
@endsection

@section('scripts')
${pageScripts}
@endsection
`;
}

// Lister et convertir les pages compte et admin
const htmlFiles = glob.sync('*.html', { cwd: rootDir }).filter(f => 
  f.startsWith('compte-') || f.startsWith('admin')
);

const titles = {
  'compte-commandes': 'Mes Commandes',
  'compte-avis': 'Mes Avis',
  'compte-recompenses': 'Mes Récompenses',
  'compte-retours': 'Retours & Échanges',
  'admin': 'Administration',
  'admin-produits': 'Gestion des Produits',
  'admin-categories': 'Gestion des Catégories',
  'admin-commandes': 'Gestion des Commandes',
  'admin-contacts': 'Messages',
  'admin-rendezvous': 'Rendez-vous',
  'admin-utilisateurs': 'Gestion des Utilisateurs',
  'admin-coupons': 'Coupons & Promotions',
  'admin-funnels': 'Marketing & Funnels',
  'admin-finances': 'Rapports Financiers',
  'admin-analytics': 'Analytics',
  'admin-collections': 'Gestion des Collections',
  'admin-sections': 'Gestion des Sections',
  'admin-blog': 'Gestion du Blog',
  'admin-recompenses': 'Gestion des Récompenses',
};

let converted = 0;
htmlFiles.forEach(htmlFile => {
  const htmlPath = path.join(rootDir, htmlFile);
  const baseName = htmlFile.replace('.html', '');
  
  // Déterminer le dossier de sortie
  let viewDir = 'pages';
  if (baseName.startsWith('admin')) viewDir = 'admin';
  if (baseName.startsWith('compte')) viewDir = 'account';
  
  // Nom du fichier Blade
  let bladeName = baseName;
  if (baseName.startsWith('admin-')) {
    const subPath = baseName.replace('admin-', '');
    // Mapper vers les bons dossiers
    const dirMap = {
      'commandes': 'orders',
      'contacts': 'contacts',
      'rendezvous': 'appointments',
      'utilisateurs': 'users',
      'coupons': 'coupons',
      'funnels': 'funnels',
      'finances': 'finances',
      'analytics': 'analytics',
      'collections': 'collections',
      'sections': 'sections',
      'blog': 'blog',
      'recompenses': 'rewards',
      'produits': 'products',
      'categories': 'categories',
    };
    if (subPath === 'commandes') bladeName = 'orders/index';
    else if (dirMap[subPath]) bladeName = dirMap[subPath] + '/index';
    else bladeName = subPath;
  }
  if (baseName === 'admin') bladeName = 'dashboard';
  if (baseName.startsWith('compte-')) bladeName = baseName.replace('compte-', '');
  
  // Chemin complet Blade
  let bladeDir = viewDir;
  if (bladeName.includes('/')) {
    // bladeName already has path (e.g., orders/index)
    const parts = bladeName.split('/');
    bladeName = parts.pop();
    bladeDir = viewDir + '/' + parts.join('/');
    // Create dir if needed
    const dirPath = path.join(rootDir, 'resources', 'views', bladeDir);
    if (!fs.existsSync(dirPath)) fs.mkdirSync(dirPath, { recursive: true });
  }
  
  const bladePath = path.join(rootDir, 'resources', 'views', bladeDir, bladeName + '.blade.php');

  const html = fs.readFileSync(htmlPath, 'utf-8');
  const title = titles[baseName] || baseName;
  const blade = convertToBlade(html, htmlFile, title);
  
  // Ne pas écraser les pages déjà faites manuellement
  const manualPages = ['admin/dashboard', 'admin/products/index', 'admin/products/create', 'admin/products/edit', 'admin/categories/index', 'admin/categories/create', 'admin/categories/edit', 'account/dashboard'];
  const relativePath = bladeDir + '/' + bladeName;
  if (manualPages.some(f => relativePath.startsWith(f))) {
    console.log('⏭️ ' + relativePath + ' — page manuelle conservée');
    return;
  }
  
  fs.writeFileSync(bladePath, blade, 'utf-8');
  console.log('✅ ' + bladeDir + '/' + bladeName + '.blade.php');
  converted++;
});

console.log('\n📊 ' + converted + ' pages converties.');
