<h2 class="col-md-12"> 
    <div class="col-lg-12 col-xs-12">
        Update Task
    </div>        
</h2>
{!! Form::model($task, ['route' => ['admin.reports.tasks.update', $task->id], 'id' => 'form-rcreate-task',  'enctype' => "multipart/form-data"]) !!}
<div class="form-group col-md-6">
    <label name="title" class="control-label col-lg-3 col-xs-12"> Task Title
        <span class="required" aria-required="true"></span>
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>                                        
</div>

<div class="form-group col-md-6">
    <label name="subject" class="control-label col-lg-3 col-xs-12"> Email Subject
        <span class="required" aria-required="true"></span>
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>                                        
</div>

<div class="form-group col-md-6">
    <label name="description" class="control-label col-lg-3 col-xs-12"> Task Description
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::textarea('description', $task->task_description, ['class' => 'form-control']) !!}
    </div>                                        
</div>

<div class="form-group col-md-6">
    <label name="emails" class="control-label col-lg-3 col-xs-12"> Task Emails
        <span class="required" aria-required="true"></span>
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::textarea('emails', $task->task_emails, ['class' => 'form-control', 'data-role' => "tagsinput", 'placeholder' => 'Enter one email address per line']) !!}
    </div>                                        
</div>
<div class="form-group col-md-6">
    <label name="filename" class="control-label col-lg-3 col-xs-12"> File Name
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('filename', $task->file_name, ['class' => 'form-control']) !!}
    </div>                                        
</div>

<div class="form-group col-md-6">
    <label name="client" class="control-label col-lg-3 col-xs-12"> Date Range 
        <span class="required" aria-required="true"></span>
        <a href="#" data-toggle="tooltip" title="Choose the date range to run the report with at the time when the task runs">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('daterange', ['day' => 'Current Day',  'week' => 'Current Week',  'month' => 'Current Month'], $task->date_range, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group col-md-6">
    <label name="date_range_num" class="control-label col-lg-3 col-xs-12">Enter Days
        <a href="#" data-toggle="tooltip" title="If you enter a number of days (number that is larger then 0) the Date Range dropdown value will be ignored (if selected)">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('date_range_num', intval($task->date_range) > 0 ? $task->date_range : null, ['class' => 'form-control']) !!}
    </div>                                       
</div>

<div class="form-group col-md-6">
    <label name="days_prior" class="control-label col-lg-6 col-xs-12">Ordered Date And Prior To 
        <a href="#" data-toggle="tooltip" title="If you enter a number of days (number that is larger then 0) the Date Range dropdown and the Number Of Days (field above) values will be ignored (if selected)">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('days_prior', null, ['class' => 'form-control']) !!}
    </div>                                       
</div>
<div class="form-group col-md-6">
    <label name="active" class="control-label col-lg-3 col-xs-12"> Active 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('active', ['1' => 'Yes', '0' => 'No'], $task->task_enabled, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group col-md-6">
    <label name="task_file" class="control-label col-lg-3 col-xs-12"> Task File 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('task_file', ['weekly_new_clients.php' => 'Weekly New Clients'], null, ['class' => 'form-control']) }}
    </div>
</div>

<h2 class="col-md-12"> 
    <div class="col-lg-12 col-xs-12">
            Details
    </div>        
</h2>

@if(isset($data['clients']) && $data['clients'] != '')
<div class="form-group col-md-3" >
    <div class="card col-lg-12 col-xs-12"" >
          <div class="card-header" style="font-weight: bolder;">
                <i>Clients</i>
          </div>
          <ul class="list-group list-group-flush" style='background-color: cornsilk;'>
            @foreach($data['clients'] as $client)
                <li class="list-group-item">{{ $client }}</li>
            @endforeach
          </ul>
    </div>
</div>
@endif

@if(isset($data['apprTypes']) && $data['apprTypes'] != '')
<div class="form-group col-md-3">
    <div class="card col-lg-12 col-xs-12"" >
      <div class="card-header" style="font-weight: bolder;" >
            <i>Types</i>
      </div>
          <ul class="list-group list-group-flush" style='background-color: cornsilk;'>
            @foreach($data['apprTypes'] as $type)
            <li class="list-group-item">{{ $type }}</li>
            @endforeach
          </ul>
    </div>
</div>
@endif

