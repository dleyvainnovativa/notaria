import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js', 'resources/css/theme.css',
                'resources/css/login.css', 
                'resources/css/admin/theme.css',
                'resources/css/admin/steps.css',
                'resources/js/login.js', 'resources/js/logout.js', 'resources/js/register.js',
                'resources/js/payment.js',
                'resources/js/firebase/firebase-token.js', 'resources/js/firebase/firebase-listener.js',
                'resources/js/firebase/firebase-init.js', 'resources/js/firebase/firebase-auth.js',
                'resources/js/admin/app.js',
                'resources/js/admin/payments.js',
                'resources/js/admin/navigate.js',
                'resources/js/admin/extract.js',
                'resources/js/admin/process/declaranot.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
