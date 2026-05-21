const fs = require('fs');
const path = require('path');
const glob = require('glob');

const rootDir = path.resolve(__dirname, '..');
const files = glob.sync('resources/views/pages/*.blade.php', { cwd: rootDir });

let fixed = 0;
files.forEach(file => {
  const filePath = path.join(rootDir, file);
  let content = fs.readFileSync(filePath, 'utf-8');
  let modified = false;

  // Supprimer la ligne <script type="module" src="{{ asset('js/main.js') }}"></script> du body
  // (elle est déjà dans @section('scripts'))
  if (content.includes('main.js')) {
    // Supprimer cette ligne spécifique du @section('content')
    content = content.replace(/\n\s*<script type="module" src="\{\{ asset\('js\/main\.js'\) \}\}"><\/script>\n/g, '\n');

    // Corriger le double main.js dans @section('scripts')
    content = content.replace(
      /@section\('scripts'\)\s*<script type="module" src="\{\{ asset\('js\/main\.js'\) \}\}"><\/script>\s*<script type="module" src="\{\{ asset\('js\/main\.js'\) \}\}"><\/script>/g,
      "@section('scripts')\n<script type=\"module\" src=\"{{ asset('js/main.js') }}\"></script>"
    );

    modified = true;
  }

  if (modified) {
    fs.writeFileSync(filePath, content, 'utf-8');
    const name = path.basename(file);
    console.log('✅ ' + name + ' — nettoyé');
    fixed++;
  } else {
    console.log('➖ ' + path.basename(file) + ' — déjà propre');
  }
});

console.log('\n📊 ' + fixed + ' fichiers corrigés.');
