const path = require('path');
const webpack = require('webpack');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');

const coreConfig = (options = {}) => ({
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
					{
						loader: MiniCssExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'postcss-loader',
						options: {
							ident: 'postcss',
							plugins(loader) {
								const { postcss } = options;
								const { plugins } = postcss || {};

								return [
									require('postcss-import')({ root: loader.resourcePath }),
									require('postcss-preset-env')(),
									...(plugins || []),
								];
							},
						},
					},
				],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin(),
	],
	optimization: {
		noEmitOnErrors: true,
		splitChunks: {
			chunks: 'all',
			automaticNameDelimiter: '-',
		},
	},
});

const development = () => {
	const config = Object.assign(coreConfig(), {
		name: 'dev-config',
		mode: 'development',
		devtool: 'eval',
	});

	return config;
};

const watch = () => {
	const config = Object.assign(coreConfig(), {
		name: 'watch-config',
		mode: 'development',
		devtool: 'eval',
		watch: true,
	});

	return config;
};

const production = () => {
	const options = {
		postcss: {
			plugins: [
				require('cssnano')(),
			],
		},
	};

	const config = Object.assign(coreConfig(options), {
		name: 'prod-config',
		mode: 'production',
	});

	return config;
};

const analyze = () => {
	const config = Object.assign(coreConfig(), {
		name: 'analyze-config',
		mode: 'production',
	});

	const analyzer = new BundleAnalyzerPlugin();
	config.plugins.push(analyzer);

	return config;
};

module.exports = [development(), watch(), production(), analyze()];
