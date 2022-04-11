<?php
namespace App\Services;

use App\Models\Post;
use App\Models\Website;
use App\Models\User;
use App\Models\Subscription;

class InputValidator{

    public static function getPostName($title){
        return Post::where('title', $title)->first()->name ?? false;
    }

    public static function getWebsiteName($name){
        return Website::where('name', $name)->first()->name ?? false;
    }

    public static function getUserName($email){
        return User::where('email', $email)->first()->id ?? false;
    }

    public static function getWebsiteID($name){
        return Website::where('name', $name)->first()->id ?? false;
    }

    public static function getEmails($id){
        $arr = [];
        $data = Subscription::join('users', 'subscriptions.user_id', '=', 'users.id')->where('website_id', $id)->select('users.email')->get();
        foreach($data as $item){
            array_push($arr, $item->email);
        }
        return count($arr) > 0 ? $arr  : null;
    }
}