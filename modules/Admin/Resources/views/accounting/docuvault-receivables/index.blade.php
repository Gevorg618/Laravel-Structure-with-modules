@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'DocuVault Receivables', 'url' => '#']
    ],
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="checked_all"></th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Orders</th>
                                    <th>< 60</th>
                                    <th>60 - 90</th>
                                    <th>90 - 120 </th>
                                    <th>120 + </th>
                                    <th>Past Due </th>
                                    <th>Total </th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                      <tr>
                                        <td><input type="checkbox" value="{{ $key }}" class="record_id_checkbox"></td>
                                        <td><a href="{{  route('admin.accounting.docuvault-receivables.show', [$key]) }}"> {{ $item['name'] }} </a></td>
                                        <td class="text-uppercase">{{ $item['type'] }}</td>
                                        <td>{{ $item['count'] }}</td>
                                        <td>{{ currency($item['stats']['totals']['60'] )  }}</td>
                                        <td>{{ currency($item['stats']['totals']['60-90'] ) }} </td>
                                        <td>{{ currency($item['stats']['totals']['90-120'] ) }} </td>
                                        <td>{{ currency( $item['stats']['totals']['120']) }} </td>
                                        <td>{{ currency( $item['stats']['totals']['pastdue'] )}} </td>
                                        <td>{{ currency($item['stats']['totals']['total']) }}  </td>
                                      </tr>
                                      @endforeach
                                </tbody>                                
                            </table>
                            <div class="form-group col-md-12 hidden danger-zone-revert">
                                <p>Count Selected Items  <b>(<span id="count_records"></span>)</b></p><br>
                                <button type="button" class="btn btn-primary show_orders">Show</button>
                            </div>
                        </div>                            
                    </div>                     
                </div>
            </div>
        </div>
    </div>    
@stop
@push('scripts')
    <script src="{{ masset('js/modules/admin/accounting/docuvault-receivables/index.js') }}"></script>
@endpush