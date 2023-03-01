<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", {{ $sharing_book + $sharing_hold }}, "#632453"],
            ["Economy", {{$flight2_b+$flight2_h}}, "#ef7b00"],
            ["Business", {{$flight1_b+$flight1_h}}, "green"],
            ["First", {{$flight_b+$flight_h}}, "#2482f6"],
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
            title: "Flight Class",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("flight_class"));
        chart.draw(view, options);
    }
</script>

<div id="flight_class" style="width: 250px; height: 300px;"></div>
