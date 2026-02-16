import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    logLevel: 'error',
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
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
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler', // Usa o compilador mais rápido e moderno
                quietDeps: true,        // <--- O SEGREDO: Silencia avisos de dependências (node_modules),
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls'],
            },
        },
    },
});
