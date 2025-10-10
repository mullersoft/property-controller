<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

  protected $fillable = [
    'employee_id',
    'property_id',
    'assigned_date',
    'return_date',
];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function property(){
        return $this->belongsTo(Property::class);
    }
}
