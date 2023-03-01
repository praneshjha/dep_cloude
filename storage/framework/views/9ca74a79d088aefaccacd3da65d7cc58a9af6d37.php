<?php

$consum = number_format($departure_details->book_sum + $departure_details->hold_sum) / 2;
?>
<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total Room", <?php echo e($departure_details->total_room); ?>, "#632453"],
            ["Consum Room", <?php echo e(round($consum)); ?>, "#368cf7"],

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
            title: "Room",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
        chart.draw(view, options);
    }
</script>


<div id="columnchart_values1" style="width:250px; height: 300px;"></div>
<?php /**PATH /var/www/html/departurecloud/resources/views/graph/room_graph.blade.php ENDPATH**/ ?>