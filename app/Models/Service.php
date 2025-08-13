<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'service_type_id',
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
