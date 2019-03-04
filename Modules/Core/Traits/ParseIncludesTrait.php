<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/28
 * Time: 上午9:44
 */

namespace Modules\Core\Traits;

use Modules\Core\Supports\Response;

/**
 * Trait PresenterTrait
 *
 * @package Modules\Core\Traits\PresenterTrait
 * @property \League\Fractal\Manager $fractal
 */
trait ParseIncludesTrait
{
    /**
     * @return $this
     */
    protected function parseIncludes()
    {
        $paramIncludes = config('repository.fractal.params.include', 'include');
        $param = Response::param($paramIncludes);

        if (!is_null($param)) {
            $this->fractal->parseIncludes($param);
        }

        return $this;
    }
}
