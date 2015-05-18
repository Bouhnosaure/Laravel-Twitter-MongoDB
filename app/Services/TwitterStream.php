<?php
namespace App\Services;

use App\Hashtag;
use App\Tweet;
use Makotokw\Twient\Twitter;

class TwitterStream
{

    private $to;

    public function __construct()
    {
        // $this->to = $this->getTwistOAuth();

    }

    public function get_steam()
    {
        set_time_limit(60000);
        $twitter = $this->getTwitAuth();
        $twitter->streaming(
            'statuses/filter',
            array('track' => 'my,your,her,his,our,their,the,mine,yours,his,hers,ours,theirs,this,these,that,those,and,i,a,to,you,it,for,on,be,so,are,not,of,in,out,from,all'),
            function ($twitter, $status) {
                $this->send_to_queue($status);
                return true;
            }
        );
    }

    public function send_to_queue($data)
    {
        if (isset($data['text']) && $data['text'] != null) {
            if (isset($data['user']['screen_name']) && $data['user']['screen_name'] != null) {
                $arr['text'] = $data['text'];
                $arr['user'] = $data['user']['screen_name'];
                Tweet::create($arr);
                if (! empty ($data['entities']['hashtags'])) {
                    foreach ($data['entities']['hashtags'] as $hashtag) {
                        Hashtag::create(['hashtag' => $hashtag]);
                    }
                }

            }
        }

    }

    protected function getTwitAuth()
    {

        $consumer_key = env('twitter_consumer_key'); //twitter_consumer_key
        $consumer_secret = env('twitter_consumer_secret');//twitter_consumer_secret
        $oauth_token = env('twitter_access_token');//twitter_access_token
        $oauth_token_secret = env('twitter_access_token_secret');//twitter_access_token_secret

        $twitter = new Twitter();
        $twitter->oAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

        return $twitter;
    }
}