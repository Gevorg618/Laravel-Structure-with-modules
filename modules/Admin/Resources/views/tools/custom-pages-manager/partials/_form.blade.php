<div class="form-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="name" class="control-label col-lg-3 col-xs-12">Internal Name
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="control-label col-lg-3 col-xs-12">Page Title
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    <span class="help-block title-error-block"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="route" class="col-xs-12 text-center">Page Route</label>
                <div class="input-group col-md-8 col-md-offset-2">
                    <span class="input-group-addon" id="basic-addon3">{{ config('app.url') }}</span>
                    {!! Form::text('route', null, ['class' => 'form-control', 'aria-describedby' => 'basic-addon3']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-lg-3 col-xs-12">Description</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::textarea('description', null, ['class' => 'form-control']) }}
                    <span class="help-block description-error-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label name="keywords" class="col-lg-3 col-xs-12">Keywords</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::textarea('keywords', null, ['class' => 'form-control']) }}
                    <span class="help-block keywords-error-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="logo_slogan" class="col-lg-3 col-xs-12">Image Slogan</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::textarea('logo_slogan', null, ['class' => 'form-control']) }}
                    <span class="help-block logo_slogan-error-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="logo_description" class="col-lg-3 col-xs-12">Image Description</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::textarea('logo_description', null, ['class' => 'form-control']) }}
                    <span class="help-block logo_description-error-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="is_active" class="col-lg-3 col-xs-12">Active</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('is_active', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control selectpicker']) }}
                    <span class="help-block is_active-error-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="is_shown_in_menu" class="col-lg-3 col-xs-12">Is Shown In Menu</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('is_shown_in_menu', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control selectpicker']) }}
                    <span class="help-block is_active-error-block"></span>

                </div>
            </div>
            <div class="form-group">
                <label for="logo_title" class="col-lg-3 col-xs-12">Image Title</label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('logo_title', null, ['class' => 'form-control']) !!}
                    <span class="help-block logo_title-error-block"></span>
                </div>
            </div>
            <div class="form-group {{ $errors->has('logo_image') ? ' has-error' : '' }}">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <img src="{{isset($customPage) ? $customPage->customPageLogoImagePath() : ''}}" alt=""
                             id="logo_image_img_container" class="img-container">
                    </div>
                    <div class="col-lg-5 col-lg-offset-1 d-flex ">
                        {{ Form::label('logo_image', 'Image Load', ['class' => 'required btn btn-primary']) }}
                    </div>
                    <div class="col-md-6 col-md-offset-6">
                        <span class="help">Required Dimensions: Width: 1080px</span>
                    </div>
                    <div class="col-lg-12 col-xs-12">
                        {{ Form::file('logo_image', ['class' => 'form-control']) }}
                    </div>
                    @if ($errors->has('logo_image'))
                        <div class="col-md-12">
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$errors->first('logo_image')}}</strong>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group ">
                <label for="logo_image" class="control-label col-lg-3 col-xs-12">Page Content
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::textarea('content', null, ['class' => 'form-control ckeditor']) }}
                    <span class="help-block content-error-block"></span>
                </div>
                @if ($errors->has('content'))
                    <div class="col-md-12">
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$errors->first('content')}}</strong>
                            </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="ibox-footer">
            <button type="submit" class="btn btn-success pull-left">{{ $button_label }}</button>
        </div>
    </div>
</div>

@push('style')
    <link rel="stylesheet" href="{{ masset('css/front-end/index.css') }}">
@endpush

@push('scripts')
    <script src="{{masset('js/modules/admin/tools/custom-pages-manager/crud.js')}}"></script>
@endpush