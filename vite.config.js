import { defineConfig, loadEnv } from 'vite'
import symfonyPlugin from "vite-plugin-symfony";
import react from '@vitejs/plugin-react';


export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd(), '')
    return {
        server: {
            host: '127.0.0.1',
            port: 5173
        },
        plugins: [
            symfonyPlugin(),
            react()
        ],
        build: {
            cssMinify: true,
            rollupOptions: {
                input: {
                    app: "./frontend/index.tsx"
                },
            }
        }
    }
})
