<?php


namespace App\Services\Advert;


use App\Models\Advert;
use Illuminate\Database\Eloquent\Builder;

class LoadAdvert
{
    private array $fields;
    private int $id;
    private Builder $query;

    public function __construct($parameters, $advertID )
    {
        $this->query    = Advert::query();
        $this->id       = $advertID;
        $fields         = isset($parameters['fields']) ? $parameters['fields'] : [];
        $this->fields   = array_unique( array_merge( Advert::$defaultFields, $fields ) );
    }

    public function load()
    {
        $advert = $this->query->find($this->id, $this->fields);

        return $advert;
    }
}
