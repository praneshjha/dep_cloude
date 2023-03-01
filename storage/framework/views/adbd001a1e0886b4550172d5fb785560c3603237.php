<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total Units", <?php echo e($departure_details->total_seat); ?>, "#632453"],
            ["Consum units", <?php echo e(number_format($departure_details->book_sum) + number_format($departure_details->hold_sum)); ?>, "green"],

        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Departure Units",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        chart.draw(view, options);
    }
</script>


<div id="columnchart_values" style="width:250px; height: 300px;"></div>
<?php /**PATH /var/www/html/departurecloud/resources/views/graph/unit_graph.blade.php ENDPATH**/ ?>