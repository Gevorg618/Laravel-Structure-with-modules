<div class="col-md-12">
    <div class="col-md-6" style="text-align: -webkit-center;">
        <div><b>Orders Per User</b></div>
        <div id="daily_pie_admins"></div>
    </div>
    <div class="col-md-6" style="text-align: -webkit-center;">
        <div><b>Orders Approved First Time</b></div>
        <div id="daily_pie_first"></div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
        function drawVisualization() {
            @php
                $list = "['Admin', 'Orders'],";
                foreach($daily['list'] as $userId => $data) {
                    $list .= "['".$data['user_name']."', ".$data['total']."],";
                }
                $list = trim($list, ',');
            @endphp

            @php
                $listFirst = "['Admin', 'Orders'],";
                foreach($daily['list'] as $userId => $data) {
                    $listFirst .= "['".$data['user_name']."', ".$data['first']."],";
                }
                $listFirst = trim($listFirst, ',');
            @endphp

            // Create and populate the data table.
            var data = google.visualization.arrayToDataTable([{!!$list!!}]);
            var dataFirst = google.visualization.arrayToDataTable([{!!$listFirst!!}]);

            // Create and draw the visualization.
            new google.visualization.PieChart(document.getElementById('daily_pie_admins')).draw(data, {pieHole: 0.23});
            new google.visualization.PieChart(document.getElementById('daily_pie_first')).draw(dataFirst, {pieHole: 0.23});
        }
        google.setOnLoadCallback(drawVisualization);
    </script>
@endpush




