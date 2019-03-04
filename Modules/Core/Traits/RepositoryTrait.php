<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/29
 * Time: 下午7:16
 */

namespace Modules\Core\Traits;

trait RepositoryTrait
{
    /**
     * @var \Prettus\Repository\Eloquent\BaseRepository|RepositoryStructureTrait
     */
    protected $repository;

    public function __construct()
    {
        $calledClass = get_called_class();
        $prefix = str_before($calledClass, 'Services');
        $model = str_before(class_basename($calledClass), 'Service');
        $this->repository = app("{$prefix}Repositories\\{$model}RepositoryEloquent");
    }
}
