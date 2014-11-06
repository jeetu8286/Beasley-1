module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			dist: {
				src: [
					'src/utils.js',
					'templates.js',

					'src/models/entry_type.js',
					'src/models/entry_field.js',
					'src/models/constraint.js',
					'src/models/profile_constraint.js',
					'src/models/entry_constraint.js',
					'src/models/available_constraints.js',
					'src/models/query_result.js',

					'src/collections/entry_type_collection.js',
					'src/collections/entry_field_collection.js',
					'src/collections/constraint_collection.js',
					'src/collections/query_result_collection.js',

					'src/views/toolbar_item_view.js',
					'src/views/toolbar_view.js',
					'src/views/constraint_view.js',
					'src/views/entry_constraint_view.js',
					'src/views/active_constraints_view.js',
					'src/views/preview_view.js',
					'src/views/query_result_item_view.js',
					'src/views/query_results_view.js',

					'src/app.js',
				],
				dest: 'query_builder.js'
			}
		},

		watch: {
			scripts: {
				files: ['src/**/*.js', 'src/templates/*.jst'],
				tasks: ['build']
			}
		},

		jst: {
			compile: {
				files: {
					"templates.js": ["src/templates/*.jst"]
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
