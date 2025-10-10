<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'serial_number',
        'status',
        'employee_id',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
