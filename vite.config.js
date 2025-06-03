import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/scss/app.scss', 'resources/ts/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    aliases: {
        '@': '/resources/ts',
        'ziggy-js': path.resolve(__dirname, 'vendor/tightenco/ziggy'),
    },
    server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
    },
});
