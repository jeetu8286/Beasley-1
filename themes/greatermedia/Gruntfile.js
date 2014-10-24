module.exports = function( grunt ) {
	'use strict';

	// Load all grunt tasks
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	// Project configuration
	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),
		concat: {
			options: {
				stripBanners: true,
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
				' * <%= pkg.homepage %>\n' +
				' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
				' * Licensed GPLv2+' +
				' */\n'
			},
			greater_media: {
				src: [
					'assets/js/src/greater_media.js'
				],
				dest: 'assets/js/greater_media.js'
			},
			gigya_login: {
				src: [
					'assets/js/src/gigya_login.js'
				],
				dest: 'assets/js/gigya_login.js'
			},
			liveplayer_login: {
				src: [
					'assets/js/src/liveplayer_login.js'
				],
				dest: 'assets/js/liveplayer_login.js'
			},
			greater_media_styleguide: {
				src: [
					'assets/js/styleguide/gm_styleguide.js'
				],
				dest: 'assets/js/gm_styleguide.js'
			}
		},
		jshint: {
			browser: {
				all: [
					'assets/js/src/**/*.js',
					'assets/js/styleguide/**/*.js',
					'assets/js/test/**/*.js'
				],
				options: {
					jshintrc: '.jshintrc'
				}
			},
			grunt: {
				all: [
					'Gruntfile.js'
				],
				options: {
					jshintrc: '.gruntjshintrc'
				}
			}
		},
		uglify: {
			all: {
				files: {
					'assets/js/greater_media.min.js': ['assets/js/greater_media.js'],
					'assets/js/gigya_login.min.js': ['assets/js/gigya_login.js'],
					'assets/js/liveplayer_login.min.js': ['assets/js/liveplayer_login.js'],
					'assets/js/gm_styleguide.min.js': ['assets/js/gm_styleguide.js']
				},
				options: {
					banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
					' * <%= pkg.homepage %>\n' +
					' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					' * Licensed GPLv2+' +
					' */\n',
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		test:   {
			files: ['assets/js/test/**/*.js']
		},

		sass:   {
			options: {
				require: 'sass-globbing'
			},
			all: {
				files: {
					'assets/css/greater_media.css': 'assets/css/sass/greater_media.scss',
					'assets/css/gm_styleguide.css': 'assets/css/sass/gm_styleguide.scss'
				}
			}
		},

		cssmin: {
			options: {
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
				' * <%= pkg.homepage %>\n' +
				' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
				' * Licensed GPLv2+' +
				' */\n'
			},
			minify: {
				expand: true,

				cwd: 'assets/css/',
				src: ['greater_media.css','gm_styleguide.css'],

				dest: 'assets/css/',
				ext: '.min.css'
			}
		},
		watch:  {

			sass: {
				files: ['assets/css/sass/**/*.scss'],
				tasks: ['sass', 'cssmin'],
				options: {
					debounceDelay: 500
				}
			},

			scripts: {
				files: ['assets/js/src/**/*.js', 'assets/js/vendor/**/*.js', 'assets/js/styleguide/**/*.js'],
				tasks: ['jshint', 'concat', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		}
	} );

	// Default task.

	grunt.registerTask( 'default', ['jshint', 'concat', 'uglify', 'sass', 'cssmin'] );


	grunt.util.linefeed = '\n';
};