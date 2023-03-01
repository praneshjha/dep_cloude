<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", {{ $sharing_book +$sharing_hold}}, "#632453"],
            ["SIC", {{$t_sic_b+$t_sic_h}}, "green"],
            ["Private", {{$t_private_b+$t_private_h}}, "green"],
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
            title: "Transport Type",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("transport"));
        chart.draw(view, options);
    }
</script>

<div id="transport" style="width: 250px; height: 300px;"></div>
