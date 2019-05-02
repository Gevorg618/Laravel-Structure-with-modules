@extends('admin::layouts.master')

@section('title', 'Sale Taxes')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Customizations', 'url' => '#'],
      ['title' => 'Sale Taxes', 'url' => route('admin.management.sale.tax.index')],
      ['title' => 'New Sale Tax', 'url' => '']
    ]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ isset($state) ? 'Updating' : 'Creating' }}</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="exampleInputName2">Set value for all counties</label>
                                    <input type="text" class="form-control" id="valueGeneral" placeholder="1.00">
                                    <button class="form-control" id="setForAll">Set for all</button>
                                </div>
                            </form>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                    {{ Form::model($saleTax, ['route' => [isset($state) ? 'admin.management.sale.tax.update' : 'admin.management.sale.tax.create', 'state' => $saleTax->state], 'class' => 'form-horizontal', 'id' => 'stateForm']) }}
                    @if(isset($state))
                        {{ method_field('PUT') }}
                        {{ Form::hidden('state',$state) }}

                    @else
                        <div class="col-md-12 form-inline">
                            <div class="form-group">
                                <label for="state">Select state</label>
                                {{Form::select('state', getStates(), null, ['class' => 'form-control', 'id' => 'stateSelect'])}}
                            </div>
                            <p>&nbsp;</p>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(isset($state))
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin::management.sale-tax.partials._counties', compact('counties', 'taxes', 'state'))
                        </div>
                    </div>
                    @else
                        <div id="counties"></div>
                    @endif
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#setForAll').click(function(){
            var valueGeneral = $('#valueGeneral').val();
            if(valueGeneral.length > 0) {
                $('.county').each(function(){
                    $(this).val(valueGeneral);
                });
            }else {
                alert('Value not set');
            }
            return false;
        });
    });

    function getCounties(state) {
        $.ajax({
            url: '{{route('admin.management.sale.tax.counties')}}',
            data: {state: state}
        }).done(function(data){
            $('#counties').html(data);
        });
    }

    getCounties($('#stateSelect').val());

    $('#stateSelect').change(function(){
        var state = $(this).val();
        getCounties(state);
    });
</script>
@endpush