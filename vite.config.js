import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js', 'resources/css/theme.css',
                'resources/css/login.css', 
                'resources/css/admin/theme.css',
                'resources/js/login.js', 'resources/js/logout.js', 'resources/js/register.js',
                'resources/js/payment.js',
                'resources/js/memory/gallery.js',
                'resources/js/memory/partners.js',
                'resources/js/firebase/firebase-token.js', 'resources/js/firebase/firebase-listener.js',
                'resources/js/firebase/firebase-init.js', 'resources/js/firebase/firebase-auth.js',
                'resources/js/admin/app.js', 'resources/js/admin/memorials.js',
                'resources/js/admin/info.js','resources/css/admin/info.css',
                'resources/js/admin/timeline.js', 'resources/js/admin/life.js', 'resources/js/admin/gallery.js',
                'resources/js/admin/tributes.js',
                'resources/js/admin/payments.js',
                'resources/js/admin/invitations.js',
                'resources/js/memory/memorial.js', 'resources/css/memorial.css', 'resources/css/admin/steps.css',
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
