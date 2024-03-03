// import { Chart as ChartJS, BarController, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from "chart.js";
import Chart from "chart.js/auto";

(() => {
	const widgets = document.querySelectorAll(".klimawidget");

	function getData(chart) {
		const xmlhttp = new XMLHttpRequest();
		const formData = new FormData();
		formData.append("action", "oklab_klimadata");

		// if (typeof search !== "undefined") {
		// 	formData.append("search", search);
		// }

		xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				const data = JSON.parse(this.responseText);
				updateData(chart, data);
			}
		};

		xmlhttp.open("POST", oklabKlimawidgetGlobal.ajaxurl);
		xmlhttp.send(formData);
	}

	function updateData(chart, data) {
		console.log(data);
		const results = data ? data.results : [];
		const newLabels = results.map((entry) => entry.date);
		const newEntries = results.map((entry) => entry.aggregate);

		chart.data.labels = newLabels;
		chart.data.datasets.forEach((dataset) => {
			dataset.data = newEntries;
		});
		console.log(chart.data);
		chart.update();
	}

	if (widgets) {
		widgets.forEach((widget) => {
			const uid = widget.dataset.uid;
			const ctx = document.getElementById(uid);
			const opt = window[uid];
			console.log(opt);

			if (ctx) {
				const chart = new Chart(ctx, {
					type: "bar",
					data: {
						labels: opt.labels,
						datasets: opt.datasets,
					},
					options: opt.options,
				});

				getData(chart);
			}
		});
	}
})();
