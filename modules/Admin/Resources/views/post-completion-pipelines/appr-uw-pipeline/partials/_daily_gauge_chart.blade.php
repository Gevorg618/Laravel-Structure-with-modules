<div class="col-md-12">
    <div class="col-md-4" style="text-align: -webkit-center;">
        <div><b>Orders Receiving Conditions</b></div>
        <div id="daily_gauge_chart_1"></div>
    </div>
    <div class="col-md-4" style="text-align: -webkit-center;">
        <div><b>Total Conditions Received</b></div>
        <div id="daily_gauge_chart_2"></div>
    </div>
    <div class="col-md-4" style="text-align: -webkit-center;">
        <div><b>Average Conditions Per Order</b></div>
        <div id="daily_gauge_chart_3"></div>
    </div>
</div>


@push('scripts')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load('visualization', '1', {packages: ['gauge']});
    </script>
    <script type="text/javascript">
        function drawVisualization() {
            // Create and populate the data table.
            var data = google.visualization.arrayToDataTable([['Label', 'Value'],['Orders', {{$stats['orders_having_conditions']}}]]);
            var options = {
                    max: {{getMaxNumber($stats['orders_having_conditions'])}},
                    min: 1,
                    width: 500,
                    height: 120,
                    redFrom: {{getMaxNumber($stats['orders_having_conditions'])-20}},
                    redTo: {{getMaxNumber($stats['orders_having_conditions'])}},
                    yellowFrom: {{getMaxNumber($stats['orders_having_conditions'])-50}},
                    yellowTo: {{getMaxNumber($stats['orders_having_conditions'])-20}},
                    minorTicks: 5
                };
            // Create and draw the visualization.
            new google.visualization.Gauge(document.getElementById('daily_gauge_chart_1')).draw(data, options);

            // Create and populate the data table.
            var data = google.visualization.arrayToDataTable([['Label', 'Value'],['Conditions', {{$stats['total_conditions']}}]]);
            var options = {
                    max: <?php echo getMaxNumber($stats['total_conditions']) ?>,
                    min: 1,
                    width: 500,
                    height: 120,
                    redFrom: {{getMaxNumber($stats['total_conditions'])-20}},
                    redTo: {{getMaxNumber($stats['total_conditions'])}},
                    yellowFrom: {{getMaxNumber($stats['total_conditions'])-50}},
                    yellowTo: {{getMaxNumber($stats['total_conditions'])-20}},
                    minorTicks: 5
                };
            // Create and draw the visualization.
            new google.visualization.Gauge(document.getElementById('daily_gauge_chart_2')).draw(data, options);

            // Create and populate the data table.
            var data = google.visualization.arrayToDataTable([['Label', 'Value'],['Average', {{$stats['avg_conditions_per_order']}}]]);
            var options = {
                    max: <?php echo getMaxNumber($stats['avg_conditions_per_order']) ?>,
                    min: 1,
                    width: 500,
                    height: 120,
                    redFrom: {{getMaxNumber($stats['avg_conditions_per_order'])-20}},
                    redTo: {{getMaxNumber($stats['avg_conditions_per_order'])}},
                    yellowFrom: {{getMaxNumber($stats['avg_conditions_per_order'])-50}},
                    yellowTo: {{getMaxNumber($stats['avg_conditions_per_order'])-20}},
                    minorTicks: 5
                };
            // Create and draw the visualization.
            new google.visualization.Gauge(document.getElementById('daily_gauge_chart_3')).draw(data, options);

        }
        google.setOnLoadCallback(drawVisualization);
    </script>
@endpush









​
