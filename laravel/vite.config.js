import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',      // admin
                'resources/sass/client-app.scss',   // client
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    // server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     strictPort: true,
    //     hmr: {
    //         // host: '192.168.68.103',
    //         // host: '137.92.201.86',
    //         host: 'localhost',
    //     },
    // },
});
