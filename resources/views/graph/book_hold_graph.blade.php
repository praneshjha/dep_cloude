<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", {{$sharing_book + $sharing_hold}}, "#632453"],
            ["Total Book", {{$sharing_book}}, "green"],
            ["Total Hold", {{$sharing_hold}}, "#368cf7"],
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
            title: "Book & Hold",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("hold_book"));
        chart.draw(view, options);
    }
</script>

<div id="hold_book" style="width: 250px; height: 300px;"></div>
