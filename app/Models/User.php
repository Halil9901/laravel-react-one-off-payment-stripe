<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use function Illuminate\Events\queueable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    protected static function booted(): void
    {
        static::creating(fn (User $model) =>
            \Log::info('Creating user with email: ' . $model->email)
        );

        static::updating(queueable(function (User $customer) {
            \Log::info('Syncing Stripe customer details');
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getHasPaidAttribute(): bool
    {
        return $this->payment !== null && $this->payment->payment_status === 'paid';
    }
}
