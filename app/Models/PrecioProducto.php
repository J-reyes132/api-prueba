<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'divisa_id',
        'precio',
        'is_active',
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function divisa()
    {
        return $this->belongsTo(Divisa::class);
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
    public function scopeProductoId($query, $productoId)
    {
        if (!empty($productoId)) {
            return $query->where('producto_id', $productoId);
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
    public function scopeOrderCreatedBy($query, $orderby)
    {
        if (!empty($orderby)) {
            return $query->orderBy('created_at', $orderby);
        }
        return $query;
    }
    public function scopePrecio($query, $precio)
    {
        if (!empty($precio)) {
            return $query->where('precio', $precio);
        }
        return $query;
    }
}
