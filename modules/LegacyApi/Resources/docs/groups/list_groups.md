<p>List all the user groups associated with the API Account being used. These groups are the ones you are allowed to create new users under and place orders for.</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/group/list|list_groups|Get all groups associated with the API Account|-|



<p>Returns JSON string groupId => groupName</p>


#### Request:

```json
{baseurl}/group/list/key/XXX
```

#### Result:

```json
{"1":"Group 1","2":"Group 2","3":"Group 3"}
```