@extends('admin::layouts.master')

@section('title', 'Appraisal UW Checklist')

@component('admin::layouts.partials._breadcrumbs', [
  'crumbs' => [
    ['title' => 'Appraisal', 'url' => '#'],
    ['title' => 'Appraisal UW Checklist', 'url' => route('admin.appraisal.under-writing.checklist')]
  ],
  'actions' => [
    ['title' => 'Add Category', 'url' => route('admin.appraisal.under-writing.checklist.category.create')],
    ['title' => 'Add Question', 'url' => route('admin.appraisal.under-writing.checklist.question.create')],
  ]
])
@endcomponent

@section('content')
    <div class="row" id="uw-category">
        @foreach($categories as $category)
            <div class="col-md-offset-1 col-lg-10">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ $category->title }}</h5>
                        <div class="ibox-tools">
                            <button class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
                                Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('admin.appraisal.under-writing.checklist.question.create') }}?category={{ $category->id }}">Add
                                        Question</a></li>
                                <li>
                                    <a href="{{ route('admin.appraisal.under-writing.checklist.category.create',$category->id) }}">Edit
                                        Category</a></li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="{{ route('admin.appraisal.under-writing.checklist.category.active-inactive',$category->id) }}">
                                        {{ ($category->is_active)?'Make Inactive':'Make Active' }}
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                    <a href="{{ route('admin.appraisal.under-writing.checklist.category.delete',$category->id) }}">
                                        Delete &nbsp<i class="fa fa-trash"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                @if(count($category->questions))
                                    @foreach($category->questions as $question)
                                        @include('admin::appraisal.under-writing.checklist.partials._questions')
                                    @endforeach
                                @else
                                    <p class="text-center text-muted">No Questions Found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

@push('scripts')

@endpush