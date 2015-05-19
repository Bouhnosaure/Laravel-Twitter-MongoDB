<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class HashtagsTransformer extends Transformer
{
    public function transform(array $hashtag)
    {
        return [
            'name' => $hashtag['_id'],
            'count' => $hashtag['number'],
        ];
    }

}