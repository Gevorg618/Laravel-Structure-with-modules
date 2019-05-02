<p>View list of available order options specific to a certain user and the associated user group.</p>


|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/client-options|list_orders|Get all order client options|-|



#### Request:

```json
{baseurl}/order/client-options/key/XXX?user_email=test@test.com&user_password=<password>
```

<div class="alert alert-warning">
	<ul>
		<li>Lender ID that will need to be set to the 'lender_id' field</li>
		<li>if the lender is active in only specific states they will be listed here, then this lender must be used in one of the states set here</li>
		<li>if the lender operates with a different name in different states, the state=>name will be listed here</li>
		<li>-1 means that the group is also a lender, this option available only in that case</li>
	</ul>
</div>

#### Result:

```json
{
   "clientInfo":{
      "id":"xxxx",
      "name":"test@test.com"
   },
   "groupInfo":{
      "id":"xxx",
      "name":"Landmark Demo"
   },
   "lenders":{
      "55":{
         "title":"Landmark Network",
         "address":"3609 S. Wadsworth Blvd. S Suite 500",
         "city":"Lakewood",
         "state":"CO",
         "zip":"80235",
         "states":{
            "CA":"CA"
         },
         "customTitles":{
            "CA":" Landmark Network, CA"
         }
      },
      "-1":{
         "title":"Landmark Demo",
         "address":"4312 Woodman Ave. Suite 2",
         "city":"Sherman Oaks",
         "state":"CA",
         "zip":"91423",
         "states":[

         ],
         "customTitles":[

         ]
      }
   },
   "paymentOptions":{
      "pay-later":"Pay Later",
      "credit-card":"Credit Card",
      "cod":"COD",
      "invoiced":"Invoiced"
   }
}
```