<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'deleted_by',
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
        ];
    }

    /**
     * Get the avatar URL attribute.
     * Returns secure avatar URL or default avatar if none exists
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path('storage/avatars/' . $this->avatar))) {
            return asset('storage/avatars/' . $this->avatar);
        }

        // Return default avatar based on user's name initials
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Generate default avatar URL with user initials
     */
    public function getDefaultAvatarUrl(): string
    {
        $initials = $this->getInitials();
        return "https://ui-avatars.com/api/?name=" . urlencode($initials) .
            "&color=ffffff&background=0ea5e9&size=128&rounded=false&bold=true";
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitials(): string
    {
        $names = explode(' ', trim($this->name));
        $initials = '';

        foreach ($names as $name) {
            if (strlen($name) > 0) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
            if (strlen($initials) >= 2)
                break;
        }

        return $initials ?: 'U';
    }

    /**
     * Check if user has a custom avatar
     */
    public function hasCustomAvatar(): bool
    {
        return $this->avatar && file_exists(public_path('storage/avatars/' . $this->avatar));
    }

    /**
     * Delete user's avatar file
     */
    public function deleteAvatar(): bool
    {
        if ($this->avatar && file_exists(public_path('storage/avatars/' . $this->avatar))) {
            unlink(public_path('storage/avatars/' . $this->avatar));
            $this->update(['avatar' => null]);
            return true;
        }
        return false;
    }

    /**
     * Scope for including user roles
     */
    public function scopeWithRoles($query)
    {
        return $query->with('roles');
    }

    /**
     * Scope for searching users
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Get the user who deleted this user
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
