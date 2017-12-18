module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			options: {
				stripBanners: true
			},
			greater_media_content_syndication: {
				src: [
					'assets/js/src/syndication.js',
					'assets/js/src/syndication-post.js'
				],
				dest: 'assets/js/syndication.js'
			},
		},
		jshint: {
			all: [
				'assets/js/src/**/*.js'
			],
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
				browser: true,
				globals: {
					exports: true,
					module: false
				}
			}
		},
		uglify: {
			all: {
				files: {
					'assets/js/syndication.min.js': ['assets/js/syndication.js']
				},
				options: {
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		sass: {
			all: {
				files: {
					'assets/css/syndication.css': 'assets/css/sass/syndication.scss'
				}
			}
		},
		cssmin: {
			minify: {
				expand: true,
				cwd: 'assets/css/',
				src: ['syndication.css'],
				dest: 'assets/css/',
				ext: '.min.css'
			}
		},
		watch: {
			sass: {
				files: ['assets/css/sass/*.scss'],
				tasks: ['sass', 'cssmin'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/js/src/**/*.js', 'assets/js/vendor/**/*.js'],
				tasks: ['jshint', 'concat', 'uglify'],
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
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Default task.
	grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'sass', 'cssmin']);

	grunt.util.linefeed = '\n';
};
