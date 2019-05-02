<tr class="appr_contact_tr">
    <td>
        <input type="text" name="contact_name[{{$i}}]" class="form-control" value="{{$name}}" />
    </td>
    <td>
        <input type="text" name="contact_email[{{$i}}]" class="form-control" value="{{$email}}" />
    </td>
    <td>
        <button type="button" name="contact_add_{{$i}}" id="contact_add_{{$i}}" class="btn btn-xs btn-danger remove-client">Remove</button>
    </td>
</tr>
