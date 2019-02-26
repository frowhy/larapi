<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2019/2/26
 * Time: 11:10 AM
 */

namespace Modules\Core\Traits;


trait TransformableTrait
{
    /**
     * @return array
     */
    public function transform()
    {
        return $this->recursive();
    }

    private function recursive($class = null)
    {
        if (null === $class) {
            $class = get_called_class();
        }
        $prefix = str_before($class, 'Entities');
        $model = class_basename($class);
        $className = "{$prefix}Transformers\\{$model}Transformer";

        if (class_exists($className)) {
            $transformer = app($className);
            return $transformer->transform($this);
        } else {
            if ($class !== get_class()) {
                return $this->recursive(get_class());
            } else {
                return [];
            }
        }
    }
}
