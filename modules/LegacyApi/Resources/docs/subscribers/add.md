<p>Add a new subscription with a specific URL and a type</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|POST|/subscribe/add|add_subscribers|Add a new subscription URL with a specific type (type can be an array of multipel types in order to have the same URL as the endpoint for all updates)|-|

				

<p class="text-info">Example Reqeust & Result</p>

<div class="alert alert-warning">Currently the only supported types are: 'appraisal', 'appraisal_assign', 'appraisal_log' if you try to set any other type it'll return an error. it'll also return an error if you set a URL that does not resolve (make sure the URL you are trying to add already exists and resolves to POST requests.).</div>
							 

|Subscriber Key|Description|
|--- |--- |
|appraisal|Used when updates to an order are saved on Landscape instance. Subscribing to this will send a push notification every single time an order is updated. If multiple changes are performed within a short period of time there will be only one notification that is sent out to the subscriber. The default is within ~5 minutes. The notification will include all the updated and recent public accessible data for that order.|
|appraisal_assign|Used when an order is assigend to an AMC. Subscribing to this will send a push notification when an order is assigned to an AMC on the Landscape instance. Landscape instance must have an AMC user account present that is associated with the AMC company and the API account the AMC uses to integrate with Landscape. The notification will include all the updated and recent public accessible data for that order.|
|appraisal_log|Used when there are new log entries added to the order activity log. The push notification will include all the new public accessible log entries created since the last push notification. If multiple log entries were created it'll queue one push notification to be sent out with all the new log entries created within the timeframe which is ~5 minutes.|



#### Request:

```json
{baseurl}/subscribe/add/key/XXX?url=http://domain.com/a.php&types[]=appraisal&types[]=appraisal_log
```

#### Result:

```json
{
	"b14cfc402136610ccbf1c01c27cbfb2bf5d2a1b6": {
		"id": "b14cfc402136610ccbf1c01c27cbfb2bf5d2a1b6",
		"url": "http://domain.com/a.php",
		"active": "1",
		"types": [
			"appraisal",
			"appraisal_log"
		]
	}
}
```