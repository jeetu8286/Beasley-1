module.exports = function( grunt ) {

	// Project configuration
	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),
		hash: {
			options: {
				mapping: 'assets/js/dist/assets.php', // mapping file so your server can serve the right files
				srcBasePath: 'assets/js/', // the base Path you want to remove from the `key` string in the mapping file
//				destBasePath: 'out/', // the base Path you want to remove from the `value` string in the mapping file
				flatten: false, // Set to true if you don't want to keep folder structure in the `key` value in the mapping file
				hashLength: 8, // hash length, the max value depends on your hash function
				hashFunction: function(source, encoding) { // default is md5
					return require('crypto').createHash('sha1').update(source, encoding).digest('hex');
				}
			},
			js: {
				src: 'assets/js/*.js',  // all your js that needs a hash appended to it
				dest: 'assets/js/dist/' // where the new files will be created
			}
		},
		concat: {
			options: {
				stripBanners: true
			},
			greater_media_live_player: {
				src: [
					'assets/js/vendor/bowser.js',
					'assets/js/src/tdplayer.js'
				],
				dest: 'assets/js/live-player.js'
			}
		},
		jshint: {
			all: [
				'Gruntfile.js',
				'assets/js/src/**/*.js'
			],
			options: {
				curly:   true,
				eqeqeq:  false,
				immed:   true,
				latedef: false,
				newcap:  true,
				noarg:   true,
				sub:     true,
				undef:   true,
				boss:    false,
				eqnull:  true,
				devel:   true,
				expr:    true,
				browser: true,
				globals: {
					exports: true,
					module:  false,
					gmr: true,
					Cookies: true,
					gmlp: true,
					jQuery: true,
					'$': true,
					window: true,
					bowser: true,
					require: true,
					TDSdk: true,
					'_': false,
					Modernizr: true,
					TdPlayerApi: false
				}
			}
		},
		uglify: {
			all: {
				files: {
					'assets/js/live-player.min.js': ['assets/js/live-player.js'],
				},
				options: {
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		watch:  {
			scripts: {
				files: ['assets/js/admin/**/*.js', 'assets/js/src/**/*.js', 'assets/js/vendor/**/*.js'],
				tasks: ['default'],
				options: {
					debounceDelay: 500
				}
			}
		}
	} );

	// Load other tasks
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-hash');

	// Default task
	grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'hash']);

	grunt.util.linefeed = '\n';
};
