
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "", { role: "style" } ],
        ["Total", <?php echo e($sharing_book +$sharing_hold); ?>, "#632453"],
        ["SIC", <?php echo e($airport1_b+$airport1_h); ?>, "green"],
        ["Private", <?php echo e($airport2_b+$airport2_h); ?>, "#ef7b00"],
        ["Not Included", <?php echo e($airport_b+$airport_h); ?>, "#f5a2ff"],        
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Airport Transfer",
        width: 250,
        height: 300,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("airport_transfer"));
      chart.draw(view, options);
  }
  </script>
  
  <div id="airport_transfer" style="width: 250px; height: 300px;"></div>
<?php /**PATH /var/www/html/departurecloud/resources/views/graph/airtransfer_graph.blade.php ENDPATH**/ ?>