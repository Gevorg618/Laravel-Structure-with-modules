<p>List all the API account subscriptions</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/subscribe/list|list_subscribers|Return all subscriptions associated to the API account|-|

				

<p class="text-info">Example Result</p>


#### Request:

```json
{baseurl}/subscribe/list/key/XXX
```

#### Result:

```json
{
	"b14cfc402136610ccbf1c01c27cbfb2bf5d2a1b6": {
		"id": "b14cfc402136610ccbf1c01c27cbfb2bf5d2a1b6",
		"url": "http://dev.com/r.php",
		"active": "1",
		"types": [
			"appraisal",
			"appraisal_log"
		]
	}
}
```							 
