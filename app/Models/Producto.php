<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    public function divisa()
    {
        return $this->belongsTo(Divisa::class);
    }

    public function scopeName($query, $name)
    {
        if (!empty($name)) {
            return $query->where('name', 'like', "%{$name}%");
        }
        return $query;
    }

    public function scopeActive($query, $active)
    {
        if (!empty($active)) {
            if ($active == 'true') {
                return $query->where('is_active', true);
            }
            if ($active == 'false') {
                return $query->where('is_active', false);
            }
        }
        return $query;
    }

    public function scopeOrderCreatedBy($query, $orderby)
    {
        if (!empty($orderby)) {
            return $query->orderBy('created_at', $orderby);
        }
        return $query;
    }

    public function scopeDivisaId($query, $divisaId)
    {
        if (!empty($divisaId)) {
            return $query->where('divisa_id', $divisaId);
        }
        return $query;
    }
}
