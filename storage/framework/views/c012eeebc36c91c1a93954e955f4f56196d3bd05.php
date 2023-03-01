<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", <?php echo e($sharing_book + $sharing_hold); ?>, "#632453"],
            ["Single", <?php echo e($single_b+$single_h); ?>, "green"],
            ["Double", <?php echo e($double_b+$double_h); ?>, "#00cbb5"],
            ["Triple", <?php echo e($triple_b+$triple_h); ?>, "#2482f6"],
            ["Quard", <?php echo e($quard_b+$quard_h); ?>, "#2482f6"],
            ["CWB", <?php echo e($twin_cwbo_b+$twin_cwbo_h); ?>, "#e900ac"],
            ["CW/OB", <?php echo e($twin_cwb_b+$twin_cwb_h); ?>, "#ef7b00"],

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
            title: "Room Sharing",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("sharing"));
        chart.draw(view, options);
    }
</script>

<div id="sharing" style="width: 250px; height: 300px;"></div>
<?php /**PATH /var/www/html/departurecloud/resources/views/graph/sharing.blade.php ENDPATH**/ ?>