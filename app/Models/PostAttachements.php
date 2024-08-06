<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachements extends Model
{
    use HasFactory;

    const UPDATED_AT = NULL;
    protected $fillable = [
        'post_id',
        'name' ,
        'path',
        'mime',
        'size',
        'created_at',
        'created_by',
    ];
}
