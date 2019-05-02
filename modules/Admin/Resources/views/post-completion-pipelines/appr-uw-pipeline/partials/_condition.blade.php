<tr class="condition_tr" id="uw-conditions-{{$i}}">
    <td>
        <textarea name="condition_text[{{$i}}]" id="condition_text[{{$i}}]" data-id="{{$i}}" class="cond-text form-control" style="height: 100px; resize: vertical">{{$condition}}</textarea>
    </td>
    <td>
        <select id="condition_category[{{$i}}]" name="condition_category[{{$i}}]" class="cont-cat form-control">
            <option value="" selected="selected">-- Select --</option>
            @foreach($uwCategories as $UWCategory)
                <option value="{{$UWCategory->key}}" {{$UWCategory->key === $category ? 'selected="selected"' : ''}}>
                    {{$UWCategory->title}}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <textarea name="condition_response[{{$i}}]" id="condition_response[{{$i}}]"  style="height: 100px ; resize: vertical" class="cond-response form-control">{{$response}}</textarea>
    </td>
    <td>{{$name}}</td>
    <td>
        <button id="condition_{{$i}}" class="btn btn-xs btn-danger remove-condition">
            Remove
        </button>
    </td>
</tr>
