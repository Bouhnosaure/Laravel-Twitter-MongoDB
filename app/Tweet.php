<?php namespace App;

use Jenssegers\Mongodb\Model as Eloquent;

class Tweet extends Eloquent {

    protected $collection = 'tweet_collection';

    protected $connection = 'mongodb';

    protected $fillable = ['text', 'user'];

}
