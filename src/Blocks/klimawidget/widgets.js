// import { Chart as ChartJS, BarController, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from "chart.js";
import Chart from 'chart.js/auto';

(() => {
	const widgets = document.querySelectorAll(".klimawidget");

	if (widgets) {
		widgets.forEach((widget) => {
			const uid = widget.dataset.uid;
			const ctx = document.getElementById(uid);
			const opt = window[uid];

			if (ctx) {
				new Chart(ctx, {
					type: "bar",
					data: {
						labels: opt.labels,
						datasets: opt.datasets,
					},
					options: opt.options,
				});
			}
		});
	}
})();
