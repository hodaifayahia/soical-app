<?php

namespace App\Http\Requests;

use App\Enums\GroupStatusEnum;
use App\Models\Group;
use App\Models\GroupeUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InviteUserRequest extends FormRequest
{
    public ?User $user = null;
    public Group $group ;
    public ?GroupeUser $groupuser = null;

    public function authorize(): bool
    {
        $this->group = $this->route('group');
        return $this->group->isAdmin(Auth::id());
    }

    public function rules(): array
    {
        return [
            'email' => [
                'string',
                function ($attribute, $value, \Closure $fail) {
                    $this->user = User::query()
                        ->where('email', $value)
                        ->orWhere('username', $value)
                        ->first();

                    if (!$this->user) {
                        $fail('User does not exist.');
                    }
                    $this->groupuser = GroupeUser::where('user_id',$this->user->id)->where('group_id',$this->group->id)->first();

                    if ($this->groupuser && $this->groupuser->status === GroupStatusEnum::APPROVED ) {
                        $fail('User is already  joined to the Group.');
                    }

                    
                },
            ],
        ];
    }
}