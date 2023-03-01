<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", <?php echo e($sharing_book + $sharing_hold); ?>, "#632453"],
            ["1 Star", <?php echo e($h_1_b+$h_1_h); ?>, "green"],
            ["2 Star", <?php echo e($h_2_b+$h_2_h); ?>, "#e900ac"],
            ["3 Star", <?php echo e($h_3_b+$h_3_h); ?>, "#2482f6"],
            ["4 Star", <?php echo e($h_4_b+$h_4_h); ?>, "#00cbb5"],
            ["5 Star", <?php echo e($h_5_b+$h_5_h); ?>, "#ed5aff"],
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
            title: "Hotel Type",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("Hotel"));
        chart.draw(view, options);
    }
</script>

<div id="Hotel" style="width: 250px; height: 300px;"></div>
<?php /**PATH /var/www/html/departurecloud/resources/views/graph/hotel_graph.blade.php ENDPATH**/ ?>