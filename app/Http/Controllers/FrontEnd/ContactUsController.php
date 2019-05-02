<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\ContactUsRequest;

class ContactUsController extends Controller
{
    /**
     * @param ContactUsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(ContactUsRequest $request)
    {
        $receivers = emails(setting('contact_us_recipients'));
        $email = [];
        $data = $request->all();
        $email['name'] = $data['name'];
        $email['email'] = $data['email'];
        $email['subject'] = $data['email'];

        //TODO send contact us Email

        return response()->json([
            'success' => true,
            'message' => 'Thank You! Your message was sent.'
        ]);
    }
}
