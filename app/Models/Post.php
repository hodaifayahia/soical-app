<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Groupe;
use App\Models\PostAttachements;
use App\Models\PostReaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'body',
        'user_id',
        'comments',
        
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function group() 
    {
        return $this->belongsTo(Groupe::class); // Make sure your model name is Group
    }

    public function attachments() 
    {
        return $this->hasMany(PostAttachements::class)->latest(); // Corrected spelling
    }

  
    
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'object');
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function latest5Comment() : HasMany {
        return $this->hasMany(Comment::class)->latest()->limit(5);
    }

    protected static function boot()
    {
        parent::boot();
    
        static::deleted(function (self $model) {
            if ($model->path) {
                // Delete the file from storage when the model is deleted
                Storage::disk('public')->delete($model->path);
            }
        });
    }
    
    
}
