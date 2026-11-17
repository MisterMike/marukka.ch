import { fileURLToPath, URL } from 'node:url';
import { defineConfig, loadEnv } from 'vite';
import ViteRestart from 'vite-plugin-restart';
import tailwindcss from '@tailwindcss/vite';
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';

// Match ports in .ddev/config.yaml -> web_extra_exposed_ports
// Vite itself must keep listening on the container_port (3000) below —
// 3005/3006 are only the *external* ports DDEV's router maps to it.
const CONTAINER_PORT = 3000
const HTTPS_PORT = 3006

export default defineConfig(({ command, mode }) => {

    const env = loadEnv(mode, process.cwd(), '')

    return {

    base: command === 'serve' ? '' : '/dist/',
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        },
    },
    build: {
        manifest: true,
        outDir: './web/dist/',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                app: 'src/js/app.js',
            },
        },
    },
    plugins: [
        tailwindcss(),
        ViteImageOptimizer(),
        ViteRestart({
            reload: ['./templates/**/*.twig', './modules/**/templates/**/*.twig'],
        }),
    ],
    server: {
        fs: {
          strict: false
        },
        host: '0.0.0.0',
        origin: env.PRIMARY_SITE_URL + ':' + HTTPS_PORT,
        port: CONTAINER_PORT,
        strictPort: true,
        cors: true,
        headers: {
            "Access-Control-Allow-Origin": "*",
        },
        allowedHosts: true,
        hmr: {
            overlay: false,
        }
    },
    }
});
