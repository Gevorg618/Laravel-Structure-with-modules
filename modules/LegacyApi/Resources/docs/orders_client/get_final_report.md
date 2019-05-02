<div class="alert alert-info">By default you'll only be able to view the orders appraisal file if the order has one</div>

## Final Report - PDF

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/appraisal/id/:id|view_order|Fetch order appraisal document||



#### Returns

<div class="alert alert-success">On success will return the appraisal final report filename and base64 encoded string of the file content</div>


#### Request:

```json
{baseurl}/order/appraisal/key/XXX/id/XX
```

#### Result:

```json
{
   "filename":"XXX_Appraisal.pdf",
   "base64":"BASE_64_ENCODED_STRING"
}
```

<div class="alert alert-success">On failure will return a json object stating the error code and message</div>

```json
{
   "error":"Appraisal for that order was not found.",
   "code":414
}
```


## Final Report - XML


|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/appraisalxml/id/:id|view_order|Fetch order appraisal XML document||



#### Returns

<div class="alert alert-success">On success will return the appraisal final report filename and base64 encoded string of the file content</div>


#### Request:

```json
{baseurl}/order/appraisalxml/key/XXX/id/XX
```

#### Result:

```json
{
   "filename":"XXX_Appraisal.xml",
   "base64":"BASE_64_ENCODED_STRING"
}
```

<div class="alert alert-danger">On failure will return a json object stating the error code and message</div>

```json
{
   "error":"Appraisal XML for that order was not found.",
   "code":414
}
```