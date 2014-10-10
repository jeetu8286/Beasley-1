module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			dist: {
				src: [
					'templates.js',
					'src/utils.js',
					'src/constraint.js',
					'src/constraint_store.js',
					'src/member_query_updater.js',
					'src/menu_view.js',
					'src/constraint_list_view.js',
					'src/preview_view.js',
					'src/app.js',
				],
				dest: 'query_builder.js'
			}
		},

		watch: {
			scripts: {
				files: ['src/**/*.js', 'src/templates/*.html'],
				tasks: ['build']
			}
		},

		jst: {
			compile: {
				files: {
					"templates.js": ["src/templates/*.html"]
				}
			}
		},

		uglify: {
			dist: {
				files: {
					'query_builder.min.js': ['query_builder.js']
				}
			}
		},

		clean: {
			src: ["templates.js"]
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-jst');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('build_templates', ['clean', 'jst']);
	grunt.registerTask('build', ['build_templates', 'concat']);
	grunt.registerTask('dist', ['build', 'uglify']);
	grunt.registerTask('default', ['build']);
};
