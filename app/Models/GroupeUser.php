<?php

namespace App\Models;

use App\Enums\GroupRoleEnum;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class GroupeUser extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

    protected $fillable =['status','role','user_id','group_id','created_by' , 'token',
    'token_expire_date'];


   
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
