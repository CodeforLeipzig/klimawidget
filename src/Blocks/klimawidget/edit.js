// external dependecies
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from "chart.js";
import { Bar } from "react-chartjs-2";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

function Edit() {
	const options = {
		responsive: true,
		plugins: {
			legend: {
				position: "top",
			},
			title: {
				display: true,
				text: "Chart.js Bar Chart",
			},
		},
	};

	const labels = ["January", "February", "March", "April", "May", "June", "July"];

	const data = {
		labels,
		datasets: [
			{
				label: "Dataset 1",
				data: [542, 234, 233, 689, 213, 111, 978, 623],
				backgroundColor: "rgba(255, 99, 132, 0.5)",
			},
			{
				label: "Dataset 2",
				data: [542, 234, 672, 232, 999, 345, 123, 43],
				backgroundColor: "rgba(53, 162, 235, 0.5)",
			},
		],
	};

	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<Bar options={options} data={data} />
		</div>
	);
}

export default Edit;
