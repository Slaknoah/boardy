<?php


namespace App\Services\Advert;


use App\Models\Advert;

class CreateAdvert
{
    private array $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function save()
    {
        $advert                 = new Advert();
        $advert->title          = $this->data['title'];
        $advert->description    = $this->data['description'];
        $advert->price          = $this->data['price'];
        $advert->images_links   = $this->data['images_links'];
        $advert->added_by       = auth()->user()->id;

        $advert->save();

        return $advert;
    }
}