@if(isset($data['states']) && $data['states'] != '')
<div class="form-group col-md-3">
    <div class="card col-lg-12 col-xs-12"" >
      <div class="card-header" style="font-weight: bolder;" >
            <i>States</i>
      </div>
          <ul class="list-group list-group-flush" style='background-color: cornsilk;' >
            @foreach($data['states'] as $state)
                <li class="list-group-item">{{ $state }}</li>
            @endforeach
          </ul>
    </div>
</div>
@endif

@if(isset($data['status']) && $data['status'] != '')
<div class="form-group col-md-3">
    <div class="card col-lg-12 col-xs-12"" >
      <div class="card-header" style="font-weight: bolder;">
            <i>Status</i>
      </div>
          <ul class="list-group list-group-flush" style='background-color: cornsilk;'>
            <li class="list-group-item">{{ $data['status']  }}</li>
          </ul>
    </div>
</div>
@endif

@if(isset($data['team']) && $data['team'] != '')
<div class="form-group col-md-3">
    <div class="card col-lg-12 col-xs-12"" >
      <div class="card-header" style="font-weight: bolder;">
            <i>Team</i>
      </div>
          <ul class="list-group list-group-flush" style='background-color: cornsilk;' >
            <li class="list-group-item">{{ $data['team'] }}</li>
          </ul>
    </div>
</div>
@endif
<h2 class="col-md-12"> 
    <div class="col-lg-12 col-xs-12">
            Task Times
    </div>        
</h2>

<div class="form-group col-md-3">
    <label name="minutes" class="control-label col-lg-6 col-xs-12">  Minutes 
        <a href="#" data-toggle="tooltip" title="Choose 'Every Minute' to run each minute. Choose a minute but no hour to run every x minutes. Choose a minute and an hour to run at a specific time.">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('minutes', $m, $task->minute, ['class' => 'form-control', 'id' => 'minutes']) }}
    </div>
</div>

<div class="form-group col-md-3">
    <label name="hours" class="control-label col-lg-6 col-xs-12">  Hours 
        <a href="#" data-toggle="tooltip" title="Choose 'Every Hour' to run on every hour. Choose an hour but no minute to run every x hours. Choose a minute and an hour to run at a specific time.">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('hours', $h, $task->hour, ['class' => 'form-control', 'id' => 'hours']) }}
    </div>
</div>

<div class="form-group col-md-3">
    <label name="weekday" class="control-label col-lg-6 col-xs-12">  Week Day 
        <a href="#" data-toggle="tooltip" title="Choose 'Every Day' to run each day or a week day for a specific week day of a month">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('weekday', $w, $task->task_week_day, ['class' => 'form-control', 'id' => 'weekday']) }}
    </div>
</div>

<div class="form-group col-md-3">
    <label name="monthday" class="control-label col-lg-6 col-xs-12">  Month Day 
        <a href="#" data-toggle="tooltip" title="Choose 'Every Day' to run each day or a month day for a specific month day of a month">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('monthday', $mD, $task->task_month_day, ['class' => 'form-control', 'id' => 'monthday']) }}
    </div>
</div>

<div class="form-group col-md-3">
    <label name="weekends" class="control-label col-lg-6 col-xs-12"> Exclude Weekends
        <a href="#" data-toggle="tooltip" title="Choose if you would like to stop this task from running on weekends (Saturday & Sunday)">?</a> 
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('weekends', ['1' => 'Yes', '0' => 'No'], $task->task_weekends, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group col-md-6">
    <label name="runat" class="control-label col-lg-6 col-xs-12">Run At
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('runat', 'Every Minute', ['class' => 'form-control', 'id' => 'runat', 'readonly']) !!}
    </div>                                       
</div>

<h2 class="col-md-12"> 
    <div class="col-lg-12 col-xs-12">
        Email Message
    </div>        
</h2>

<div class="form-group col-md-12 ">
    <span class="required" aria-required="true"></span>
    <div class="col-lg-12 col-xs-12">
        {{ Form::textarea('content', null, ['class' => 'form-control']) }}
        <span class="help-block content-error-block"></span>
    </div>
</div>

<div style="display:none;">
    <textarea name='task_data'>{{ serialize($data) }}</textarea>
</div>

<div class="form-group col-md-12">
    <div class="col-lg-6 col-xs-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-danger cancel_task">Cancel</button>            
    </div>
</div>
{!! Form::close() !!}