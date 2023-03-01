<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "", {role: "style"}],
            ["Total", {{ $sharing_book + $sharing_hold }}, "#632453"],
            ["AP", {{$meal_amp_b+$meal_amp_h}}, "#2482f6"],
            ["MAP", {{$meal_mamp_b+$meal_mamp_h}}, "green"],
            ["CP", {{$meal_cmp_b+$meal_cmp_h}}, "#ef7b00"],
            ["EP", {{$meal_ep_b+$meal_ep_h}}, "#ed5aff"],
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
            title: "Meal Plan",
            width: 250,
            height: 300,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("meal_plan"));
        chart.draw(view, options);
    }
</script>

<div id="meal_plan" style="width: 250px; height: 300px;"></div>
