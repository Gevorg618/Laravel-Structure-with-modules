<p>Update an existing subscription</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|PUT|/subscribe/update|update_subscribers|Update an existing subscription with a different url or types set.|-|

				

<p class="text-info">Example Reqeust & Result</p>
							 
						
<div class="alert alert-warning">When setting the URL and Type it'll overwrite the current values. If you are trying to add a new type to the list make sure to pass ALL types you'd like the subscription record to have.</div>

#### Request:

```json
{baseurl}/subscribe/update/key/XXX?id=<UNIQUE_HASH_ID>&url=http://domain.com/b.php&types[]=appraisal_log
```


#### Result:

```json
{
	"b14cfc402136610ccbf1c01c27cbfb2bf5d2a1b6": {
		"id": "b14cfc402136610ccbf1c01c27cbfb2bf5d2a1b6",
		"url": "http://domain.com/b.php",
		"active": "1",
		"types": [
			"appraisal_log"
		]
	}
}
```