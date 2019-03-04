<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2019-02-28
 * Time: 17:11
 */

namespace Modules\Core\Traits\Repository;

use Modules\User\Entities\User;

/**
 * @property \Illuminate\Database\Eloquent\Model model
 */
trait CheckPermissionTrait
{
    /**
     * 检查权限
     *
     * @param \Modules\User\Entities\User $user
     * @param int $id
     *
     * @return bool
     */
    public function checkPermission(User $user, int $id): bool
    {
        return $this->model->where(['id' => $id, 'user_id' => $user->id])->exists();
    }
}
