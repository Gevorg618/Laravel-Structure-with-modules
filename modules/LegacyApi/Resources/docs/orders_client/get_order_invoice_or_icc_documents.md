<div class="alert alert-info">By default you'll only be able to view the orders ICC or Invoice documents if the order has one</div>

## ICC Document

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/icc-cert/id/:id|view_order|Fetch order icc certification document||



#### Returns

<div class="alert alert-success">On success will return the document filename and base64 encoded string of the file content</div>


#### Request:

```json
{baseurl}/order/icc-cert/key/XXX/id/XX
```

#### Result:

```json
{
   "filename":"XXX_ICC_Document.pdf",
   "base64":"BASE_64_ENCODED_STRING"
}
```

<div class="alert alert-danger">On failure will return a json object stating the error code and message</div>

```json
{
   "error":"ICC Certification for that order was not found.",
   "code":415
}
```



## Invoice Document

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/invoice/id/:id|view_order|Fetch order invoice document||


#### Returns

<div class="alert alert-success">On success will return the document filename and base64 encoded string of the file content</div>


#### Request:

```json
{baseurl}/order/invoice/key/XXX/id/XX
```

#### Result:

```json
{
   "filename":"XXX_Invoice.pdf",
   "base64":"BASE_64_ENCODED_STRING"
}
```

<div class="alert alert-danger">On failure will return a json object stating the error code and message</div>

```json
{
   "error":"Invoice Document for that order was not found.",
   "code":415
}
```

