<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

   
    protected $fillable = ['object_type' , 'object_id', 'user_id', 'type'];
  

      /**
     * Get the parent commentable model (post or comment).
     */
    public function object()
    {
        return $this->morphTo();
    }
}
