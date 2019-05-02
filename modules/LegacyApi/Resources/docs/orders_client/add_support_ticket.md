<div class="alert alert-info">Add a support ticket to an order</div>

### Client Support Ticket


|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|POST|/order/inquiry/id/:id/from/myemail@domain.com/message/XXXX|view_order|Create a new support inquiry for an order||



#### Returns

<div class="alert alert-success">On success will return a string 'Inquiry Created'.</div>


#### Request:

```json
{baseurl}/order/inquiry/key/XXX/id/XX/from/myemail@domain.com/message/XXXX
```

#### Result:

```json
["Inquiry Created."]
```

<div class="alert alert-danger">On failure will return a json object stating the error code and message</div>