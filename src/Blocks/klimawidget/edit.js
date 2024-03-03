// external dependecies
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from "chart.js";
import { Bar } from "react-chartjs-2";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import apiFetch from "@wordpress/api-fetch";
import { useEffect, useState, useMemo, useRef } from "@wordpress/element";
import { useBlockProps } from "@wordpress/block-editor";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, PanelRow, ButtonGroup, Button, TextControl, SelectControl, SVG, Path } from "@wordpress/components";

function Edit({ attributes, setAttributes }) {
	const { title, datasetOptions } = attributes;
	const [titleValue, setTitleValue] = useState(title ? title : "Chart.js Bar Chart");
	const [labels, setLabels] = useState([]);
	const [datasets, setDatasets] = useState([]);

	const [apiResults, setApiResults] = useState({});

	useEffect(() => {
		setLabels(["2020", "2021", "2022", "2023", "2024"]);
		setDatasets([{ label: "Wind Power", data: [100, 200, 440, 555, 777] }]);
	}, []);

	const options = {
		responsive: true,
		plugins: {
			legend: {
				position: "top",
			},
			title: {
				display: true,
				text: title ? title : "Chart.js Bar Chart",
			},
		},
	};

	const data = {
		labels,
		datasets: datasets,
	};

	const blockProps = useBlockProps();

	useEffect(() => {
		setAttributes({
			...attributes,
			title: titleValue,
		})
	}, [titleValue]);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Chart Settings", "oklab-klimawidget")}>
					<TextControl
						label={__("Chart Title", "oklab-klimawidget")}
						type="string"
						value={titleValue}
						onChange={(value) => setTitleValue(value)}
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
