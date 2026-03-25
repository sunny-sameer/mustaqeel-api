<?php

namespace App\Models\Traits;

use App\Models\RoleLevel;
use Spatie\Permission\Models\Role;

trait HasUserRoleLevels
{
    public function assignRoleLevel(string $role, string $level): void
    {
        $roleLevelId = RoleLevel::whereHas('role', function ($query) use ($role){
                $query->where('name',$role);
            })
            ->where('name',$level)
            ->value('id');

        if (! $roleLevelId) {
            return;
        }

        $this->levels()->sync($roleLevelId);
    }

    public function assignMultiLevel(array $levels)
    {
        foreach ($levels as $key => $value) {
            $roleLevel = RoleLevel::whereHas('role', function ($query) use ($value){
                $query->where('name',$value['role']);
            })
            ->where(['name'=>$value['name'],'level'=>$value['position']])->first();
            if(!isset($roleLevel->id)){
                $role = Role::where('name',$value['role'])->first();
                $roleLevel = RoleLevel::create(['role_id'=>$role->id,'name'=>$value['name'],'level'=>$value['position']]);
            }

            $this->levels()->sync($roleLevel->id);
        }
    }
}
