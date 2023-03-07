<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['type','parent'];

    public function saveProduct($formData, $product_id)
    {
        Product::query()->updateOrCreate(
            [
                'id' => $product_id
            ],
            [
                'server_id' => $formData['server_id'],
                'name' => $formData['name'],
                'discount' => $formData['discount'],
                'type' => $formData['type'],
                'price' => $formData['price'],
            ]
        );
    }

    public function parent()
    {
        return $this->belongsTo(Servers::class, 'server_id');
    }

    public function category()
    {
        return $this->belongsTo(Types::class, 'type');
    }

    public function type()
    {
        return $this->belongsTo(Types::class, 'type_id');
    }
}
