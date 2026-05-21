/* ============================================
   KATUISCIA — Script de nettoyage des liens .html
   Remplace tous les href="page.html" par href="page"
   ============================================ */

const fs = require('fs');
const path = require('path');
const glob = require('glob');

const rootDir = path.resolve(__dirname, '..');

// Fichiers à traiter
const patterns = [
  '*.html',                    // Racine
  'src/components/*.html',     // Partials
];

let totalModified = 0;
let totalFiles = 0;

patterns.forEach(pattern => {
  const files = glob.sync(pattern, { cwd: rootDir });

  files.forEach(file => {
    const filePath = path.join(rootDir, file);
    let content = fs.readFileSync(filePath, 'utf-8');
    let modified = false;

    // Remplacer href="page.html" par href="/page"
    const linkRegex = /href="([^"]+\.html)"/g;
    let newContent = content;

    let match;
    while ((match = linkRegex.exec(content)) !== null) {
      const fullMatch = match[0];
      const linkUrl = match[1];

      // Ignorer les liens externes, ancres, assets
      if (linkUrl.startsWith('http://') || linkUrl.startsWith('https://') || linkUrl.startsWith('#')) {
        continue;
      }
      if (linkUrl.match(/\.(css|js|png|jpg|jpeg|svg|ico|webp|gif|json|xml|php|woff|woff2|ttf|eot)$/i)) {
        continue;
      }

      const cleanUrl = linkUrl.replace('.html', '');
      // Préserver le comportement : si l'URL était relative sans slash, on garde sans slash
      const cleanHref = cleanUrl.startsWith('/') ? `href="${cleanUrl}"` : `href="${cleanUrl}"`;
      newContent = newContent.split(fullMatch).join(cleanHref);
      modified = true;
    }

    // Remplacer action="page.html" -> action="/page"
    const actionRegex = /action="([^"]+\.html)"/g;
    while ((match = actionRegex.exec(newContent)) !== null) {
      const fullMatch = match[0];
      const actionUrl = match[1];
      if (actionUrl.startsWith('http://') || actionUrl.startsWith('https://') || actionUrl.startsWith('#')) continue;
      const cleanAction = actionUrl.replace('.html', '');
      newContent = newContent.split(fullMatch).join(`action="${cleanAction}"`);
      modified = true;
    }

    if (modified) {
      fs.writeFileSync(filePath, newContent, 'utf-8');
      totalModified++;
      console.log('✅ ' + file + ' — liens .html nettoyés');
    } else {
      console.log('➖ ' + file + ' — aucun changement');
    }
    totalFiles++;
  });
});

console.log('\n📊 Terminé : ' + totalFiles + ' fichiers analysés, ' + totalModified + ' modifiés.');
