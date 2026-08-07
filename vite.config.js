import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/app.js',
                'resources/js/wishlist.jsx',
                'resources/js/compare.jsx',
                'resources/js/react/pages/ContactUs.jsx'
            ],
            refresh: true,
        }),
        react(),
    ],
});
