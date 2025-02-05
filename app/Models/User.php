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
use App\Models\Scopes\OrderedDescScope;

use Korridor\LaravelHasManyMerged\HasManyMerged;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, UploadTrait, Sluggable, HasManyMergedRelation;

    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
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
    // scope freelancer
    public function scopeFreelancer($query)
    {
        return $query->where('role', 'freelancer');
    }

    // scope client
    public function scopeClient($query)
    {
        return $query->where('role', 'client');
    }

    // scope admin
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }
    // is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // scope client and pending
    public function scopeClientPending($query)
    {
        return $query->where('role', 'client')->where('status', 0);
    }

    // scope client and active
    public function scopeClientActive($query)
    {
        return $query->where('role', 'client')->where('status', 1);
    }

    // scope client and inactive
    public function scopeClientInactive($query)
    {
        return $query->where('role', 'client')->where('status', 2)->orWhere('status', 3);
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
        // return $this->hasMany(Skill::class);
        return $this->belongsToMany(Skill::class, 'user_skills', 'user_id', 'skill_id');
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

    // relation Jobs
    public function jobs()
    {
        return $this->hasMany(Job::class, 'client_id', 'id');
    }

    // relation Invitations
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'freelancer_id', 'id');
    }

    // relation Applications
    public function applications()
    {
        return $this->hasMany(Application::class, 'freelancer_id', 'id');
    }

    // relation Bookmarks
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * @return HasManyMerged<Message>
     */
    public function messages(): HasManyMerged
    {
        return $this->hasManyMerged(Message::class, ['sender_id', 'receiver_id']);
    }


    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }


    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }


    public function rates()
    {
        return $this->hasMany(Rate::class, 'rated_by', 'id');
    }

    // Calculate the total average rating for the user
    public function getTotalAverageRatingAttribute()
    {
        $rates = $this->rates; // Get all rates related to this user
        $values = [];

        foreach ($rates as $rate) {
            $values = array_merge($values, array_column($rate->rates, 'value'));
        }

        // Calculate the average
        $average = count($values) > 0 ? array_sum($values) / count($values) : 0;

        // Round the average to 2 decimal places
        return round($average, 2);
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
