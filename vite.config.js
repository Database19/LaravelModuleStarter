import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Membuat server Vite bisa diakses dari luar container
        hmr: {
            host: 'localhost', // Memberitahu browser untuk konek ke 'localhost'
        },
    },
});
