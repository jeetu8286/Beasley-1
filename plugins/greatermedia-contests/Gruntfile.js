module.exports = function (grunt) {

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			options: {
				stripBanners: true,
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
						' * <%= pkg.homepage %>\n' +
						' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
						' * Licensed GPLv2+' +
						' */\n'
			},
			frontend: {
				src: ['js/src/greatermedia-contests.js'],
				dest: 'js/greatermedia-contests.js'
			},
			backend: {
				src: ['js/src/greatermedia-contests-admin.js'],
				dest: 'js/greatermedia-contests-admin.js'
			}
		},
		jshint: {
			all: ['js/src/**/*.js'],
			options: {
				curly: true,
				eqeqeq: true,
				immed: true,
				latedef: true,
				newcap: true,
				noarg: true,
				sub: true,
				undef: true,
				boss: true,
				eqnull: true,
				globals: {
					exports: true,
					module: false,
					jQuery: false,
					document: false,
					window: false,
					console: false,
					GreaterMediaContests: false,
					GreaterMediaContestsForm: false,
					Formbuilder: false,
					FormData: false
				}
			}
		},
		uglify: {
			all: {
				files: {
					'js/greatermedia-contests-admin.min.js': ['js/greatermedia-contests-admin.js'],
					'js/greatermedia-contests.min.js': ['js/greatermedia-contests.js']
				},
				options: {
					banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
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
		sass: {
			all: {
				options: {
					trace: true,
					sourcemap: 'auto',
					style: 'compressed'
				},
				files: {
					'css/greatermedia-contests.css': 'css/src/greatermedia-contests.scss',
					'css/greatermedia-contests-admin.css': 'css/src/greatermedia-contests-admin.scss'
				}
			}
		},
		watch: {
			sass: {
				files: ['css/src/*.scss'],
				tasks: ['css'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['js/src/**/*.js'],
				tasks: ['js'],
				options: {
					debounceDelay: 500
				}
			}
		}
	});

	// Load other tasks
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Custom tasks
	grunt.registerTask('js', ['jshint', 'concat', 'uglify']);
	grunt.registerTask('css', ['sass']);

	// Default task
	grunt.registerTask('default', ['js', 'css']);

	grunt.util.linefeed = '\n';
};
