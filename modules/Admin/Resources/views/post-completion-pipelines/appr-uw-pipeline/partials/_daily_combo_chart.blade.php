<div class="col-md-12">
	<div id="daily_combo_chart"></div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load('visualization', '1', {packages: ['corechart']});
    </script>

    <script type="text/javascript">
        function drawVisualization() {
            // Create and populate the data table.
            var data = google.visualization.arrayToDataTable([
            {!!$j!!}
            ]);

            // Create and draw the visualization.
            var ac = new google.visualization.ComboChart(document.getElementById('daily_combo_chart'));
            ac.draw(data, {
            title : 'Monthly User Activity',
            vAxis: {title: "Orders"},
            hAxis: {title: "Month"},
            seriesType: "bars",
            series: { {{(count($userNames))}}: {type: "line"}}
            });
        }
        google.setOnLoadCallback(drawVisualization);
    </script>
@endpush




