// import { Chart as ChartJS, BarController, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from "chart.js";
import Chart from "chart.js/auto";

(() => {
	const widgets = document.querySelectorAll(".klimawidget");

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

	function getData(chart) {
		const req = new XMLHttpRequest();

		const dataApiBase = oklabKlimawidgetGlobal.data_api_base;
		const bundesland = "sachsen";

		let url = dataApiBase + "/" + bundesland;

		req.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				const data = JSON.parse(this.responseText);
				updateData(chart, data);
			}
		};

		req.open("GET", url);
		req.send();
	}

	function updateData(chart, data) {
		// console.log("update data: " + JSON.stringify(data, null, 2));
		const results = data ? data.results : [];
		const xLabels = results.map((entry) => entry.date);
		const dataLabel = formatDataLabel(data ? data.energySource : "");

		const newDatasets = [
			...chart.data.datasets,
			{
				label: dataLabel,
				data: results.map((entry) => entry.aggregate),
				backgroundColor: "rgba(255, 99, 132, 0.5)",
			},
		];

		chart.data.labels = xLabels;
		chart.data.datasets = newDatasets;
		// console.log(chart.data);
		chart.update();
	}

	if (widgets) {
		widgets.forEach((widget) => {
			const uid = widget.dataset.uid;
			const ctx = document.getElementById(uid);
			const opt = window[uid];
			// console.log(opt);

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
						text: opt?.options?.title_text,
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

			if (ctx) {
				const chart = new Chart(ctx, {
					type: "bar",
					data: {
						datasets: [],
					},
					options: options,
				});

				getData(chart);
			}
		});
	}
})();
