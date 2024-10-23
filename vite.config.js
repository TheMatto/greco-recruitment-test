import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  server: {
    host: true,
    port: 5173,
    watch: {
      usePolling: true
    },
  },
  build: {
    assetsDir: '',
    manifest: true,
    outDir: 'app/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: 'app/resources/js/index.js'
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern'
      }
    }
  },
  plugins: [vue()],
});
