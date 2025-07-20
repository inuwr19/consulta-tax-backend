<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'time', 'method', 'status', 'gmeet_link', 'notes',
    'service_type', 'individual_service_type', 'consultant_id',
    'nama', 'nik', 'npwp', 'efin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }
}

