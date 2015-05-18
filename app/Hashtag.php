<?php namespace App;

use Jenssegers\Mongodb\Model as Eloquent;

class Hashtag extends Eloquent
{

    protected $collection = 'hashtag_collection';

    protected $connection = 'mongodb';

    protected $fillable = ['hashtag'];

}
