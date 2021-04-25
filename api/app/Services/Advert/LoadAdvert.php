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
        $fields         = isset($parameters['fields']) ? array_unique($parameters['fields']) : [];
        $this->fields   = array_merge( Advert::$defaultFields, $fields );
    }

    public function load()
    {
        $advert = $this->query->find($this->id)->select($this->fields)->first();

        return $advert;
    }
}
