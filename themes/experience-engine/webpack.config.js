const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const coreConfig = {
	output: {
		path: path.resolve(__dirname, 'bundle'),
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				enforce: 'pre',
				exclude: /node_modules/,
				use: {
					loader: 'eslint-loader',
					options: {
						cache: true,
						failOnWarning: false,
						failOnError: true,
					},
				},
			},
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						cacheDirectory: true,
						presets: ['@babel/preset-react', '@babel/preset-env'],
					},
				},
			},
			{
				test: /\.css$/,
				use: [
					{ loader: MiniCssExtractPlugin.loader },
					{ loader: 'css-loader' },
					{ loader: 'postcss-loader' },
				],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin(),
	],
	optimization: {
		noEmitOnErrors: true,
	},
};

const development = () => {
	const config = Object.assign({}, coreConfig, {
		name: 'dev-config',
		mode: 'development',
		devtool: 'eval',
	});

	return config;
};

const watch = () => {
	const config = Object.assign({}, coreConfig, {
		name: 'watch-config',
		mode: 'development',
		devtool: 'eval',
		watch: true,
	});

	return config;
};

const production = () => {
	const config = Object.assign({}, coreConfig, {
		name: 'prod-config',
		mode: 'production',
	});

	return config;
};

module.exports = [development(), watch(), production()];
