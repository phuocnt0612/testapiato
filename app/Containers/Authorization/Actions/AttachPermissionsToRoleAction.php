<?php

namespace App\Containers\Authorization\Actions;

use App\Containers\Authorization\Tasks\AttachPermissionsToRoleTask;
use App\Containers\Authorization\Tasks\GetPermissionTask;
use App\Containers\Authorization\Tasks\GetRoleTask;
use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;

/**
 * Class AttachPermissionsToRoleAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class AttachPermissionsToRoleAction extends Action
{

    /**
     * @param \App\Ship\Parents\Requests\Request $request
     *
     * @return  mixed
     */
    public function run(Request $request)
    {
        $role = $this->call(GetRoleTask::class, [$request->role_id]);
        $permissions = [];

        if (is_array($permissionsIds = $request->permissions_ids)) {
            foreach ($permissionsIds as $permissionId) {
                $permissions[] = $this->call(GetPermissionTask::class, [$permissionId]);
            }
        } else {
            $permissions[] = $this->call(GetPermissionTask::class, [$permissionsIds]);
        }

        return $role->givePermissionTo($permissions);
    }
}
