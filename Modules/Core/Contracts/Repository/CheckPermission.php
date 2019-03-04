<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2019-02-28
 * Time: 17:10
 */

namespace Modules\Core\Contracts\Repository;

use App\User;

interface CheckPermission
{
    /**
     * 检查权限
     *
     * @param \App\User $user
     * @param int $id
     *
     * @return mixed
     */
    public function checkPermission(User $user, int $id);
}
