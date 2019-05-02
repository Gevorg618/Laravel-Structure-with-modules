@extends('dashboard::layouts.master')

@section('title', 'Surveys')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>{{ $survey->title }}</h2>
        </div>
        <div class="col-lg-7">
            <div class="alert alert-info">
                Please take a few minutes to complete this short survey regarding your experience working with Landmark as we facilitated
                the completion of appraisal on the property listed below. Your feedback helps us improve our operation and appraiser panel
                so we may continue to offer you the best service possible.
            </div>
        </div>

        @if($order)
            <div>
                <div class="col-lg-7">
                    <div class="col-lg-6"><h3>Property Address: {{ $order->propaddress1 }}, {{ $order->propcity }}, {{ $order->propstate }}</h3></div>
                    <div class="col-lg-5 col-lg-offset-1"><h3>Date Ordered: {{ date('m/d/Y', strtotime($order->ordereddate)) }} </h3></div>
                </div>
                <div class="col-lg-7 ">
                    <div class="col-lg-6"><h3>Borrower: {{ $order->borrower }}</h3></div>
                    <div class="col-lg-5 col-lg-offset-1"><h3>Date Delivered: {{ date('m/d/Y', strtotime($order->date_delivered)) }}</h3></div>
                </div>
            </div>

        @endif

        <div class="col-lg-7">
            @if(!$survey->error)

                {{ Form::open(['route' => ['dashboard.surveys.submit', $survey->id, (!empty($order) ? $order->id : '')], 'method' => 'post', 'class' => 'form-horizontal']) }}

                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please populate following survey</h3>
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @foreach($survey->questions AS $question)
                                                <div class="form-group">
                                                    <div class="col-lg-6 col-xs-6">
                                                        {{ Form::label('question-'.$question->id, $question->title, ['class' => 'col-lg-12 col-xs-12 '.(($question->is_required) ? 'required' : '')]) }}
                                                        @if($question->description)
                                                            <div class="col-md-12">
                                                                <i>Note: {{ $question->description }}.</i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-5 col-xs-5 col-lg-offset-1">
                                                        @if($question->type == 'textarea')
                                                            {{ Form::textarea('question-'.$question->id, old('question-'.$question->id), ['class' => 'form-control', 'rows' => '3']) }}
                                                        @elseif($question->type == 'rating')
                                                            <fieldset class="rating">
                                                                @for($i = $question->rating_items; $i >= 1; $i--)
                                                                    <input type="radio" id="star{{ $question->id }}-{{ $i }}" name="question-{{ $question->id }}" value="{{ $i }}" {{ (old('question-'.$question->id) == $i) ? 'checked=checked' : '' }} /><label class="full" for="star{{ $question->id }}-{{ $i }}" title="{{ $i }}"></label>
                                                                @endfor
                                                            </fieldset>
                                                        @elseif($question->type == 'textfield')
                                                            {{ Form::input('question-'.$question->id, 'question-'.$question->id, old('question-'.$question->id), ['class' => 'form-control']) }}
                                                        @elseif($question->type == 'yesno')
                                                            {{ Form::select('question-'.$question->id, ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) }}
                                                        @endif
                                                    </div>
                                                </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="ibox-footer">
                                        <button class="btn btn-success pull-right">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        @else
            {{ $survey->error }}
        @endif
    </div>
@stop

@push('scripts')
<script>
    $(function() {
//        console.log($('input[name^="question-"]:checked').val());
    });

</script>
@endpush
