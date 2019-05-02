<div>List all public visible log entries related to that order</div>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/logs/id/:id|view_order|Fetch order public visible log entries||


#### Returns

<div>On success will return an array of log entries that are public to the client</div>


#### Request:

```json
{baseurl}/order/logs/key/XXX/id/XX
```

#### Result:

```json
{
   "211507":{
      "id":"211507",
      "orderid":"141122745",
      "userid":"0",
      "type":"process",
      "created":"2012-10-03 15:47:36",
      "log":"Order Placed"
   }
}
```

<div>Will return an empty array if there are no log entries</div>