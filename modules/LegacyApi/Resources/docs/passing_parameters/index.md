API Supports receiving HTTP key value pairs (IE: key1=value1&key2=value2&key3=value3 etc...)

Creating a new user using HTTP key value pairs

```
json {baseurl}/user/add/key/XXX?email=test@test.com&password=XXXXXXX&firstname=Test&lastname=Test 
```

Creating a new order using HTTP key value pairs

```
json {baseurl}/order/add/key/XXX?orderedby=XX&propaddress1=Address&propcity=City&propstate=State&propzip=Zip 
```

You can pass a JSON encoded object by specifying the `json` HTTP key

Creating a new user using HTTP key value pairs with a json object as the data

```
json {baseurl}/user/add/key/XXX?json={"email":"test@test.com","password":"XXXX","firstname":"Test","lastname":"Test"} 
```

Creating a new order using HTTP key value pairs with a json object as the data

```
json {baseurl}/order/add/key/XXX?json={"orderedby":"XXX","propaddress1":"Address","propcity":"City","propstate":"State","Zip":"Zip"} 
```