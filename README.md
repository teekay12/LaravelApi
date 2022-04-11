<p>THis is a simple RESTFUL API built using Laravel.<p>

<p>To insatall run composer install  to install dependencies<br>
Run php artisan migrate to migrate the database<br>
Run php artisan db:seed to setup with test data
</p>

<p> Available end points are: <br>
/api/posts -- to list all post(get request)<br>
/api/post/new -- to create new post , this requires a website ID(this can be gotten from the test db) the post belongs to, title and name(post request) <br>
/api/subscribe -- to subscribe to a website this requires a website ID and user id(this data can be gotten from the db )</p>
