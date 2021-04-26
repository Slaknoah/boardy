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
use Illuminate\Support\Facades\Cache;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $params = [
            'page'      => 1,
            'order_by'  => '',
            'order_direction' => '',
            'take'      => '',
            'search'    => '',
            'user'      => $request->user()->id
        ];
        foreach (array_keys($params) as $param) {
            if (request()->has($param))
                $params[$param] = request()->input($param);
        }
        $prefix = 'adverts_';
        $hashed = md5(json_encode($params));
        $cacheKey = $prefix . $hashed;

        return Cache::remember($cacheKey, 60*60*24, function () use ($request) {
            $loadAdverts = new LoadAdverts($request->all());

            $adverts = $loadAdverts->load();

            return ( AdvertResource::collection($adverts) )->response();
        });
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
