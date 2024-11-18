<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Groupe;
use App\Models\Post;
use App\Models\PostAttachements;
use App\Models\PostReaction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'body',
        'user_id',
        'comments',
        'group_id',
        
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function group() 
    {
        return $this->belongsTo(Group::class); // Make sure your model name is Group
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
    
    public function isOwner($userId)
    {
        return $this->user_id == $userId; 
    }
    
    // Post.php

    public static function PostFroTimeLine($userId) : Builder {
        return Post::query()// select * form post 
        ->withCount('reactions') // selct count likes in post form reactions
        ->with([
            'comments' => function ($query) {
                $query->withCount('reactions'); // selct count form reactions // select * from comments where post (1,2..)
                  
            }, 'reactions' => function ($query) use ($userId) {
                $query->where('user_id', $userId); //selct reaction in comments where in (1,1,3)
            }
        ])
        
        ->latest() ;// Order posts by latest
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
