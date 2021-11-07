jQuery(function($){
	function setDashboardChartWidth() {
		var width = $(".chart-container-width-basis").width()

		// $("figure.chart-container").width(width)
		// $(".chart g").width(width)
		$(".chart").width(width)
		// $(".chart svg").width(width)
		console.log(width)
	}

	setDashboardChartWidth()
})