import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar } from '@quasar/vite-plugin'
import fs from 'fs';
import path from 'path';

export default defineConfig({
    // uncomment if 
    // server: {
    //     host: '0.0.0.0', // Makes the Vite dev server accessible on all network interfaces
    //     port: 5173, // Port for Vite
    //     https: {
    //         key: fs.readFileSync(path.resolve(__dirname, 'vite.key')),
    //         cert: fs.readFileSync(path.resolve(__dirname, 'vite.crt')),
    //     },
    //     hmr: {
    //         host: '10.0.20.2', // Use Ngrok domain
    //         protocol: 'wss', // WebSocket over secure connection
    //     },
    // },
    // uncomment if
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
        quasar({
            sassVariables: 'resources/css/quasar-variables.sass'
        })
    ],
});
