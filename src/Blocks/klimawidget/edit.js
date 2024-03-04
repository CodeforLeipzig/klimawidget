// external dependecies
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from "chart.js";
import { Bar } from "react-chartjs-2";
import hash from "object-hash";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import apiFetch from "@wordpress/api-fetch";
import { addQueryArgs } from "@wordpress/url";
import { useEffect, useState, useMemo, useRef } from "@wordpress/element";
import { useBlockProps } from "@wordpress/block-editor";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, PanelRow, ButtonGroup, Button, TextControl, SelectControl, SVG, Path } from "@wordpress/components";

const dataApiBase = "/oklab_climate_data/v1/sachsen";

function formatDataLabel(label) {
	switch (label) {
		case "WIND":
			label = "Zubau Windkraftanlagen";
			break;
		case "SOLAR_POWER":
			label = "Zubau Photovoltaik";
			break;

		default:
			break;
	}
	return label;
}

function Edit({ attributes, setAttributes }) {
	const { title, datasetOptions } = attributes;
	const [labels, setLabels] = useState([]);
	const [datasets, setDatasets] = useState([]);

	const [apiResults, setApiResults] = useState({});

	useEffect(() => {
		if (datasetOptions && Array.isArray(datasetOptions) && datasetOptions.length > 0) {
			const newLabels = [];
			const newDatasets = [];

			datasetOptions.map((options) => {
				const reqHash = hash({
					property: options.property,
					energySource: options.energySource,
					interval: options.interval,
				});

				if (typeof apiResults[reqHash] === "undefined") {
					apiFetch({
						path: dataApiBase,
						method: "GET",
					}).then((res) => {
						console.log(res);
						const results = res?.results || [];
						const xLabels = results.map((entry) => entry.date);
						const dataLabel = formatDataLabel(res ? res.energySource : "");

						const newDatasets = [
							{
								label: dataLabel,
								data: results.map((entry) => entry.aggregate),
								backgroundColor: "rgba(255, 99, 132, 0.5)",
							},
						];

						setLabels(xLabels);
						setDatasets(newDatasets);
					});
				}
			});
		}
		// setDatasets();
	}, [datasetOptions]);

	const options = {
		responsive: true,
		scales: {
			y: {
				ticks: {
					callback: function (value, index, ticks) {
						return value / 1000 + " MW";
					},
				},
			},
		},
		plugins: {
			legend: {
				position: "bottom",
			},
			title: {
				display: true,
				text: title,
			},
			tooltip: {
				callbacks: {
					label: function (context) {
						let label = " " + context.dataset.label || "";

						if (label) {
							label += ": ";
						}
						if (context.parsed.y !== null) {
							label += (Number.parseFloat(context.parsed.y) / 1000).toFixed(1) + " MW";
						}
						return label;
					},
				},
			},
		},
	};

	const data = {
		labels,
		datasets: datasets,
	};

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Chart Settings", "oklab-klimawidget")}>
					<TextControl
						label={__("Chart Title", "oklab-klimawidget")}
						type="string"
						value={title}
						onChange={(value) => setAttributes({ title: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<Bar options={options} data={data} />
			</div>
		</>
	);
}

export default Edit;
