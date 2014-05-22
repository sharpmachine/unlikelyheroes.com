'use strict';
module.exports = function(grunt) {

    grunt.initConfig({

        // let us know if our JS is sound
        jshint: {
            options: {
                "bitwise": true,
                "browser": true,
                "curly": true,
                "eqeqeq": true,
                "eqnull": true,
                "es5": true,
                "esnext": true,
                "immed": true,
                "jquery": true,
                "latedef": true,
                "newcap": true,
                "noarg": true,
                "node": true,
                "strict": false,
                "trailing": true,
                "undef": true,
                "globals": {
                    "jQuery": true,
                    "alert": true
                }
            },
            all: [
            'Gruntfile.js',
            'js/*.js'
            ]
        },

        // concatenation and minification all in one
        uglify: {
            dist: {
                files: {
                    'js/scripts.min.js': [
                    'js/scripts.js'
                    ],
                    'js/plugins.min.js': [
                    'js/transition.js',
                    'js/alert.js',
                    'js/button.js',
                    // 'js/carousel.js',
                    'js/collapse.js',
                    'js/dropdown.js',
                    // 'js/modal.js',
                    // 'js/tooltip.js',
                    // 'js/popover.js',
                    // 'js/scrollspy.js',
                    // 'js/tab.js',
                    // 'js/affix.js',
                    'js/color.js',
                    'js/retina.js',
                    'js/sticky.js',
                    'js/uservoice.js'
                    ]
                }
            }
        },

        less: {
            development: {
                options: {
                    paths: ["less"],
                    yuicompress: true,
                    cleancss: true
                },
                files: {
                    "css/bootstrap.css": "less/bootstrap.less"
                }
            }
        },

        // watch our project for changes
        watch: {
            grunt: {
                files: ['Gruntfile.js']
            },
            less: {
                files: ["less/*"],
                tasks: ["less"],
            },
            css: {
                files: ['css/bootstrap.css']
            },
            js: {
                files: [
                'js/scripts.js'
                ],
                tasks: ['uglify']
            },
            php: {
                files: ['*.php']
            },
            livereload: {
                files: [
                'css/*.css',
                'js/scripts.min.js',
                'js/plugins.min.js',
                '*.php',
                ],
                options: {
                    livereload: true
                }
            }
        }
    });

    // load tasks
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-notify');

    // register task
    grunt.registerTask('default', [
        // 'jshint',
        'uglify',
        'watch'
        ]);

};