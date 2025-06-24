import { defineConfig } from 'vite';
import react from 'vite-plugin-react';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
});
