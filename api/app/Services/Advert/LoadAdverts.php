<?php


namespace App\Services\Advert;


use App\Models\Advert;
use Illuminate\Database\Eloquent\Builder;

class LoadAdverts
{
    private int $take;
    private string $orderBy;
    private string $orderDirection;
    private string $search;
    private Builder $query;

    public function __construct($parameters)
    {
        $this->setLocalParameters($parameters);
        $this->query = Advert::query();
    }

    public function load() {
        $this->applyAddedBy();
        $this->applySearch();
        $this->applyOrder();

        return $this->query->select(Advert::$defaultFields)->paginate( $this->take );
    }

    private function applySearch()
    {
        if( $this->search != '' ){
            $this->query->where(function( $query ) {
                $query->where('title', 'LIKE', '%'.$this->search.'%');
            });
        }
    }

    private function applyOrder()
    {
        $this->query->orderBy( $this->orderBy, $this->orderDirection );
    }

    private function applyAddedBy()
    {
        $this->query->where('added_by', auth()->user()->id);
    }

    private function setLocalParameters($parameters)
    {
        $this->take             = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->search           = isset( $parameters['search'] ) ? $parameters['search'] : '';
        $this->orderBy          = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection   = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
    }
}
