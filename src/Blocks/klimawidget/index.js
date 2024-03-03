//  Import CSS.
import "./style.scss";
import "./editor.scss";

// external dependencies

// Internal dependencies
import metadata from "./block.json";
import Edit from "./edit";

/**
 * Internal block libraries
 */
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";

const { apiVersion, name, attributes, supports, keywords, category } = metadata;

export const settings = {
	apiVersion: apiVersion,
	title: __("Climate Widget", "oklab-klimawidget"),
	description: __("Climate Data Visualization", "oklab-klimawidget"),
	category: category,
	icon: "chart-line",
	keyword: keywords,
	supports: supports,
	attributes: attributes,
	edit: Edit,
	save: function () {
		return null;
	},
};

registerBlockType(name, settings);
