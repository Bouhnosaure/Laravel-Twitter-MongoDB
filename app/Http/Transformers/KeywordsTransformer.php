<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class KeywordsTransformer extends Transformer
{
    public function transform(array $keyword)
    {
        return [
            'name' => $keyword['_id'],
            'count' => $keyword['value']['count'],
        ];
    }

}