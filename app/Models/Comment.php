<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'comment', 'user_id', 'parent_id'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post() : BelongTo {
         return $this->belongsTo(Post::class);
    }
    public function comments() : HasMany {
         return $this->hasMany(self::class , 'parent_id');
    }

public function reactions()
{
    return $this->morphMany(Reaction::class, 'object');
}
    
 
}



