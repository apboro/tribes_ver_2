<?php

namespace App\Filters;

class AudienceFilter extends QueryFilter
{
    public function search($search)
    {
        return $this->builder->where('first_name', 'like', '%' . $search . '%')
            ->orWhere('last_name', 'like', '%' . $search . '%')
            ->orWhere('user_name', 'like', '%' . $search . '%');
    }

    public function community($community)
    {
        return $this->builder->whereHas('communities', function ($q) use ($community) {
            $q->where('id', $community);
        });
    }

    public function type($type)
    {
        if ($type == 'bought') {
            return $this->builder->whereHas('tariffVariant', function ($q)  {
                $q->where('price', '>', 0);
            });
        }
        if ($type == 'trial') {
            return $this->builder->whereHas('tariffVariant', function ($q)  {
                $q->where('price', 0);
            });
        }
    }

    public function role($role)
    {
        if ($role == 'admin' || $role == 'member') {
            return $this->builder->whereHas('communities', function ($q) use ($role) {
                $q->where('telegram_users_community.role', $role);
            });
        }
        if ($role == 'excluded') {
            return $this->builder->whereHas('communities', function ($q) {
                $q->where('telegram_users_community.excluded', true);
            });
        }
    }
}
