<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'service_id',
        'day_of_week',
        'start_time',
        'end_time',
        'appointment_duration', // en minutos
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getStartTimeFormattedAttribute()
    {
        $time = $this->start_time;
        if (!$time) {
            return '-';
        }

        // Si ya es Carbon (del cast), formatea directo
        if ($time instanceof Carbon) {
            return $time->format('H:i');
        }

        // Si es string, parsea
        try {
            return Carbon::parse($time)->format('H:i');
        } catch (\Exception $e) {
            return '-';  // Fallback si parse falla
        }
    }

    public function getEndTimeFormattedAttribute()
    {
        $time = $this->end_time;
        if (!$time) {
            return '-';
        }

        if ($time instanceof Carbon) {
            return $time->format('H:i');
        }

        try {
            return Carbon::parse($time)->format('H:i');
        } catch (\Exception $e) {
            return '-';
        }
    }

    public function getAvailableSlotsAttribute()
    {
        try {
            if (!$this->start_time || !$this->end_time || !$this->appointment_duration) {
                return [];  // Retorna vacío si faltan datos clave
            }

            $start = Carbon::parse($this->start_time);
            $end = Carbon::parse($this->end_time);
            $duration = (int) $this->appointment_duration;  // Asegura entero

            if ($start->gte($end)) {
                return [];  // Si start >= end, no hay slots
            }

            $slots = [];
            $current = $start->copy();

            while ($current->lt($end)) {
                $slots[] = $current->format('H:i');
                $current->addMinutes($duration);
            }

            return $slots;
        } catch (\Exception $e) {
            // Log error si quieres: \Log::error('Error calculating slots: ' . $e->getMessage());
            return [];  // Siempre retorna array en caso de fallo
        }
    }

}
