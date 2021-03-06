module.exports = function(grunt) {
    // Chargement automatique de tous nos modules
    require('load-grunt-tasks')(grunt);

    // Configuration des plugins
    grunt.initConfig({
        // Concat and compress CSS
        cssmin: {
            combine: {
                options:{
                    report: 'gzip',
                    keepSpecialComments: 0
                },
                files: {
                    'web/built/min.css': [
                        'web/vendor/foundation/css/normalize.css',
                        'web/vendor/foundation/css/foundation.css',
                        'web/vendor/mapbox.js/mapbox.css',
                        'src/Bonnes/*/Resources/public/css/style.css'
                    ]
                }
            }
        },

        // Minify JS
        uglify: {
            options: {
                mangle: false,
                sourceMap: true,
                sourceMapName: 'web/built/app.map'
            },
            dist: {
                files: {
                    'web/built/app.min.js':[
                        'web/vendor/modernizr/modernizr.js',
                        'web/vendor/mapbox.js/mapbox.js',
                        'web/bundles/fosjsrouting/js/router.js'
                    ]
                }
            }
        },

        // Allow the grunt watch command
        watch: {
            css: {
                files: ['src/Bonnes/*/Resources/public/css/*.css'],
                tasks: ['css']
            },
            javascript: {
                files: ['src/Bonnes/*/Resources/public/js/*.js'],
                tasks: ['javascript']
            }
        },

        copy: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'web/vendor/mapbox.js/images',
                    dest: 'web/built/images',
                    src: ['**']
                }]
            }
        }
    });

    grunt.registerTask('default', ['css', 'javascript']);
    grunt.registerTask('css', ['cssmin']);
    grunt.registerTask('javascript', ['uglify']);
    grunt.registerTask('cp', ['copy']);
};
