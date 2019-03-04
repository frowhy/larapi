<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/29
 * Time: 下午7:15
 */

namespace Modules\Core\Traits;

use ReflectionException;

trait ServiceTrait
{
    protected $service;

    public function __construct()
    {
        try {
            $calledClass = get_called_class();
            $class = new \ReflectionClass($calledClass);
            $shortName = $class->getShortName();
            $model = str_before($shortName, 'Controller');
            $prefix = str_before($calledClass, 'Http\\Controllers');
            $this->service = app("{$prefix}Services\\{$model}Service");
        } catch (ReflectionException $exception) {
            dd($exception);
        }
    }
}
