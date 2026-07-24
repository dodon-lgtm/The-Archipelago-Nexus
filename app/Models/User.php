<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function penawarans(): HasMany
    {
        return $this->hasMany(Penawaran::class, 'freelancer_id');
    }

    public function savedProjects(): HasMany
    {
        return $this->hasMany(SavedProject::class, 'freelancer_id');
    }

    public function savedProjectsList()
    {
        return $this->belongsToMany(Project::class, 'saved_projects', 'freelancer_id', 'project_id')
            ->withTimestamps();
    }

    public function workspacesAsCompany(): HasMany
    {
        return $this->hasMany(Workspace::class, 'company_id');
    }

    public function workspacesAsFreelancer(): HasMany
    {
        return $this->hasMany(Workspace::class, 'freelancer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function projectSubmissions(): HasMany
    {
        return $this->hasMany(ProjectSubmission::class, 'submitted_by');
    }
}
