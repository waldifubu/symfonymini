import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import react from '@vitejs/plugin-react-swc';

export default defineConfig({
    plugins: [
        /* react(), // if you're using React */
        symfonyPlugin(),
        react()
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./frontend/index.tsx"
            },
        }
    },
});
