@if(!empty($apprDataColl))
    <div class="tab_content">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @php $count = 0 @endphp
                        @foreach($apprDataColl as $value)
                            @if($count >= $cols)
                                </tr>
                                <tr>
                                @php $count = 0 @endphp
                            @endif
                                <td>{{'{qc.data'.$value['id'].'}'}}</td>
                                <td>{{$value['title']}}</td>
                            @php $count++ @endphp
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif
