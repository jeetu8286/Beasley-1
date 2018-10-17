const path = require('path');
const webpack = require('webpack');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');

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
		splitChunks: {
			chunks: 'all',
			automaticNameDelimiter: '-',
		},
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

const analyze = () => {
	const config = Object.assign({}, coreConfig, {
		name: 'analyze-config',
		mode: 'production',
	});

	config.plugins.push(
		new BundleAnalyzerPlugin()
	);

	return config;
};

module.exports = [development(), watch(), production(), analyze()];
