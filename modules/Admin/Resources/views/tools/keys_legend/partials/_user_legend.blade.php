@if(!empty($userLegend))
    <div class="tab_content">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userLegend as $key => $value)
                        <tr>
                            <td>{{'{'.$key.'}'}}</td>
                            <td>{{$value}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
