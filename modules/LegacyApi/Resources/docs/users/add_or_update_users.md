### Add Or Update Users

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|POST|/user/add|add_user|Create New User|-|
|POST|/user/update|update_user|Update user information|-|

### Required Fields

|Name|Description|Notes|
|--- |--- |--- |
|email|User email address|Must be a unique email address. Will error if the email address already exists|
|password|User password|Must be plain text do not specify hashed password|
|groupid|User Group|integer - must be one of the allowed user groups id associated with the API Account. See @groups|
|firstname|User first name|plain text|
|lastname|User last name|plain text|
|phone|Phone number|plain text|
|company|User company name|plain text|
|comp_address|User company address|plain text|
|comp_city|User company city|plain text|
|comp_state|User company state|plain text|
|comp_zip|User zipcode|plain text|


### Optional Fields

|Name|Description|Notes|
|--- |--- |--- |
|notes|User Notes|plain text|
|user_type|User Type|plain text , Set either 'client' or 'appraiser' if not set defaults to 'client'|
|mobile|Mobile|plain text|
|fax|Fax|plain text|
|address|Address|plain text|
|city|City|plain text|
|state|State|plain text|
|zip|Zip|plain text|


## Add User

#### Returns:

<strong>On success will return the newly created user information (as if you were calling /user/X where X being the new users id)</strong>

#### Request:

```json
{baseurl}/user/add/key/XXX/...
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

<strong>On failure will return a json object stating the error code and message</strong>

#### Request:

```json
{baseurl}/user/add/key/XXX/...
```

#### Result:

```json
{"error":"User information is missing. Please submit the Password Field.","code":302}
{"error":"User with the email address already exists.","code":303}
```

## Update User

#### Returns:

<strong>On success will return the updated user information</strong>

#### Request:

```json
{baseurl}/user/update/key/XXX/...
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

<strong>On failure will return a json object stating the error code and message</strong>
