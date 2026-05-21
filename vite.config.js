import { defineConfig } from 'vite';
import handlebars from 'vite-plugin-handlebars';
import { resolve } from 'path';
import fs from 'fs';
import url from 'url';

// Plugin Vite : sert les fichiers .html sans l'extension
function cleanUrls() {
  return {
    name: 'clean-urls',
    configureServer(server) {
      server.middlewares.use((req, res, next) => {
        let pathname = req.url.split('?')[0];

        // Ignorer les fichiers statiques (avec extension) et les requêtes API
        if (pathname.includes('.') || pathname.startsWith('/api/') || pathname === '/') {
          return next();
        }

        // Nettoyer le path : enlever le trailing slash
        const cleanPath = pathname.replace(/\/$/, '');
        const htmlPath = cleanPath + '.html';
        const fullPath = resolve(__dirname, '.' + htmlPath);

        if (fs.existsSync(fullPath)) {
          req.url = htmlPath;
        }
        next();
      });
    },
    configurePreviewServer(server) {
      server.middlewares.use((req, res, next) => {
        let pathname = req.url.split('?')[0];
        if (pathname.includes('.') || pathname.startsWith('/api/') || pathname === '/') {
          return next();
        }
        const cleanPath = pathname.replace(/\/$/, '');
        const htmlPath = cleanPath + '.html';
        const fullPath = resolve(__dirname, '.' + htmlPath);
        if (fs.existsSync(fullPath)) {
          req.url = htmlPath;
        }
        next();
      });
    },
  };
}

const pages = {};
const files = fs.readdirSync(__dirname);
files.forEach(file => {
  if (file.endsWith('.html')) {
    const name = file.replace('.html', '');
    pages[name] = resolve(__dirname, file);
  }
});

export default defineConfig({
  plugins: [
    cleanUrls(),
    handlebars({
      partialDirectory: resolve(__dirname, 'src/components'),
    }),
  ],
  build: {
    rollupOptions: {
      input: pages,
    },
  },
  server: {
    watch: {
      ignored: ['**/storage/framework/views/**'],
    },
  },
});
