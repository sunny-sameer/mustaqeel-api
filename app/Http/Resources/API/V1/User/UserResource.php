<?php

namespace App\Http\Resources\API\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->id);
        return [
            'id'=>$user->id,
            'name'=>$user->name,
            'nameArabic'=>$user->nameArabic,
            'email'=>$user->email,
            'termsAccepted'=>$user->termsAccepted,
            'status'=>$user->status,
            'profile'=>$user->profile ?? NULL,
            'communication'=>$user->communication ?? NULL,
            'passport'=>$user->passport ?? NULL,
            'address'=>$user->address ?? NULL,
            'qatarInfo'=>$user->qatarInfo ?? NULL,
            'role' => $user->roles->pluck('name')->first(),
            'roleType' => $user->roles->pluck('type')->first(),
            'roleLevel' => $user->levels->pluck('level')->first(),
            'permissions' => $user->roles->first()->permissions->pluck('name')->all(),
        ];
    }
}
