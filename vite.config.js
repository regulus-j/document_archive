import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
    // server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     // https: true,
    //     // // hmr: {
    //     // //     host: 'https://3dd1-2001-4455-69f-9e00-1d12-36c3-e1d6-bb15.ngrok-free.app',
    //     // // }
    // }
