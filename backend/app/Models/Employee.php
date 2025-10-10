<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name',
            'department',
            'email',
            'phone',
        ];
        public function assignment(){
            return $this->hasMany(Assignment::class);
        }
public function properties()
{
    return $this->belongsToMany(Property::class, 'assignments')
        ->withPivot('assigned_date', 'return_date')
        ->withTimestamps();
}


}
