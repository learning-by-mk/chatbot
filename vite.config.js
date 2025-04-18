import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "resources/js/app.js",
            },
        },
    },
    // resolve: {
    //     alias: {
    //         "~katex": resolve(__dirname, "node_modules/katex"),
    //     },
    // },
});
