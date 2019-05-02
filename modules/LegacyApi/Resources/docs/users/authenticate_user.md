<p>Check for email address/password combination against our database</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/user/auth|view_user|Check user email address password combination|-|


<strong>On failure will return a json object stating the error code and message</strong>
	
<strong>Password must be plain text password.</strong>



#### Request:

```json
{baseurl}/user/auth/key/XXX/email/admin@landmarknetwork.com/password/XXXXXX
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
{baseurl}/user/auth/key/XXX/id/1/password/XXXXXX
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

