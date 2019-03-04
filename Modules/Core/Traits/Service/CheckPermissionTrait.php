<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2019-02-28
 * Time: 17:06
 */

namespace Modules\Core\Traits\Service;

use Modules\Core\Supports\Response;
use Modules\User\Entities\User;

/**
 * @property \Modules\Core\Contracts\Repository\CheckPermission repository
 */
trait CheckPermissionTrait
{
    /**
     * 检查权限
     *
     * @param \Modules\User\Entities\User $user
     * @param int $id
     *
     * @return \Modules\Core\Supports\Response
     */
    public function checkPermission(User $user, int $id)
    {
        $result = $this->repository->checkPermission($user, $id);
        if (!$result) {
            return Response::errorUnauthorized(__('core::default.permission_denied'));
        }

        return Response::success();
    }
}
