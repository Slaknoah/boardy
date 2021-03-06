<?php


namespace Tests\Feature\API\Advert;


use App\Http\Resources\AdvertResource;
use App\Models\Advert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadAdvertsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected array $resourceJsonStructure;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->user = User::find(1);

        $this->resourceJsonStructure = [
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'image',
                    'price',
                    'created_at'
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'per_page',
                'to',
                'total'
            ]
        ];
    }

    public function testGuestCannotLoadAdverts()
    {
        $this->getJson(route('api.adverts.get'))
            ->assertStatus(401);
    }

    public function testUserCanLoadAdverts()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.adverts.get'))
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceJsonStructure);
        $json = $response->json();

        $this->assertCount(10, $json['data']);
    }

    public function testUserCanPaginateAdverts()
    {
        $this->actingAs($this->user)
            ->getJson(route('api.adverts.get', ['page' => 2]))
            ->assertStatus(200)
            ->assertJson([
                'meta'  => [
                    'current_page'  => 2
                ]
            ])
            ->assertJsonStructure($this->resourceJsonStructure);
    }

    public function testUserCanOrderAdvertsByDateCreated()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.adverts.get', Advert::$defaultFields))
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceJsonStructure);
        $json = $response->json();

        $adverts = Advert::orderBy('created_at', 'ASC')->select(Advert::$defaultFields)->paginate(10);
        $resource = AdvertResource::collection($adverts);
        $resourceResponse = $resource->response()->getData(true);

        $this->assertSame( $json, $resourceResponse );
    }

    public function testUserCanOrderAdvertsByPrice()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.adverts.get', ['order_by' => 'price', 'order_direction' => 'ASC']))
            ->assertStatus(200)
            ->assertJsonStructure($this->resourceJsonStructure);
        $json = $response->json();

        $adverts = Advert::orderBy('price', 'ASC')->select(Advert::$defaultFields)->paginate(10);
        $resource = AdvertResource::collection($adverts);
        $resourceResponse = $resource->response()->getData(true);

        $this->assertSame( $json, $resourceResponse );
    }
}
