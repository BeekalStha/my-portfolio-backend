<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutMe extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'title',
        'slug',
        'website',
        'email',
        'phone',
        'location',
        'age',
        'bio',
        'description',
        'profile_picture',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
}
