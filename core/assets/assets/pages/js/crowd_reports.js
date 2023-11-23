!(function (e) {
	var t = {};
	function r(o) {
		if (t[o]) return t[o].exports;
		var a = (t[o] = { i: o, l: !1, exports: {} });
		return e[o].call(a.exports, a, a.exports, r), (a.l = !0), a.exports;
	}
	(r.m = e),
		(r.c = t),
		(r.d = function (e, t, o) {
			r.o(e, t) || Object.defineProperty(e, t, { enumerable: !0, get: o });
		}),
		(r.r = function (e) {
			"undefined" != typeof Symbol &&
				Symbol.toStringTag &&
				Object.defineProperty(e, Symbol.toStringTag, { value: "Module" }),
				Object.defineProperty(e, "__esModule", { value: !0 });
		}),
		(r.t = function (e, t) {
			if ((1 & t && (e = r(e)), 8 & t)) return e;
			if (4 & t && "object" == typeof e && e && e.__esModule) return e;
			var o = Object.create(null);
			if (
				(r.r(o),
				Object.defineProperty(o, "default", { enumerable: !0, value: e }),
				2 & t && "string" != typeof e)
			)
				for (var a in e)
					r.d(
						o,
						a,
						function (t) {
							return e[t];
						}.bind(null, a),
					);
			return o;
		}),
		(r.n = function (e) {
			var t =
				e && e.__esModule
					? function () {
							return e.default;
					  }
					: function () {
							return e;
					  };
			return r.d(t, "a", t), t;
		}),
		(r.o = function (e, t) {
			return Object.prototype.hasOwnProperty.call(e, t);
		}),
		(r.p = ""),
		r((r.s = 42));
})({
	42: function (e, t, r) {
		e.exports = r(43);
	},
	43: function (e, t) {
		function r(e, t) {
			for (var r = 0; r < t.length; r++) {
				var o = t[r];
				(o.enumerable = o.enumerable || !1),
					(o.configurable = !0),
					"value" in o && (o.writable = !0),
					Object.defineProperty(e, o.key, o);
			}
		}
		var o = (function () {
			function e() {
				!(function (e, t) {
					if (!(e instanceof t))
						throw new TypeError("Cannot call a class as a function");
				})(this, e);
			}
			var t, o, a;
			return (
				(t = e),
				(a = [
					{
						key: "initClassicChartJS",
						value: function () {
							(Chart.defaults.global.defaultFontColor = "#7c7c7c"),
								(Chart.defaults.scale.gridLines.color = "#f5f5f5"),
								(Chart.defaults.scale.gridLines.zeroLineColor = "#f5f5f5"),
								(Chart.defaults.scale.display = !0),
								(Chart.defaults.scale.ticks.beginAtZero = !0),
								(Chart.defaults.global.elements.line.borderWidth = 2),
								(Chart.defaults.global.elements.point.radius = 5),
								(Chart.defaults.global.elements.point.hoverRadius = 7),
								(Chart.defaults.global.tooltips.cornerRadius = 3),
								(Chart.defaults.global.legend.display = !1);
							
							var	t = jQuery(".js-flot-time-of-day");
								w = jQuery(".js-flot-day-of-week");
							
							t.length &&
								new Chart(t, {
									type: "line",
									data: {
										labels: _s("time_range"),
										datasets: [
											{
												label: "This Year",
												fill: !0,
												backgroundColor: "rgba(249,126,54,.15)",
												borderColor: "rgba(249,126,54,.5)",
												pointBackgroundColor: "rgba(249,126,54,.5)",
												pointBorderColor: "#fff",
												pointHoverBackgroundColor: "#fff",
												pointHoverBorderColor: "rgba(249,126,54,.5)",
												data: _s("popular_times"),
											},
										],
									},
									options: {
										scales: {
											yAxes: [
												{
													ticks: {
														suggestedMax: 0,
														callback: function (value, index, values) {
															return value + "%";
														},
													},
												},
											],
										},
										tooltips: {
											callbacks: {
												label: function (e, t) {
													return e.yLabel + "%";
												},
											},
										},
									},
								});
							w.length &&
							new Chart(w, {
								type: "line",
								data: {
									labels: _s("week_day"),
									datasets: [
										{
											label: "This Year",
											fill: !0,
											backgroundColor: "rgba(249,126,54,.15)",
											borderColor: "rgba(249,126,54,.5)",
											pointBackgroundColor: "rgba(249,126,54,.5)",
											pointBorderColor: "#fff",
											pointHoverBackgroundColor: "#fff",
											pointHoverBorderColor: "rgba(249,126,54,.5)",
											data: _s("week_day_orders"),
										},
									],
								},
								options: {
									scales: {
										yAxes: [
											{
												ticks: {
													suggestedMax: 20,
													callback: function (value, index, values) {
														return value + "%";
													},
												},
											},
										],
									},
									tooltips: {
										callbacks: {
											label: function (e, t) {
												return " "+ e.yLabel + " %";
											},
										},
									},
								},
							});
							
						},
					},
					{
						key: "init",
						value: function () {
							this.initClassicChartJS();
						},
					},
				]),
				(o = null) && r(t.prototype, o),
				a && r(t, a),
				e
			);
		})();
		jQuery(function () {
			o.init();
		});
	},
});
Vue.component("date-range-picker", window["vue2-daterange-picker"].default);
Vue.component("pie-chart-order", {
	extends: window.VueChartJs.Pie,
	created: function () {
		var self = this;
		bus.$on("renderPieChartOrder", function (payload) {
			var chartData = payload.chartData;
			var options = payload.options;
			self.renderChart(chartData, options);
		});
	},
});
Vue.mixin({
	methods: {
		dtFormat: function (date) {
			return moment(date).format("DD/MM/YYYY");
		},
		beautifyDate: function (value) {
			return this.$options.filters.beautifyDate(value);
		},
	},
});
Vue.component("pie-chart-earning", {
	extends: window.VueChartJs.Pie,
	created: function () {
		var self = this;
		bus.$on("renderPieChartEarning", function (payload) {
			var chartData = payload.chartData;
			var options = payload.options;
			self.renderChart(chartData, options);
		});
	},
});
Vue.mixin({
	methods: {
		dtFormat: function (date) {
			return moment(date).format("DD/MM/YYYY");
		},
		beautifyDate: function (value) {
			return this.$options.filters.beautifyDate(value);
		},
	},
});
Vue.component("crowd-reports", {
	template: "#crowd-reports-template",
	data: function () {
		return {
			module: "crowd_reports",
			params: {},
			filteredDateRange: {
				startDate: _s("startDate")
					? _s("startDate")
					: moment().subtract(1, "weeks"),
				endDate: _s("endDate") ? _s("endDate") : moment(),
			},
			displayDate: {
				startDate: "",
				endDate: "",
			},
			enableFilterBtn: true,
			dashData: {
				summary: {
					all: 0,
					confirmed: 0,
					closed: 0,
					cancelled: 0,
					avgEarnings: 0,
					totalEarnings: 0,
				},
				items: [],
				mostVisitedCustomers:[],
				lastOrders: [],
				yearlyData: _s("yearlyData"),
			},
			orderSources: [],
			sourceId: "",
			pieChartOrders: {
				chartData: {
					labels: [],
					datasets: [
						{
							borderWidth: 1,
							borderColor: this.getBorderColor(),
							backgroundColor: this.getBackgroundColor(),
							data: [],
						},
					],
				},
				chartOptions: this.getChartOptions(),
			},
			pieChartEarnings: {
				chartData: {
					labels: [],
					datasets: [
						{
							borderWidth: 1,
							borderColor: this.getBorderColor(),
							backgroundColor: this.getBackgroundColor(),
							data: [],
						},
					],
				},
				chartOptions: this.getChartOptions(),
			},
			baseUrl:_s('crowdReportsUrl'),
			dbWeekDays:_s('dbWeekDays'),
			weekDayId:_s('week_day_id'),
		};
	},
	computed: {
		weekDay:function(){
			var self = this;
			var week =  this.dbWeekDays.find(function (w) {
				return Number(w.id) === Number(self.weekDayId);
			});
			return week.value;
		},
		dateRange: function () {
			return (
				moment(this.filteredDateRange.startDate).format("D/M/Y") +
				" - " +
				moment(this.filteredDateRange.endDate).format("D/M/Y")
			);
		},
		totalEarnings: function () {
			return (
				Number(this.dashData.summary.totalEarnings) -
				Number(this.dashData.summary.refundTotal)
			);
		},
		cancelled: function () {
			return Number(this.dashData.summary.cancelled);
		},
		closed: function () {
			return (
				Number(this.dashData.summary.closed) +
				Number(this.dashData.summary.partialRefunded)
			);
		},
		refundTotal: function () {
			return Number(this.dashData.summary.refundTotal);
		},
		avgOrder: function () {
			return Number(this.dashData.summary.avgOrder);
		},
		avgPayOrder: function () {
			return Number(this.dashData.summary.avgPayOrder);
		},
		dineOrder: function () {
			return Number(this.dashData.summary.dineOrder);
		},
		pickUpOrder: function () {
			return Number(this.dashData.summary.pickUpOrder);
		},
		totalCustomers: function () {
			return Number(this.dashData.summary.totalCustomers);
		},
		items: function () {
			return this.dashData.items;
		},
		mostVisitedCustomers: function () {
			return this.dashData.mostVisitedCustomers;
		},
		lastOrders: function () {
			return this.dashData.lastOrders;
		},
		getStatus: function () {
			return "text-primary";
		},
		yearCount: function () {
			return Number(this.dashData.yearlyData.yearCount)
				? Number(this.dashData.yearlyData.yearCount)
				: 0;
		},
		yearEarnings: function () {
			return Number(this.dashData.yearlyData.yearEarnings)
				? Number(this.dashData.yearlyData.yearEarnings)
				: 0;
		},
		avgYearCount: function () {
			var d = new Date();
			var n = d.getMonth() + 1;
			/* var months = _s("this_year_months");
			var n = months.length; */
			var yearCount = Number(this.dashData.yearlyData.yearCount);
			var avgYearCount = yearCount / n;
			return Math.ceil(Number(avgYearCount));
		},
		avgYearEarnings: function () {
			var d = new Date();
			var n = d.getMonth() //+ 1;
			/* var months = _s("this_year_months");
			var n = months.length;
			if (Number(n) === 0) {
				n += 1;
			} */

			var yearCount = Number(this.dashData.yearlyData.yearEarnings)
				? Number(this.dashData.yearlyData.yearEarnings)
				: 0;
			var avgYearEarnings = yearCount / n;

			return Number(avgYearEarnings).toFixed(2);
		},
	},
	methods: {
		getBorderColor: function () {
			return [
				"rgba(255,99,132,1)",
				"rgba(54, 162, 235, 1)",
				"rgba(255, 206, 86, 1)",
				"rgba(75, 192, 192, 1)",
				"rgba(255, 99, 31, 1)",
				"rgba(106, 90, 205, 1)",
				"rgba(65, 90, 113,1)",
				"rgba(60, 60, 60,1)",
				"rgba(113, 99, 71, 1)",
				"rgba(75, 176, 196,1)",
			];
		},
		getBackgroundColor: function () {
			return [
				"rgba(255, 99, 132, 0.2)",
				"rgba(54, 162, 235, 0.2)",
				"rgba(255, 206, 86, 0.2)",
				"rgba(75, 192, 192, 0.2)",
				"rgba(255, 99, 31, 0.2)",
				"rgba(106, 90, 205,0.2)",
				"rgba(65, 179, 113,0.2)",
				"rgba(60, 60, 60,0.2)",
				"rgba(113, 99, 71,0.2)",
				"rgba(75, 176, 196,0.2)",
			];
		},
		getChartOptions: function () {
			return {
				legend: {
					display: true,
				},
				responsive: true,
				maintainAspectRatio: false,
				pieceLabel: {
					mode: "percentage",
					precision: 1,
				},
			};
		},
		getPieChartLabels: function () {
			var source = [];
			this.orderSources.forEach(function (s) {
				if (s.value !== "All") {
					source.push(s.value);
				}
			});
			return source;
		},
		filterOrderSource: async function () {
			Codebase.blocks(".order-source-block", "state_loading");
			var self = this;
			var data = {
				module: this.module,
				method: "orderBySource",
				sourceId: self.sourceId,
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.dashData.lastOrders = response.orderBySource;
			}
			Codebase.blocks(".order-source-block", "state_normal");
		},
		handleFilter: function () {
			this.filterData();
		},
		filterData: async function (loader) {
			loader = typeof loader === "undefined";
			this.enableFilterBtn = false;
			var self = this;
			if (loader) {
				Codebase.blocks(".crowd-reports-filter-block", "state_loading");
			}
			var url = this.baseUrl;
			var startDate = moment(this.filteredDateRange.startDate).format(
				"YYYY/MM/DD",
			);
			var endDate = moment(this.filteredDateRange.endDate).format("YYYY/MM/DD");
				url += "?filterStartDate=" + startDate;
			
			url += "&filterEndDate=" + endDate;
			url += "&weekDayId=" + this.weekDayId;

			window.location = url;
			/* var data = {
				module: this.module,
				method: "filter_list",
				filterStartDate: startDate,
				filterEndDate: endDate,
				sourceId: this.sourceId,
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.displayDate.startDate = this.filteredDateRange.startDate;
				self.displayDate.endDate = this.filteredDateRange.endDate;
				self.dashData.summary = response.dashboard;
				self.dashData.items = response.items;
				self.dashData.lastOrders = response.lastOrders;
				self.dashData.mostVisitedCustomers = response.mostVisitedCustomers;
				self.pieChartOrders.chartData.datasets[0].data = Object.values(
					response.pieChartOrders,
				);
				self.pieChartEarnings.chartData.datasets[0].data = Object.values(
					response.pieChartEarnings,
				);
				this.updatePieChartOrder();
				this.updatePieChartEarning();
			} */
			if (loader) {
				Codebase.blocks(".crowd-reports-filter-block", "state_normal");
			}
			this.enableFilterBtn = true;
		},
		updatePieChartOrder: function () {
			this.pieChartOrders.chartData.labels = this.getPieChartLabels();
			bus.$emit("renderPieChartOrder", {
				chartData: this.pieChartOrders.chartData,
				options: this.pieChartOrders.chartOptions,
			});
		},
		updatePieChartEarning: function () {
			this.pieChartEarnings.chartData.labels = this.getPieChartLabels();
			bus.$emit("renderPieChartEarning", {
				chartData: this.pieChartEarnings.chartData,
				options: this.pieChartEarnings.chartOptions,
			});
		},
		setPieChartData:function(){
			this.pieChartOrders.chartData.datasets[0].data = Object.values(
				_s('pieChartOrders')
			);
			this.pieChartEarnings.chartData.datasets[0].data = Object.values(
				_s('pieChartEarnings')
			);
			this.updatePieChartOrder();
			this.updatePieChartEarning();
		}
	},
	mounted: function () {
		//this.filterData(false);
		this.orderSources = _s("orderSources");
		this.setPieChartData();
		
	},
});
