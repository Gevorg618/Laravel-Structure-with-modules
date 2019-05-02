<p>Delete an existing subscription</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|DELETE|/subscribe/delete|delete_subscribers|Delete an existing subscription.|-|

				

<p class="text-info">Example Reqeust & Result</p>

#### Request:

```json
{baseurl}/subscribe/delete/key/XXX?id=<UNIQUE_HASH_ID>
```


#### Result:

```json
{"OK"}
```