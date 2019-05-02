<div class="form-group">
    <label for="title" class="col-md-2 control-label">Title</label>
    <div class="col-md-10">
        {!! Form::text('title',
        isset($category) ? old('title', $category->title) : null,
            ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title']
        ) !!}
    </div>
</div>
<div class="form-group">
    <label for="is_active" class="col-md-2 control-label">Active</label>
    <div class="col-md-10">
        {!! Form::select('is_active', [0 => 'No', 1 => 'Yes'],
            isset($category) ? old('is_active', $category->is_active) : null,
            ['id' => 'is_active', 'class' => 'form-control', 'placeholder' => 'Choose activity']
        ) !!}
    </div>
</div>
<div class="form-group">
    <label for="client_correction" class="col-md-2 control-label">Order</label>
    <div class="col-md-10">
        {!! Form::text('ord',
        isset($category) ? old('ord', $category->ord) : null,
            ['id' => 'ord', 'class' => 'form-control', 'placeholder' => 'Order']
        ) !!}
    </div>
</div>
<hr>
<div class="form-group">
    <div class="col-md-10">
        <button type="submit" value="submit" name="submit" class="btn btn-primary">Save</button>
    </div>
</div>