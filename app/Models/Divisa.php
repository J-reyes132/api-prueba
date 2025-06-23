<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Divisa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'symbol',
        'exchange_rate',
    ];

    public function scopeActive($query, $active)
    {
        if(!empty($active)){
            if($active == 'true')
            {
                return  $query->where('is_active', true);
            }
            if($active == 'false')
            {
                return  $query->where('is_active', false);
            }
        }
        return $query;

    }

    public function scopename($query, $name)
    {
        if(!empty($name)){
            return $query->where('name', 'like', "%{$name}%");
        }
        return $query;
    }

    public function scopeOrderCreatedBy($query, $orderby){
        if(!empty($orderby)){
            return $query->orderby('created_at', $orderby);
        }
    }

}
