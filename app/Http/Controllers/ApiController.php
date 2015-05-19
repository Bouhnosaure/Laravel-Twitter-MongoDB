<?php namespace App\Http\Controllers;

use App\Hashtag;
use App\Http\Transformers\CountTransformer;
use App\Http\Transformers\HashtagsTransformer;
use App\Http\Transformers\KeywordsTransformer;
use App\Services\MapReduce;
use App\Services\TwitterStream;
use App\Tweet;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
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
     * @var Response
     */
    private $response;

    /**
     * Create a new controller instance.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @param MapReduce $mapReduce
     * @return Response
     */
    public function twitterTotal()
    {
        $count = Tweet::count();

        return $this->response->withItem($count, new CountTransformer());
    }

    public function hashtagTotal()
    {
        $count = Hashtag::count();

        return $this->response->withItem($count, new CountTransformer());
    }

    /**
     * @param MapReduce $mapReduce
     * @return mixed
     */
    public function aggregateHashtag(MapReduce $mapReduce)
    {
        $hashtags = $mapReduce->agg_hashtags();

        return $this->response->withCollection($hashtags['result'], new HashtagsTransformer());
    }

    public function keywords()
    {
        $keywords = DB::collection('map_reduce_twitter_words')->orderBy('value', 'desc')->paginate(25);

        return $this->response->withPaginator($keywords, new KeywordsTransformer());
    }


}
