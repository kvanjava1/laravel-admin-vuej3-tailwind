import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/cms/app.css', 
                'resources/js/cms/app.ts'
      ],
            refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
        tailwindcss(),
  ],
  resolve: {
    alias: {
            '@': '/resources/js', // Match tsconfig paths
    },
    extensions: ['.js', '.ts', '.vue', '.json'], // File extensions to resolve
  },
  server: {
    host: 'tailwind-ts-dashboard-v1.test', // Your local dev domain
    hmr: {
      host: 'tailwind-ts-dashboard-v1.test', // Hot Module Reload (HMR)
      protocol: 'ws', // Force WebSocket for better HMR
    },
  },
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          // Split vendor chunks for better caching
          vue: ['vue', 'vue-router', '@vueuse/core'],
          pinia: ['pinia'],
          axios: ['axios'],
        },
      },
    },
    chunkSizeWarningLimit: 1000, // Silence large chunk warnings
  },
});