import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar } from '@quasar/vite-plugin'
import fs from 'fs';
import path from 'path';

export default defineConfig({
    // uncomment if 
    server: {
        host: '0.0.0.0', // This makes the Vite dev server accessible on all network interfaces
        port: 5173, // The port Vite runs on
        hmr: {
        // sa wifi ip address
          host: '192.168.1.23' // Use your laptop’s local IP here
        // piso wifi nga ip address
        //   host: '10.0.20.1' // Use your laptop’s local IP here
        }
    }, 
    
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
