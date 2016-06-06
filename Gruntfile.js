'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({

        compass: {
            dist: {
                options: {
                    sassDir: 'assets/scss',
                    cssDir: 'assets/css',
                    sourcemap: true,
                    outputStyle: 'compact',
                    noLineComments: true
                }
            },
        },

        // watch for changes and trigger sass, jshint, uglify and livereload
        watch: {
            compass: {
                files: ['assets/scss/**/*.{scss,sass}'],
                tasks: ['compass', 'postcss', 'flipcss', 'cssmin']
            },
            scripts: {
               files: ['assets/js/source/**/*.js'],
               tasks: ['jshint', 'uglify'],
               options: {
                 spawn: false,
               },
             },
        },

        // autoprefixer
        postcss: {
            options: {
                map: true,
                processors: [
                    require('autoprefixer')({
                        browsers: ['last 2 versions', 'ie 9', 'ios 6', 'android 4']
                    })
                ]
            },
            files: {
                expand: true,
                flatten: true,
                src: 'assets/css/*.css',
                dest: 'assets/css/'
            },
        },

        // css minify
        cssmin: {
            options: {
                keepSpecialComments: 1
            },
            minify: {
                files: [{
                    expand: true,
                    cwd: 'assets/css/',
                    src: ['*.css', '!*.min.css'],
                    dest: 'assets/css/',
                    ext: '.min.css'
                }]
            }
        },

        flipcss: {
            options: {
              warnings: false,
              flipPseudo: false,
              flipUrls: true,
              flipSelectors: true
            },
            frontend: {
                'assets/css/style-rtl.css': 'assets/css/style.css',
            },
         },

        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                force: true
            },

            all: [
                'Gruntfile.js',
                'assets/js/source/*.js'
            ]
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            main: {
                options: {
                    sourceMap: true,
                },
                files: {
                    'assets/js/editor.min.js': 'assets/js/editor.js',
                    'assets/js/reply.min.js': 'assets/js/reply.js',
                    'assets/js/topic.min.js': 'assets/js/topic.js',
                    'assets/js/user.min.js': 'assets/js/user.js',
                }
            }
        },

        checktextdomain: {
            options: {
                correct_domain: false,
                text_domain: 'newsfront-bbpress',
                keywords: [
                    '__:1,2d',
                    '_e:1,2d',
                    '_x:1,2c,3d',
                    'esc_html__:1,2d',
                    'esc_html_e:1,2d',
                    'esc_html_x:1,2c,3d',
                    'esc_attr__:1,2d',
                    'esc_attr_e:1,2d',
                    'esc_attr_x:1,2c,3d',
                    '_ex:1,2c,3d',
                    '_n:1,2,3,4d',
                    '_nx:1,2,4c,5d',
                    '_n_noop:1,2,3d',
                    '_nx_noop:1,2,3c,4d',
                    ' __ngettext:1,2,3d',
                    '__ngettext_noop:1,2,3d',
                    '_c:1,2d',
                    '_nc:1,2,4c,5d'
                ]
            },
            files: {
                src: [
                    '**/*.php', // Include all files
                    '!node_modules/**', // Exclude node_modules/
                    '!tests/**', // Exclude unit tests/
                    '!bin/**', // Exclude Bin/
                    '!i18n/**', // Exclude i18n/
                    '!docker/**', // Exclude docker/
                    '!build/.*' // Exclude build/
                ],
                expand: true
            }
        },

        addtextdomain: {
            options: {
                textdomain: 'newsfront-bbpress', // Project text domain.
            },

            target: {
                files: {
                    src: ['*.php', '**/*.php', '!node_modules/**']
                }
            }
        },

        makepot: {
            target: {
                options: {
                    domainPath: 'languages',
                    mainFile: 'plugin.php',
                    potFilename: 'newsfront-bbpress.pot',
                    processPot: function(pot) {
                        pot.headers['report-msgid-bugs-to'] = 'frank@radiumthemes.com';
                        pot.headers['language-team'] = 'Radium Themes <http://radiumthemes.com>';
                        pot.headers['Last-Translator'] = 'Franklin Gitonga <frank@radiumthemes.com>';
                        return pot;
                    },
                    type: 'wp-plugin'
                }
            }
        },

        // Generates a ChangeLog file from Git commits
        changelog: {
            options: {
                // Task-specific options go here.
            }
        },

        devUpdate: {
          main: {
              options: {
                  updateType: 'prompt', //just report outdated packages
                  reportUpdated: false, //don't report up-to-date packages
                  semver: false, // update regardless of package.json
                  packages: {
                      devDependencies: true, //only check for devDependencies
                      dependencies: false
                  },
                  packageJson: null, //use matchdep default findup to locate package.json
                  reportOnlyPkgs: [] //use updateType action on all packages
              }
          }
      },

    });

    // register task
    grunt.registerTask('build', [ 'compass', 'postcss', 'flipcss', 'cssmin', 'uglify', 'watch']);
    grunt.registerTask('build-commit', ['checktextdomain', 'makepot', /* 'changelog'*/ ]);
    grunt.registerTask('update-packages', ['devUpdate']);

};
