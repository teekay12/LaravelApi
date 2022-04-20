<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\InputValidator;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'website'=> 'required',
            'title' => 'required',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        if(InputValidator::getWebsiteName($request->website)){
            $websiteId = InputValidator::getWebsiteID($request->website);
            //send email to all users subscribed to this website

            $data =  Post::create([
                'website_id' => $websiteId,
                'title' => $request->title,
                'description' => $request->description
            ]);

            $input = [
                'title' => $request->title,
                'description' => $request->description,
                'website_id' => $websiteId
            ];

            NewPost::dispatch($input)->delay(now());

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
