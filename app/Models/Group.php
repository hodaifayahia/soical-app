<?php

namespace App\Models;

use App\Enums\GroupRoleEnum;
use App\Models\GroupeUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Group extends Model
{
    protected $fillable =['name', 'about','user_id','auto_aprovel','thumbnail_path','cover_path', ];
    use HasFactory;
    use SoftDeletes;
    use HasSlug;
    






    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
    public function currectUserGroup() : HasOne {
        return $this->hasOne(GroupeUser::class)->where('user_id',Auth::id());
    }

    public function isAdmin($userId) : bool {
        return $this->currectUserGroup?->user_id == $userId;
    }
    public function adminuser() : BelongsTo {
        return $this->belongsTo(User::class , 'created_by');
    }
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function group() : BelongsTO {
        return $this->belongsTo(Group::class);
    }
    public function adminusers() : BelongsToMany {
        return $this->belongsToMany(User::class, 'groupe_users')
            ->wherePivot('role', GroupRoleEnum::ADMIN);
    }
}
