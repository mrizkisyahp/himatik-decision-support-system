<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'department_id', 'email_verified_at', 'google_id', 'auth_provider', 'avatar_url', 'nim', 'nickname', 'prodi', 'kelas', 'phone', 'address'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

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


    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'interviewer_id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    public function emailVerificationOtp()
    {
        return $this->hasOne(EmailVerificationOtp::class);
    }

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }
}
