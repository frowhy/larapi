<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/27
 * Time: 下午7:20
 */

namespace Modules\Core\Traits;

use Session;


/**
 * Trait RepositoryStructureTrait
 * @package Modules\Core\Traits
 * @method \Prettus\Repository\Eloquent\BaseRepository pushCriteria($criteria)
 */
trait RepositoryStructureTrait
{
    /**
     * Specify Model
     *
     * @return string
     */
    public function model()
    {
        $calledClass = get_called_class();
        $prefix = str_before($calledClass, 'Repositories');
        $model = str_before(class_basename($calledClass), 'Repository');

        return "{$prefix}Entities\\{$model}";
    }

    /**
     * Specify Presenter
     *
     * @return mixed
     */
    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }

    /**
     * Boot up the repository, pushing criteria
     *
     */
    public function boot()
    {
        $this->pushCriteria(app('Prettus\\Repository\\Criteria\\RequestCriteria'));
    }

    public function only(array $attributes)
    {
        $model = str_before(class_basename(get_called_class()), 'Repository');
        Session::flash("{$model}.requested_fields", $attributes);

        return $this;
    }

    public function except(array $attributes)
    {
        $model = str_before(class_basename(get_called_class()), 'Repository');
        Session::flash("{$model}.exclude_fields", $attributes);

        return $this;
    }
}
