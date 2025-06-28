<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'level',
        'profiency',
        'description',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function aboutMe()
    {
        return $this->belongsTo(AboutMe::class);
    }
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected static function boot()
{
    parent::boot();

    static::creating(function ($skill) {
        if (empty($skill->slug)) {
            $slug = Str::slug($skill->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Skill::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $skill->slug = $slug;
        }
    });
}

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
}
