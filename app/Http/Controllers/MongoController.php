<?php namespace App\Http\Controllers;

use App\Hashtag;
use App\Services\MapReduce;
use App\Services\TwitterStream;
use App\Tweet;
use Illuminate\Support\Facades\DB;

class MongoController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @param MapReduce $mapReduce
     * @return Response
     */
    public function index(MapReduce $mapReduce)
    {

        $total_tweets = Tweet::count();
        $total_hashtag = Hashtag::count();

        $agg_hashtags = $mapReduce->agg_hashtags();

        return view('home')->with(['total_tweets' => $total_tweets, 'total_hashtag' => $total_hashtag, 'agg_hashtags' => $agg_hashtags]);
    }

    public function keywords()
    {
        $limit = 25;
        $projections = array('id', 'value');
        $keywords = DB::collection('map_reduce_twitter_words')->orderBy('value', 'desc')->paginate($limit, $projections);
        return view('keywords')->with(['keywords' => $keywords]);
    }

    /**
     * @param MapReduce $mapReduce
     */
    public function mapreduce(MapReduce $mapReduce)
    {
        dd($mapReduce->mapreduce_keywords());
    }

    public function stream(TwitterStream $stream)
    {
        $stream->get_steam();
    }

}
