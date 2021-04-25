<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdvertRequest;
use App\Http\Resources\AdvertResource;
use App\Models\Advert;
use App\Services\Advert\CreateAdvert;
use App\Services\Advert\LoadAdvert;
use App\Services\Advert\LoadAdverts;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('');
    }

    public function index(Request $request)
    {
        $loadAdverts = new LoadAdverts($request->all());

        $adverts = $loadAdverts->load();

        return ( AdvertResource::collection($adverts) )->response();
    }

    public function show(Request $request, $advert)
    {
        if( !Advert::where('id', $advert)->exists() ) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $request->validate([
            'fields'    => 'nullable|array',
            'fields.*'  => 'in:images_links,description'
        ]);

        $loadAdvert = new LoadAdvert( $request->all(), $advert );
        $advert     = $loadAdvert->load();

        return ( new AdvertResource( $advert ));
    }

    public function store(CreateAdvertRequest $request)
    {
        $createAdvert = new CreateAdvert($request->all());
        $advert       = $createAdvert->save();

        return response()->json([ 'id' => $advert->id ]);
    }
}
