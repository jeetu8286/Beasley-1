module.exports = {
	settings: {
		react: {
			version: '16.5.2'
		}
	},
	parser: 'babel-eslint',
	env: {
		browser: true,
		node: true,
		es6: true
	},
	plugins: ['import', 'react', 'jsx-a11y'],
	extends: [
		'@10up/eslint-config',
		'plugin:import/errors',
		'plugin:import/warnings',
		'plugin:react/recommended',
		'plugin:jsx-a11y/recommended'
	],
	globals: {
		wp: true
	},
	env: {
		browser: true,
		node: true
	},
	rules: {
		'import/no-unresolved': 0,
		'no-useless-escape': [0],
		'no-unused-vars': [0],
		'no-console': [0],
		'no-unreachable': [0],
		'require-jsdoc': 0,
		indent: [
			'error',
			'tab',
			{
				SwitchCase: 1
			}
		],
		'jsx-a11y/label-has-for': [
			'error',
			{
				required: {
					every: ['id']
				}
			}
		],
		'jsx-a11y/media-has-caption': 0
	}
};
