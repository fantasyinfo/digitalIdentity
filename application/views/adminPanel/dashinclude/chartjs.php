<!-- <script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Details', 'Students Gender'],
            ['Total Girls',<?=$totalboys?>],
            ['Total Boys',<?=$totalgirls?>]
        ]);

        var options = {
            title: 'Total Students Details',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('student_chart'));
        chart.draw(data,options);
    }
</script> -->