<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'ci',
        'ci_extension',
        'birth_date',
        'gender',
        'address',
        'phone',
        'license_number',
        'bio',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
