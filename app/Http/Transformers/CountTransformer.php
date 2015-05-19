<?php namespace App\Http\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract as Transformer;

class CountTransformer extends Transformer
{
    public function transform($count)
    {
        return [
            'count' => $count,
        ];
    }

}