<?php

namespace App\Http\Filters;

class PropertyFilter extends QueryFilter
{
    public function min_value($value)
    {
        return $this->builder->where('min_value', '>=', $value);
    }

    public function max_value($value)
    {
        return $this->builder->where('max_value', '<=', $value);
    }

    public function number_of_bed($value)
    {
        return $this->builder->whereHas('details', function ($query) use ($value) {
            $query->where('number_of_bed', $value);
        });
    }

    public function num_of_bath($value)
    {
        return $this->builder->whereHas('details', function ($query) use ($value) {
            $query->where('num_of_bath', $value);
        });
    }

    public function is_fully_furnished($value)
    {
        return $this->builder->whereHas('details', function ($query) use ($value) {
            $query->where('is_fully_furnished', $value);
        });
    }

    public function search($term)
    {
        return $this->builder->where(function($query) use ($term) {
            $query->where('name', 'like', "%$term%")
                ->orWhere('location', 'like', "%$term%")
                ->orWhereHas('details', function($query) use ($term) {
                    $query->where('number_of_bed', 'like', "%$term%")
                        ->orWhere('num_of_bath', 'like', "%$term%")
                        ->orWhere('floor', 'like', "%$term%")
                        ->orWhere('transection_type', 'like', "%$term%")
                        ->orWhere('facing', 'like', "%$term%")
                        ->orWhere('additional_rooms', 'like', "%$term%")
                        ->orWhere('age_of_construction', 'like', "%$term%");
                });
        });
    }
}
