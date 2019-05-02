## Endpoints

|Type|Location|Permission|Description|Alias|
|--- |--- |--- |--- |--- |
|GET|/order/statuses|view_order_statuses|View available order statuses|--|
|GET|/order/appraisal-types|view_order_appr_types|View available appraisal types|--|
|GET|/order/property-types|view_order_property_types|View available property types|--|
|GET|/order/occ-statuses|view_order_occ_status|View available occupancy status|--|
|GET|/order/loan-types|view_order_loantype|View available order loan types|--|
|GET|/order/loan-purposes|view_order_loanpurpose|View available order loan purposes|--|
|GET|/order/document-types|view_order_documenttypes|View available order document types|--|
|GET|/order/addendas|view_order_addendas|View available order addendas|--|


## Appraisal Statuses

#### Request:

```json
{baseurl}/order/statuses/key/XXX
```

#### Result:

```json
{
   "2":"To Be Scheduled",
   "3":"Scheduled",
   "4":"In Progress",
   "5":"Inspection Complete",
   "6":"Appraisal Completed",
   "7":"Payment Pending",
   "8":"Unassigned",
   "9":"TEMPORDER",
   "10":"Cancelled",
   "12":"On Hold",
   "14":"Hold for U/W Cond",
   "15":"Q/C",
   "16":"Chargeback",
   "17":"Hold for U/W Approval",
   "18":"Reconsideration",
   "19":"Q/C Correction",
   "20":"Cancelled / Trip Fee",
   "21":"Awaiting Client Approval"
}
```

## Appraisal Types (Products)

#### Request:

```json
{baseurl}/order/appraisal-types/key/XXX
```

#### Result:

```json
{
   "1":"1004 Full",
   "2":"2055 Drive By",
   "3":"1025 Multi-Family",
   "4":"1073 Condo / PUD",
   "5":"Vacant Land",
   "7":"2000 Residential Field Review Report",
   "13":"1004D Final Inspection",
   "15":"1004C Manufactured Home",
   "16":"2000A Multi-Family Field Review",
   "18":"2075 Property Inspection Report",
   "19":"2006 Desktop Appraisal Review",
   "34":"1007 Rent Schedule",
   "37":"216 Operating Income Statement",
   "43":"1075 Drive-by Condo",
   "70":"2006 Desktop Appraisal Review Plus Inspe",
   "71":"1004 Full - EPP",
   "72":"1073 Condo - EPP",
   "73":"1004C Manufactured Home - EPP",
   "74":"1025 Multi-Family - EPP",
   "75":"Occupancy Verification",
   "76":"QC Audits",
   "77":"Flood Certification",
   "78":"Desktop Valuation"
}
```

## Appraisal Property Types

#### Request:

```json
{baseurl}/order/property-types/key/XXX
```

#### Result:

```json
{
   "1":"Detached",
   "2":"Attached",
   "3":"Condo",
   "4":"PUD",
   "5":"CO-OP",
   "7":"i-House I",
   "10":"i-House II"
}
```

## Appraisal Occupancy Statuses

#### Request:

```json
{baseurl}/order/occ-statuses/key/XXX
```

#### Result:

```json
{
   "1":"Primary Residence",
   "2":"Second Home",
   "3":"Investment Property"
}
```

## Appraisal Loan Types

#### Request:

```json
{baseurl}/order/loan-types/key/XXX
```

#### Result:

```json
{
   "1":"Conventional",
   "2":"FHA",
   "4":"USDA Rural/Housing Servic",
   "5":"N/A",
   "7":"Asset Valuation (REO)"
}
```

## Appraisal Loan Reason

#### Request:

```json
{baseurl}/order/loan-purposes/key/XXX
```

#### Result:

```json
{
   "1":"Purchase",
   "2":"Cash-Out",
   "3":"Refi",
   "6":"N/A",
   "7":"Reverse Mortgage",
   "10":"Asset Valuation"
}
```

<a name='document-types'></a>

## Appraisal Document Types


#### Request:

```json
{baseurl}/order/document-types/key/XXX
```

#### Result:

```json
{
   "1":{
      "id":"1",
      "key":"SALES_CONTRACT",
      "title":"Sales Contract"
   },
   "2":{
      "id":"2",
      "key":"SALES_CONTRACT_ADDENDUM",
      "title":"Sales Contract Addendum"
   },
   "3":{
      "id":"3",
      "key":"TITLE",
      "title":"Title"
   },
   "4":{
      "id":"4",
      "key":"SURVEY",
      "title":"Survey"
   },
   "5":{
      "id":"5",
      "key":"PLAT_MAP",
      "title":"Plat Map"
   },
   "6":{
      "id":"6",
      "key":"CONDO_QUESTIONNAIRE",
      "title":"Condo Questionnaire"
   },
   "7":{
      "id":"7",
      "key":"OTHER",
      "title":"Other"
   },
   "8":{
      "id":"8",
      "key":"pdf",
      "title":"Appraisal PDF Document"
   },
   "9":{
      "id":"9",
      "key":"xml",
      "title":"Appraisal XML Document"
   },
   "10":{
      "id":"10",
      "key":"invoice",
      "title":"Invoice"
   },
   "11":{
      "id":"11",
      "key":"icc",
      "title":"Independence Compliance Certificate"
   },
   "12":{
      "id":"12",
      "key":"FHACASETRANSFER",
      "title":"FHA Case Transfer"
   },
   "13":{
      "id":"13",
      "key":"REALVIEWHTMLREPORT",
      "title":"RealView HTML Report"
   },
   "14":{
      "id":"14",
      "key":"REALVIEWPDFREPORT",
      "title":"RealView PDF Report"
   },
   "15":{
      "id":"15",
      "key":"REALVIEWXML",
      "title":"RealView XML"
   },
   "16":{
      "id":"16",
      "key":"UCDPFNMSSR",
      "title":"UCDP FNM SSR"
   },
   "17":{
      "id":"17",
      "key":"UCDPFRESSR",
      "title":"UCDP FRE SSR"
   },
   "18":{
      "id":"18",
      "key":"FLOODCERTIFICATION",
      "title":"Flood Certification"
   },
   "19":{
      "id":"19",
      "key":"REALVIEWPDFREPORTSUMMARY",
      "title":"RealView PDF Summary Report"
   }
}
```

<a name='addendas'></a>

## Appraisal Addendas

#### Request:

```json
{baseurl}/order/addendas/key/XXX
```

#### Result:

```json
{
   "1":{
      "id":"1",
      "title":"1007 - Rent Schedule",
      "price":75
   },
   "2":{
      "id":"2",
      "title":"216 - Operating Income Statement",
      "price":75
   },
   "3":{
      "id":"3",
      "title":"1004D - Appraisal Update and/or Completion Report",
      "price":75
   }
}
```
