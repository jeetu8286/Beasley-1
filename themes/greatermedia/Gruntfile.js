module.exports = function (grunt) {
	'use strict';

	// Load all grunt tasks
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			options: {
				stripBanners: true
			},
			scripts: {
				files: {
					'assets/js/frontend.js': [
						'assets/js/vendor/cycle2/jquery.cycle2.js',
						'assets/js/vendor/cycle2/jquery.cycle2.center.js',
						'assets/js/vendor/cycle2/jquery.cycle2.swipe.js',
						'assets/js/vendor/cycle2/jquery.cycle2.carousel.js',
						'assets/js/vendor/placeholders.min.js',
						'assets/js/vendor/jquery.fitvids.js',
						'assets/js/vendor/headroom.min.js',
						'assets/js/src/mobile-sub-menus.js',
						'assets/js/src/show-schedule.js',
						'assets/js/src/social_share.js',
						'assets/js/src/pjax.js',
						'assets/js/src/greater_media.js',
						'assets/js/src/load_more.js',
						'assets/js/src/menus.js',
						'assets/js/src/hero-slider.js',
						'assets/js/src/search.js',
						'assets/js/src/firebase.js'
					],
					'assets/js/admin.js': [
						'assets/js/src/admin.js'
					]
				}
			}
		},
		jshint: {
			all: [
				'assets/js/src/**/*.js',
				'!assets/js/src/vendor/**/*.js'
			],
			options: {
				curly: true,
				eqeqeq: false,
				immed: true,
				latedef: false,
				newcap: true,
				noarg: true,
				sub: true,
				undef: true,
				boss: true,
				eqnull: true,
				globals: {
					document: false,
					window: false,
					screen: false,
					console: false,
					location: false,
					setTimeout: false,
					setInterval: false,
					CustomEvent: false,
					jQuery: false,
					firebase: false,
					_: false,
					beasley: false,
					renderLogoUpload: false,
					resetLogoUpload: false
				}
			}
		},
		uglify: {
			all: {
				files: {
					'assets/js/frontend.min.js': ['assets/js/frontend.js'],
					'assets/js/admin.min.js': ['assets/js/admin.js']
				},
				options: {
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		sass: {
			options: {
				require: 'sass-globbing',
				sourceMap: true,
				precision: 5
			},
			all: {
				files: {
					'assets/css/greater_media.css': 'assets/css/sass/greater_media.scss',
					'assets/css/greater_media_admin.css': 'assets/css/sass/greater_media_admin.scss',
					'assets/css/gm_admin.css': 'assets/css/sass/gm_admin.scss',
					'assets/css/gm_tinymce.css': 'assets/css/sass/gm_tinymce.scss',
					'assets/css/ie9.css': 'assets/css/sass/ie9.scss'
				}
			}
		},
		cssmin: {
			minify: {
				expand: true,
				cwd: 'assets/css/',
				src: ['greater_media.css', 'greater_media_admin.css', 'gm_admin.css', 'gm_tinymce.css'],
				dest: 'assets/css/',
				ext: '.min.css'
			}
		},
		watch:  {

			livereload: {
				files  : ['assets/css/**/*.css'],
				options: {
					livereload: true
				}
			},

			sass: {
				files: ['assets/css/sass/**/*.scss'],
				tasks: ['css'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/js/src/**/*.js', 'assets/js/vendor/**/*.js'],
				tasks: ['js'],
				options: {
					debounceDelay: 500
				}
			}
		}
	});

	// Default tasks
	grunt.registerTask('css', ['sass', 'cssmin']);
	grunt.registerTask('js', ['jshint', 'concat', 'uglify']);
	grunt.registerTask('default', ['js', 'css']);

	grunt.util.linefeed = '\n';
};
