<p>This method is designed to list a specific appraiser assigned orders and information related to them</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/vendor-list|list_orders|Get all orders assigend tp|-|



### Required Fields

|Name|Description|Notes|
|--- |--- |--- |
|user_email|Vendor email address|Must exist in the system and assinged to the order you are attemting to upload the final report to|
|user_password|plain text password|Must be plain text and transmitted over HTTPS..|


#### Request:

```json
{baseurl}/order/vendor-list/key/XXX?user_email=X&user_password=Y
```

#### Result:

```json
{
	"141222130": {
		"id": "141222130",
		"ordereddate": "2013-07-15 16:54:52",
		"sched_dts": "2013-07-23 10:00:00",
		"completed": "2013-07-24 13:06:57",
		"submitted": "2013-07-25 11:15:20",
		"propaddress1": "3550 Bryant St",
		"propaddress2": "",
		"propcity": "Palo Alto",
		"propstate": "CA",
		"propzip": "94306",
		"prop_type": "1",
		"loantype": "2",
		"req_fha": "N",
		"loanpurpose": "7",
		"lender": "Generation Mortgage",
		"lenderaddress": "3565 Piedmont Road NE # 300",
		"lendercity": "Atlanta",
		"lenderstate": "GA",
		"lenderzip": "30305",
		"loanrefnum": "",
		"no_units": "1",
		"sales_price": "0",
		"appr_type": "1",
		"contactname": "Mary Lautner",
		"contactphone": "(650) 7998442",
		"contactemail": "",
		"contactnotes": "",
		"complex_prop": "",
		"is_cod": ""
	},
	"141226292": {
		"id": "141226292",
		"ordereddate": "2013-07-30 15:33:44",
		"sched_dts": "2013-07-31 12:00:00",
		"completed": "2013-07-31 14:19:02",
		"submitted": "2013-08-02 12:21:53",
		"propaddress1": "15951 Quail Hill Rd",
		"propaddress2": "",
		"propcity": "LOS GATOS",
		"propstate": "CA",
		"propzip": "95032",
		"prop_type": "1",
		"loantype": "2",
		"req_fha": "N",
		"loanpurpose": "7",
		"lender": "Reverse Mortgage Solutions, Inc.",
		"lenderaddress": " 2727 Spring Creek Drive ",
		"lendercity": "Spring",
		"lenderstate": "TX",
		"lenderzip": "77373",
		"loanrefnum": "R2013072221",
		"no_units": "1",
		"sales_price": "0",
		"appr_type": "1",
		"contactname": "Deborah",
		"contactphone": "(831) 2472753",
		"contactemail": "",
		"contactnotes": "Borrower's daughter",
		"complex_prop": "",
		"is_cod": ""
	}
}
```

<div class="alert alert-info">Filtering Orders - You can pass in 'ids' as a parameter to get the data for those orders. Either comma separated string or json object</div>

#### Request:

```json
{baseurl}/order/vendor-list/key/XXX?user_email=X&user_password=Y&ids=200019370,200019369,200019368
{baseurl}/order/vendor-list/key/XXX?user_email=X&user_password=Y&ids={"1":"200019370","2":"200019369","3":"200019368"}
```

#### Result:

```json
{
	"141222130": {
		"id": "141222130",
		"ordereddate": "2013-07-15 16:54:52",
		"sched_dts": "2013-07-23 10:00:00",
		"completed": "2013-07-24 13:06:57",
		"submitted": "2013-07-25 11:15:20",
		"propaddress1": "3550 Bryant St",
		"propaddress2": "",
		"propcity": "Palo Alto",
		"propstate": "CA",
		"propzip": "94306",
		"prop_type": "1",
		"loantype": "2",
		"req_fha": "N",
		"loanpurpose": "7",
		"lender": "Generation Mortgage",
		"lenderaddress": "3565 Piedmont Road NE # 300",
		"lendercity": "Atlanta",
		"lenderstate": "GA",
		"lenderzip": "30305",
		"loanrefnum": "",
		"no_units": "1",
		"sales_price": "0",
		"appr_type": "1",
		"contactname": "Mary Lautner",
		"contactphone": "(650) 7998442",
		"contactemail": "",
		"contactnotes": "",
		"complex_prop": "",
		"is_cod": ""
	},
	"141226292": {
		"id": "141226292",
		"ordereddate": "2013-07-30 15:33:44",
		"sched_dts": "2013-07-31 12:00:00",
		"completed": "2013-07-31 14:19:02",
		"submitted": "2013-08-02 12:21:53",
		"propaddress1": "15951 Quail Hill Rd",
		"propaddress2": "",
		"propcity": "LOS GATOS",
		"propstate": "CA",
		"propzip": "95032",
		"prop_type": "1",
		"loantype": "2",
		"req_fha": "N",
		"loanpurpose": "7",
		"lender": "Reverse Mortgage Solutions, Inc.",
		"lenderaddress": " 2727 Spring Creek Drive ",
		"lendercity": "Spring",
		"lenderstate": "TX",
		"lenderzip": "77373",
		"loanrefnum": "R2013072221",
		"no_units": "1",
		"sales_price": "0",
		"appr_type": "1",
		"contactname": "Deborah",
		"contactphone": "(831) 2472753",
		"contactemail": "",
		"contactnotes": "Borrower's daughter",
		"complex_prop": "",
		"is_cod": ""
	}
}
```