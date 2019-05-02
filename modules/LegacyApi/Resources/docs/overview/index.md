Landscape™ API was built to support multiple clients, Allowing to set certain permissions for certain clients while others only have limited access to the information and data stored.

The following page explains exactly how to send and receive requests to and from the API endpoint. This page also hosts the detailed explanation of the error codes you might encounter while using the API and a test console to test HTTP requests against the API.

To be able to use the API all you'll need to do is to send HTTP requests to the API specifying the appropriate action, fields and data you'd like to POST or GET to/from the API.

Read carefully as certain actions and calls require you to use specific HTTP requests (POST/GET/PUT/DELETE) and specifying the required fields when sending the requests.

The API endpoint is `ASK FOR YOUR BASE URL` in case you need to send requests over a secured connection (Sending passwords when adding users) you can use the same endpoint while specifying the secured host and sending requests over a secured connection IE: `ASK FOR YOUR BASE URL`

Under each API call you will see the request type excepted for the call to go through (any other request will be rejected and an appropriate error message and code will be returned), You will also see the permission required for that request.

<div class="alert alert-warning">In the documentation {baseurl} refers to the base API endpoint which is described above.</div>