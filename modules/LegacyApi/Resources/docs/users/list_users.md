### List Users

By default you'll only be able to view the users you've created using the API key used when creating the user.

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/user/list|list_users|Get all users created|-|


#### Request: 

```
json {baseurl}/user/list/key/XXX
```

#### Result: 

```json
{
   "122166":{
      "id":"122166",
      "email":"XXXX",
      "register_date":"2012-08-03 16:32:00",
      "active":1,
      "groupid":"0",
      "notes":"",
      "address":"1160 Parsippany Blvd, Suite B",
      "city":"Parsippany",
      "state":"NJ",
      "zip":"7054",
      "firstname":"XXX",
      "lastname":"XXX",
      "phone":"",
      "mobile":"",
      "fax":"",
      "company":"Maverick Funding Corporation",
      "comp_address":"Maverick Funding Corporation 1160 Parsippany Blvd, Suite B",
      "comp_city":"Parsippany",
      "comp_state":"NJ",
      "comp_zip":"7054"
   },
   "122167":{
      "id":"122167",
      "email":"XXXX",
      "register_date":"2012-08-03 16:32:00",
      "active":1,
      "groupid":"0",
      "notes":"",
      "address":"1160 Parsippany Blvd, Suite B",
      "city":"Parsippany",
      "state":"NJ",
      "zip":"7054",
      "firstname":"XXXX",
      "lastname":"XXXX",
      "phone":"",
      "mobile":"",
      "fax":"",
      "company":"Maverick Funding Corporation",
      "comp_address":"Maverick Funding Corporation 1160 Parsippany Blvd, Suite B",
      "comp_city":"Parsippany",
      "comp_state":"NJ",
      "comp_zip":"7054"
   },
   "122168":{
      "id":"122168",
      "email":"XXXX",
      "register_date":"2012-08-03 16:32:00",
      "active":1,
      "groupid":"0",
      "notes":"",
      "address":"1160 Parsippany Blvd, Suite B",
      "city":"Parsippany",
      "state":"NJ",
      "zip":"7054",
      "firstname":"XXXX",
      "lastname":"XXXX",
      "phone":"",
      "mobile":"",
      "fax":"",
      "company":"Maverick Funding Corporation",
      "comp_address":"Maverick Funding Corporation 1160 Parsippany Blvd, Suite B",
      "comp_city":"Parsippany",
      "comp_state":"NJ",
      "comp_zip":"7054"
   },
   "122169":{
      "id":"122169",
      "email":"XXXX",
      "register_date":"2012-08-03 16:32:00",
      "active":1,
      "groupid":"0",
      "notes":"",
      "address":"1160 Parsippany Blvd, Suite B",
      "city":"Parsippany",
      "state":"NJ",
      "zip":"7054",
      "firstname":"XXXX",
      "lastname":"XXXX",
      "phone":"",
      "mobile":"",
      "fax":"",
      "company":"Maverick Funding Corporation",
      "comp_address":"Maverick Funding Corporation 1160 Parsippany Blvd, Suite B",
      "comp_city":"Parsippany",
      "comp_state":"NJ",
      "comp_zip":"7054"
   }
}
```