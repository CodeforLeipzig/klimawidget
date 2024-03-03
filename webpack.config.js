const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require('path');

module.exports = {
	...defaultConfig,

	// https://webpack.js.org/configuration/entry-context/#context
	context: path.resolve(__dirname, 'src'),

	entry: {
		'klimawidget': './Blocks/klimawidget',
		'widgets': './Blocks/klimawidget/widgets.js'
	},

	output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, './dist'),
		filename: '[name].js'
	}
}