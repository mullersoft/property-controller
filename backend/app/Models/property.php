<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'purchase_date',
        'purchase_cost',
        'status',
        'serial_number',
        'model_number',
        'manufacturer',
        'current_value',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
