<?php

namespace App\Http\Filters;

class TaskFilter extends QueryFilter
{
    /**
     * student participants filter by type
     *
     * @param  String  $type
     * @return Query Builder
     */
    public function title($str = null)
    {
        return $this->builder->when($str != 'all', function ($q) use ($str) {
            return $q->where('name', $str);
        });
    }

    /**
     * course order by column and value
     *
     * @param  String  $str
     * @return Query Builder
     */
    public function order($str = null)
    {
        if (empty($str)) {
            return true;
        }
        return $this->builder
            ->when($str == 'name', function ($q) {
                return $q->orderBy('name', request('direction') ?? 'asc');
            })
            ->when($str == 'start_date', function ($q) {
                return $q->orderBy('start_date', request('direction') ?? 'asc');
            });
    }

    /**
     * course search by random string
     *
     * @param  String $str
     * @return Query Builder
     */
    public function q($str = '')
    {
        if (empty($str)) {
            return true;
        }
        return $this->builder->where(function ($query) use ($str) {
            return $query->where('name', 'LIKE', '%' . $str . '%')
                ->orWhere('start_date',  'LIKE', '%' . $str . '%')
                ->orWhere('start_time',  'LIKE', '%' . $str . '%');
        });
    }
}
