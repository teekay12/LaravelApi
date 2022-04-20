<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\InputValidator;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'website' => 'required',
        ]);

        if(InputValidator::getWebsiteName($request->website) && InputValidator::getUserName($request->user)){
            return Subscription::create([
                'user_id' => InputValidator::getUserName($request->user),
                'website_id' => InputValidator::getWebsiteID($request->website)
            ]);
        }else{
            return response()->json([
                'error' => 'Resource not found',
            ], 404);
        }
        return response()->json([
            'error' => 'Server Error, please try again',
        ], 500);
    }
}
