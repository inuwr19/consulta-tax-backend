<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    public function view(User $user, Payment $payment)
{
    return $payment->user_id === $user->id || $user->role === 'admin';
}

}
