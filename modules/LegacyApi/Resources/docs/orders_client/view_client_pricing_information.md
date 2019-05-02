<p>View list of prices for different appraisal types in all or some states. This includes any state taxes, addendas or any additional charges that apply.</p>

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/client-pricing|list_orders|Get all client pricing. state and appraisal type are required fields. you can specify more than one.|-|


#### Request:

```json
{baseurl}/order/client-pricing/key/XXX?user_email=test@test.com&user_password=XXX&state[]=AL&state[]=CA&appraisal[]=2&appraisal[]=3
```

```json
{baseurl}/order/client-pricing/key/XXX?user_email=test@test.com&user_password=XXX&state=AL,CA,NM&appraisal=1,2,3
```

#### Result:

```json
{
	"client": "Landmark Demo",
	"title": "Custom Client Pricing Used",
	"rows": {
		"CA": {
			"tax": [],
			"appraisals": {
				"3": {
					"appraisal": "1025 - Multi-Family",
					"additions": {
						"addendas": [
							{
								"title": "1007 - Rent Schedule",
								"amount": "75"
							}
						]
					},
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "76.00",
						"fha": "76.00"
					}
				}
			}
		}
	}
}
```

```json
{
	"client": "Landmark Demo",
	"title": "Custom Client Pricing Used",
	"rows": {
		"AL": {
			"tax": [],
			"appraisals": {
				"1": {
					"appraisal": "1004 - Full",
					"additions": [],
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "1.00",
						"fha": "1.00"
					}
				},
				"2": {
					"appraisal": "2055 - Drive By",
					"additions": [],
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "1.00",
						"fha": "1.00"
					}
				},
				"3": {
					"appraisal": "1025 - Multi-Family",
					"additions": {
						"addendas": [
							{
								"title": "1007 - Rent Schedule",
								"amount": "75"
							}
						]
					},
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "76.00",
						"fha": "76.00"
					}
				}
			}
		},
		"CA": {
			"tax": [],
			"appraisals": {
				"1": {
					"appraisal": "1004 - Full",
					"additions": [],
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "1.00",
						"fha": "1.00"
					}
				},
				"2": {
					"appraisal": "2055 - Drive By",
					"additions": [],
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "1.00",
						"fha": "1.00"
					}
				},
				"3": {
					"appraisal": "1025 - Multi-Family",
					"additions": {
						"addendas": [
							{
								"title": "1007 - Rent Schedule",
								"amount": "75"
							}
						]
					},
					"base_price": {
						"conventional": "1.00",
						"fha": "1.00"
					},
					"modified_price": {
						"conventional": "76.00",
						"fha": "76.00"
					}
				}
			}
		}
	}
}
```