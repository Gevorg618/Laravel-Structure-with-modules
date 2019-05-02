|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|POST|/order/upload-final-report|view_order|Upload the final report to an appraisal order|-|


### Required Fields

<div class="alert alert-warning">Files submitted should be either XML/PDF types. Some appraisal types will require XML.</div>

|Name|Description|Notes|
|--- |--- |--- |
|id|The order ID this file will be associated with|Must be a valid order ID that exists and the current API user has access to.|
|name|File name|In plain text no extension. For example: 'appraisal', 'purchase_contract' etc...|
|type|valid extension of the file uploaded|just the file extension no period. For example: 'pdf', 'jpg', 'doc'. (Allowed extensions: 'pdf', 'doc', 'docx', 'txt', 'rtf', 'jpg', 'jpeg', 'png', 'gif')|
|user_email|Vendor email address|Must exist in the system and assinged to the order you are attemting to upload the final report to|
|user_password|plain text password|Must be plain text and transmitted over HTTPS..|
|content|the content of the file base64 encoded.|The contents of the document must be base64 encoded prior to passing it to the API. You also need to make sure to make it uri-safe. Since base64 encoded strings contain (+,/,=) you will need to replace those with (-,_,.) respectively.Example in PHP below. You must use the above replacements for the document to work properly.|


```php
$content = file_get_contents('file.pdf');
$content = base64_encode($content);
$content = str_replace(array('+', '/', '='), array('-', '_', '.'), $content);

// Send content over HTTP
....
```

#### Returns

<div class="alert alert-success">On success will return the newly created order information (filename)</div>


#### Request:

```json
{baseurl}/order/upload-final-report/key/XXX/id/14154083/...
```

#### Result:

```json
{"filename":"81e92_pdfdocument.pdf"}
```

<div class="alert alert-danger">On failure will return a json object stating the error code and message</div>

#### Request:

```json
{baseurl}/order/upload-final-report/key/XXX/id/14154083/...
```

#### Result:

```json
{
   "readyState":4,
   "responseText":"{\"error\":\"Order Document content must be base64 encoded. and the following characters (+,\\/,=) must be replaced with (-,_,.) respectively.\",\"code\":423}",
   "status":400,
   "statusText":"Bad Request"
}
```
