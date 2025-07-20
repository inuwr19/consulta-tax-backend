<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'experience_years',
        'rating',
        'price_individual_service',
        'price_individual_jasa',
        'price_company_service',
    ];

    // Relationship with appointments/consultations
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relationship with users (if consultants are linked to user accounts)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
