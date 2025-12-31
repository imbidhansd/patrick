<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Homeowner extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'homeowners';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'address_line_1',
        'city',
        'state',
        'zip',
        'email_verified',
        'phone_verified',
        'email_otp',
        'phone_otp',
        'email_otp_expires_at',
        'phone_otp_expires_at',
        'password_reset_otp',
        'password_reset_otp_expires_at',
        'password_reset_token',
        'password_reset_token_expires_at',
        'last_login_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_otp',
        'phone_otp',
        'password_reset_otp',
        'password_reset_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'email_otp_expires_at' => 'datetime',
        'phone_otp_expires_at' => 'datetime',
        'password_reset_otp_expires_at' => 'datetime',
        'password_reset_token_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Set the password attribute with hashing.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Check if email is verified.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return (bool) $this->email_verified;
    }

    /**
     * Check if phone is verified.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return (bool) $this->phone_verified;
    }

    /**
     * Mark the email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified' => true,
            'email_otp' => null,
            'email_otp_expires_at' => null,
        ])->save();
    }

    /**
     * Mark the phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified' => true,
            'phone_otp' => null,
            'phone_otp_expires_at' => null,
        ])->save();
    }

    /**
     * Get the full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
