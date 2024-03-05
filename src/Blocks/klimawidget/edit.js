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
import { useEffect, useState } from "@wordpress/element";
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

function getApiPath(options) {
	let path = "";
	const source = options.energySource;
	const interval = options.interval;

	if (source) {
		switch (source) {
			case "WIND":
				path += "/wind";
				break;
			case "SOLAR_POWER":
				path += "/solar_power";
				break;
		}
	}

	if (interval) {
		switch (interval) {
			case "YEAR":
				path += "/year";
				break;
			case "MONTH":
				path += "/month";
				break;
			case "WEEK":
				path += "/week";
				break;
		}
	}

	return path;
}

function Edit({ attributes, setAttributes }) {
	const { title, dataset, datasetOptions } = attributes;
	const [labels, setLabels] = useState([]);
	const [datasets, setDatasets] = useState([]);
	const [apiResults, setApiResults] = useState({});

	function setDataset(label, data, x) {
		const newDataset = { label: label, data: data, x: x };
		setAttributes({ dataset: newDataset });
	}

	useEffect(() => {
		if (datasetOptions) {
			const reqHash = hash({
				property: datasetOptions.property,
				energySource: datasetOptions.energySource,
				interval: datasetOptions.interval,
			});

			if (typeof apiResults[reqHash] === "undefined") {
				apiFetch({
					path: dataApiBase + getApiPath(datasetOptions),
					method: "GET",
				}).then((res) => {
					const results = res?.results || [];
					const xLabels = results.map((entry) => entry.date);
					const dataLabel = formatDataLabel(res?.energySource);
					const data = results.map((entry) => entry.aggregate);

					const newDatasets = {
						label: dataLabel,
						data: data,
						backgroundColor: datasetOptions.backgroundColor,
					};

					setDataset(dataLabel, data, xLabels);
					setLabels(xLabels);
					setDatasets([newDatasets]);
					setApiResults({ ...apiResults, [reqHash]: res });
				});
			} else {
				const results = apiResults[reqHash].results;
				const xLabels = results.map((entry) => entry.date);
				const dataLabel = formatDataLabel(apiResults[reqHash]?.energySource);
				const data = results.map((entry) => entry.aggregate);

				const newDatasets = {
					label: dataLabel,
					data: data,
					backgroundColor: datasetOptions.backgroundColor,
				};

				setDataset(dataLabel, data, xLabels);
				setLabels(xLabels);
				setDatasets([newDatasets]);
			}
		}
	}, [datasetOptions]);

	function setIntervalValue(value) {
		const newDatasetOptions = { ...datasetOptions, interval: value };
		setAttributes({ datasetOptions: newDatasetOptions });
	}

	function onChangeEnergySourceValue(value) {
		const newDatasetOptions = { ...datasetOptions, energySource: value };
		setAttributes({ datasetOptions: newDatasetOptions });
	}

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
				<PanelBody title={__("Dataset Option", "oklab-klimawidget")}>
					<h4>Interval</h4>
					<ButtonGroup>
						<Button variant={datasetOptions?.interval === "YEAR" ? "primary" : "secondary"} onClick={() => setIntervalValue("YEAR")}>
							Year
						</Button>
						<Button variant={datasetOptions?.interval === "MONTH" ? "primary" : "secondary"} onClick={() => setIntervalValue("MONTH")}>
							Month
						</Button>
						<Button variant={datasetOptions?.interval === "WEEK" ? "primary" : "secondary"} onClick={() => setIntervalValue("WEEK")}>
							Week
						</Button>
					</ButtonGroup>
					<SelectControl
						label={__("Energy Source", "oklab-klimawidget")}
						className="oklab-select-energy-source"
						value={datasetOptions?.energySource}
						options={[
							{ label: "Windkraft", value: "WIND" },
							{ label: "Photovoltaik", value: "SOLAR_POWER" },
						]}
						onChange={onChangeEnergySourceValue}
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
