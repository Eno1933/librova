import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        // Ganti sesuai domain Laragon kamu
        host: 'librova.test',
        port: 5173,
        hmr: {
            host: 'librova.test',
            protocol: 'ws',
        },
    },
});