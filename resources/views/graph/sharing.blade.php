<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", {{ $sharing_book + $sharing_hold }}, "#632453"],
            ["Single", {{$single_b+$single_h}}, "green"],
            ["Double", {{$double_b+$double_h}}, "#00cbb5"],
            ["Triple", {{$triple_b+$triple_h}}, "#2482f6"],
            ["Quard", {{$quard_b+$quard_h}}, "#2482f6"],
            ["CWB", {{$twin_cwbo_b+$twin_cwbo_h}}, "#e900ac"],
            ["CW/OB", {{$twin_cwb_b+$twin_cwb_h}}, "#ef7b00"],

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
