<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\SukhaResetPassword;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'preferences',
        'birthdate',
        'occupation',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'preferences' => 'array',
            'birthdate' => 'date',
        ];
    }

    /**
     * Helper to verify if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relationships
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class)->latest('completed_at');
    }

    public function latestAssessment(): HasOne
    {
        return $this->hasOne(Assessment::class)->latestOfMany('completed_at');
    }

    public function emotionalLogs(): HasMany
    {
        return $this->hasMany(EmotionalLog::class)->orderBy('log_date', 'desc');
    }

    public function techniqueSessions(): HasMany
    {
        return $this->hasMany(TechniqueSession::class)->latest();
    }

    public function aiRecommendations(): HasMany
    {
        return $this->hasMany(AiRecommendation::class)->latest('generated_at');
    }

    public function latestAiRecommendation(): HasOne
    {
        return $this->hasOne(AiRecommendation::class)->latestOfMany('generated_at');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(UserReminder::class)->orderBy('reminder_time');
    }

    /**
     * Returns avatar style key based on gender
     */
    public function avatarGender(): string
    {
        return match ($this->gender) {
            'female' => 'female',
            'non_binary' => 'neutral',
            default => 'male',
        };
    }

    /**
     * Send the password reset notification using Sukha's custom template.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new SukhaResetPassword($token));
    }
}
