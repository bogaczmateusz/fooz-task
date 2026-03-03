import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    outDir: 'build/assets/js',
    emptyOutDir: false,
    rollupOptions: {
      input: path.resolve(__dirname, 'src/assets/js/scripts.js'),
      output: {
        entryFileNames: 'scripts.js',
        format: 'iife',
        inlineDynamicImports: true,
      },
    },
    sourcemap: true,
    minify: false,
  },
});
