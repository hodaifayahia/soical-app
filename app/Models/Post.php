<?php

namespace App\Models;

use App\Models\Groupe;
use App\Models\PostAttachements;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->hasMany(PostAttachements::class); // Corrected spelling
    }
}
