import path from "path";

System.config({
    "paths": {
        '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        '@assets': path.resolve(__dirname, 'resources/assets'),
        '@images': path.resolve(__dirname, 'resources/assets/images'),
        '@components': path.resolve(__dirname, 'resources/js/components'),
        '@mixins': path.resolve(__dirname, 'resources/js/mixins'),
    }
});
