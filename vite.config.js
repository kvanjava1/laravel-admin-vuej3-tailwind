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
        extensions: ['.js', '.ts', '.d.ts']
    },
    server: {
        host: 'tailwind-ts-dashboard-v1.test',
        hmr: {
            host: 'tailwind-ts-dashboard-v1.test',
        },
    },
});