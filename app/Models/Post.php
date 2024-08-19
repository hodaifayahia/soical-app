<?php

namespace App\Models;

use App\Models\Groupe;
use App\Models\PostAttachements;
use App\Models\PostReaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'body',
        'user_id',
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

    public function reactions() : HasMany {
        return $this->hasMany(PostReaction::class);
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
