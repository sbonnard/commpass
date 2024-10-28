import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        manifest: true,
        outDir: 'assets',
        rollupOptions: {
            input: '/js/script.js', // Chemin vers le fichier d'entrée
            output: {
                entryFileNames: 'script-CRlRqdEl.js', // Nom du fichier JavaScript
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'script-DSSvur9f.css'; // Nom du fichier CSS
                    }
                    return assetInfo.name; // Pour d'autres types de fichiers, utiliser leur nom d'origine
                },
            },
        },
    },
});