<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\InputValidator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\NewPost;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request) || $request == null || empty($request->title) || empty($request->description))
            return response()->json([
                'error' => 'All fields are required',
            ], 404);

        if(InputValidator::getWebsiteName($request->website)){
            $websiteId = InputValidator::getWebsiteID($request->website);
            //send email to all users subscribed to this website

            $data =  Post::create([
                'website_id' => $websiteId,
                'title' => $request->title,
                'description' => $request->description
            ]);

            $emails = InputValidator::getEmails($websiteId);
            if($emails != null){
                NewPost::dispatch($emails, $request)->delay();
            }

            return $data;
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
