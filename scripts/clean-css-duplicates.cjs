const fs = require('fs');
const path = require('path');
const glob = require('glob');

const rootDir = path.resolve(__dirname, '..');
const files = glob.sync('resources/views/pages/*.blade.php', { cwd: rootDir });

let cleaned = 0;
files.forEach(file => {
  const filePath = path.join(rootDir, file);
  let content = fs.readFileSync(filePath, 'utf-8');
  let modified = false;

  // Remove duplicate global.css and components.css from @section('head')
  // Since layout already has them
  const old = `<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/components.css') }}">`;

  if (content.includes(old)) {
    content = content.replace(old + '\n', '');
    modified = true;
  }

  if (modified) {
    fs.writeFileSync(filePath, content, 'utf-8');
    console.log('✅ ' + path.basename(file));
    cleaned++;
  }
});

console.log('\n📊 ' + cleaned + ' fichiers nettoyés.');
