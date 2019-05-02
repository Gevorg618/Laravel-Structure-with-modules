<p>By default you'll only be able to view the orders the client created using the API key used when creating the order.</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/list|list_orders|Get all orders created|-|

	
#### Request:

```json
{baseurl}/order/list/key/XXX
```

#### Result:

```json
[
	"200019370",
	"200019369",
	"200019368",
	"200019364",
	"200019363",
	"200019362",
	"200019361",
	"200019359",
	"200019358",
	"200019357",
	"200018547",
	"200018546",
	"200018532",
	"200018531",
	"200018530",
	"200018529",
	"200018527",
	"200018526",
	"141124461",
	"141124455"
]
```

<div>Filtering Orders - You can pass in 'ids' as a parameter to get the data for those orders. Either comma separated string or json object</div>

#### Request:

```json
{baseurl}/order/list/key/XXX?ids=200019370,200019369,200019368
{baseurl}/order/list/key/XXX?ids={"1":"200019370","2":"200019369","3":"200019368"}
```

#### Result:

```json
[
	"200019370",
	"200019369",
	"200019368"
]
```

### Additional Fields
<div class="alert alert-info">Additional Information - You can pass in 'fields' as a parameter to get additional order data in the response. Either comma separated string or json object</div>

#### Request:

```json
{baseurl}/order/list/key/XXX?fields=appr_type,status
{baseurl}/order/list/key/XXX?ids={"1":"appr_type","2":"status"}
```

#### Result:

```json
[
    {
        "id": "200042241",
        "appr_type": "1",
        "status": "10"
    },
    {
        "id": "200042226",
        "appr_type": "1",
        "status": "21"
    },
    {
        "id": "200042223",
        "appr_type": "1",
        "status": "8"
    }
]
```