<p>By default you'll only be able to view the users you've created using the API key used when creating the user.</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/user/:id|view_user|Fetch user information by user id|/user/view/id/:id|
|GET|/user/:email|view_user|Fetch user information by user email address|/user/view/email/:id|

#### Request:

```json
{baseurl}/user/1/key/XXX
```

#### Result:

```json
{
   "id":"1",
   "email":"admin@landmarknetwork.com",
   "register_date":"2007-03-01 18:01:03",
   "active":1,
   "groupid":"0",
   "notes":"",
   "address":"",
   "city":"",
   "state":"",
   "zip":"",
   "firstname":"Admin",
   "lastname":"User",
   "phone":"(818) 2721214",
   "mobile":"",
   "fax":"",
   "type":"Admin",
   "company":"Landmark Network",
   "comp_address":"Landmark Network 4312 Woodman Ave",
   "comp_city":"Sherman Oaks",
   "comp_state":"CA",
   "comp_zip":"91423"
}
```


#### Request:

```json
{baseurl}/user/admin@landmarknetwork.com/key/XXX
```

#### Result:

```json
{
   "id":"1",
   "email":"admin@landmarknetwork.com",
   "register_date":"2007-03-01 18:01:03",
   "active":1,
   "groupid":"0",
   "notes":"",
   "address":"",
   "city":"",
   "state":"",
   "zip":"",
   "firstname":"Admin",
   "lastname":"User",
   "phone":"(818) 2721214",
   "mobile":"",
   "fax":"",
   "type":"Admin",
   "company":"Landmark Network",
   "comp_address":"Landmark Network 4312 Woodman Ave",
   "comp_city":"Sherman Oaks",
   "comp_state":"CA",
   "comp_zip":"91423"
}
```