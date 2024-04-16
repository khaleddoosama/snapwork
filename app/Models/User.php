<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\UploadTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, UploadTrait, Sluggable;


    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'username'
            ]
        ];
    }

    /* Scopes */
    // scope student
    public function scopeStudent($query)
    {
        return $query->where('role', 'student');
    }

    // scope instructor
    public function scopeInstructor($query)
    {
        return $query->where('role', 'instructor');
    }

    // scope admin
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // scope student and pending
    public function scopeStudentPending($query)
    {
        return $query->where('role', 'student')->where('status', 0);
    }

    // scope student and active
    public function scopeStudentActive($query)
    {
        return $query->where('role', 'student')->where('status', 1);
    }

    // scope student and inactive
    public function scopeStudentInactive($query)
    {
        return $query->where('role', 'student')->where('status', 2)->orWhere('status', 3);
    }

    /* Relations */
    // relation specialization
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    // relation skills
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    // relation Languages
    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    // relation Projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // relation Educations
    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    // relation Employment
    public function employments()
    {
        return $this->hasMany(EmploymentHistory::class, 'user_id', 'id');
    }

    // relation Certifications
    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    /* methods */
    // set Picture Attribute
    public function setPictureAttribute(UploadedFile $picture)
    {

        $folderName = $this->role . '/pictures';

        $this->deleteIfExists($this->picture); // Delete the old image if it exists
        $this->attributes['picture'] = $this->uploadImage($picture, $folderName);
    }
}
