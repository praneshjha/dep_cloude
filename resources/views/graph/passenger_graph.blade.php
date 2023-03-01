<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", {{ $sharing_book + $sharing_hold }}, "#632453"],
            ["Adult", {{$passenger_b+$passenger_h}}, "#2482f6"],
            ["Child", {{$passenger1_b+$passenger1_h}}, "#00cbb5"],
            ["Infant", {{$passenger2_b+$passenger2_h}}, "green"],

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
            title: "Age Bracket",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("passenger"));
        chart.draw(view, options);
    }
</script>

<div id="passenger" style="width: 250px; height: 300px;"></div>
