@extends('admin::layouts.master')

@section('title', 'Settings Manager - ' . $row->title)

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'Settings Manager', 'url' => route('admin.tools.settings')],
  ['title' => $row->title, 'url' => '']
],
'actions' => [
  ['title' => 'Add Category', 'url' => route('admin.tools.settings')],
  ['title' => 'Add Setting', 'url' => route('admin.tools.settings')]
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        {{ Form::model($row, ['route' => ['admin.tools.settings.category.view', 'id' => $row->id], 'class' => 'form-horizontal']) }}

                        @foreach($row->settings as $setting)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="col-lg-3">{{ $setting->title }}</label>
                                        <div class="col-lg-8">
                                            @if($setting->type == 'textarea')
                                                {{ Form::textarea('settings['.$setting->id.']', $setting->getValue(), ['class' => 'form-control']) }}
                                            @elseif($setting->type == 'dropdown')
                                                {{ Form::select('settings['.$setting->id.']', $setting->getSettingDropDownOptions()->prepend('-- Select --', ''), $setting->getValue(), ['class' => 'form-control hidden-until-ready hidden']) }}
                                            @elseif($setting->type == 'checkbox')
                                                {{ Form::hidden('settings['.$setting->id.']', 0) }}
                                                @foreach($setting->getSettingDropDownOptions() as $key => $value)
                                                    {{ Form::hidden('settings['.$setting->id.']['.$key.']', 0) }}
                                                    <div class="i-checks"><label>
                                                            {{ Form::checkbox('settings['.$setting->id.']['.$key.']', $value, $setting->isChecked($key)) }}
                                                            <i></i> {{$value}}
                                                        </label></div>
                                                @endforeach
                                            @elseif($setting->type == 'radio')
                                                {{ Form::hidden('settings['.$setting->id.']', 0) }}
                                                @foreach($setting->getSettingDropDownOptions() as $key => $value)
                                                    {{ Form::hidden('settings['.$setting->id.']', 0) }}
                                                    <div class="i-checks"><label>
                                                            {{ Form::radio('settings['.$setting->id.']', $value, $key == $setting->getValue()) }}
                                                            <i></i> {{$value}}
                                                        </label></div>
                                                @endforeach
                                            @elseif($setting->type == 'date')
                                                <div class="input-group date">
                        <span class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </span>
                                                    {{ Form::text('settings['.$setting->id.']', $setting->getValue(), ['class' => 'form-control date-picker']) }}
                                                </div>
                                            @elseif($setting->type == 'datetime')
                                                <div class="input-group date">
                        <span class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </span>
                                                    {{ Form::text('settings['.$setting->id.']', $setting->getValue(), ['class' => 'form-control date-time-picker']) }}
                                                </div>
                                            @elseif($setting->type == 'multi')
                                                {{ Form::select('settings['.$setting->id.'][]', $setting->getSettingDropDownOptions(), $setting->getSelectedItems(), ['class' => 'form-control select hidden-until-ready hidden', 'multiple' => 'multiple']) }}
                                            @elseif($setting->type == 'yesno')
                                                <div class="i-checks"><label>
                                                        {{ Form::radio('settings['.$setting->id.']', 1, $setting->getValue()) }}
                                                        <i></i> Yes
                                                    </label></div>
                                                <div class="i-checks"><label>
                                                        {{ Form::radio('settings['.$setting->id.']', 0, !$setting->getValue()) }}
                                                        <i></i> No
                                                    </label></div>
                                            @elseif($setting->type == 'editor')
                                                {{ Form::textarea('settings['.$setting->id.']', $setting->getValue(), ['class' => 'form-control summernote']) }}
                                            @else
                                                {{ Form::text('settings['.$setting->id.']', $setting->getValue(), ['class' => 'form-control']) }}
                                            @endif
                                            <span class="help-block m-b-none">{{ $setting->description }}</span>
                                        </div>
                                        <div class="col-lg-1">
                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-success dropdown-toggle">
                                                    Options <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Edit</a></li>
                                                    @if($setting->value != $setting->default_value)
                                                        <li><a href="#">Revert</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row">
                            <button class="btn btn-primary pull-right">Save Changes</button>
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop