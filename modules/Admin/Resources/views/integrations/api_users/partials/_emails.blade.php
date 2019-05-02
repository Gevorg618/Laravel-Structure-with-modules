<div class="row">
    <div class="col-md-12">
        @if($settings)
            @foreach($settings as $setting)

            @endforeach
        @endif
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{json_encode($setting->title)}}</h3>
          </div>
          <div class="panel-body">
            @if($setting->description)
                <div class="alert alert-info">{{$setting->description}}</div>
            @endif
            <div class="form-group">
                <div class="col-md-12">
                    <textarea name="emailsetting[{{$setting->id}}]" class="editor">
                        {{ isset($apiUser) ? (!is_null(getAPIEmailContentByKey($apiUser->id, $setting->id)) ? getAPIEmailContentByKey($apiUser->id, $setting->id) : $setting->default_content) : $setting->default_content}}
                    </textarea>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

