<?php

namespace App\Http\Resources\API\V1\User;

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
        $user = auth()->user();
        return [
            'id'=>$user->id,
            'name'=>$user->name,
            'nameArabic'=>$user->nameArabic,
            'email'=>$user->email,
            'termsAccepted'=>$user->termsAccepted,
            'status'=>$user->status,
            'profile'=>$user->profile,
            'communication'=>$user->communication,
            'passport'=>$user->passport,
            'address'=>$user->address,
            'qatarInfo'=>$user->qatarInfo,
            'role' => $user->roles->pluck('name')->first(),
            'permissions' => $user->roles->first()->permissions->pluck('name')->all(),
        ];
    }
}
