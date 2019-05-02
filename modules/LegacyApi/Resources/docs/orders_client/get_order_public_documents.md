<div class="alert alert-info">List all public accessible documents related to that order</div>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/files/id/:id|view_order|Fetch order public documents||


#### Returns

<div class="alert alert-success">On success will return the list of documents that are public to the client</div>


#### Request:

```json
{baseurl}/order/files/key/XXX/id/XX
```

#### Result:

```json
[
   {
      "name":"XXX.xml",
      "length":3977.32,
      "base64":"BASE_64_ENCODED_STRING"
   },
   {
      "name":"test document",
      "length":30,
      "base64":"BASE_64_ENCODED_STRING"
   }
]
```

<div class="alert alert-warning">Will return an empty array if there are no files</div>