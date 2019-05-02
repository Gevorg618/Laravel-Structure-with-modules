<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Models\FrontEnd\Subscriber;
use App\Http\Controllers\Controller;
use App\Http\Requests\FrontEnd\SubscriberRequest;

class SubscribeController extends Controller
{
    /**
     * @param SubscriberRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(SubscriberRequest $request)
    {
        $data = $request->all();

        Subscriber::create([
            'email' => $data['subscribe_email']
        ]);

        return response()->json(['message' => 'Thank You! You are now subscribed.']);
    }
}
