<?php

namespace App\Http\Filters;

class UserFilter extends QueryFilter
{
    /**
     * student participants filter by type
     *
     * @param  String  $type
     * @return Query Builder
     */
    public function username($str = null)
    {
        return $this->builder->when($str != 'all', function ($q) use ($str) {
            return $q->where('username', $str);
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
            return $this->builder;
        }
        return $this->builder
            ->when($str == 'name', function ($q) {
                return $q->orderBy('full_name', request('direction') ?? 'asc');
            })
            ->when($str == 'email', function ($q) {
                return $q->orderBy('email', request('direction') ?? 'asc');
            })
            ->when($str == 'contact', function ($q) {
                return $q->orderBy('contact', request('direction') ?? 'asc');
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
            return $this->builder;
        }
        return $this->builder->where(function ($query) use ($str) {
            return $query->where('username', 'LIKE', '%' . $str . '%')
                ->orWhere('email',  'LIKE', '%' . $str . '%');
        });
    }
}
