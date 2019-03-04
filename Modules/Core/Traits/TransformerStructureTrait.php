<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/28
 * Time: 上午10:16
 */

namespace Modules\Core\Traits;

use Modules\Core\Supports\Response;
use Session;

trait TransformerStructureTrait
{
    private $transform;
    private $field;

    public function transform($transform)
    {
        $this->transform = $transform;
        $this->field = $this->fields($transform);
        $this->parseRequestedFields();
        $this->parseExcludeFields();

        return $this->field;
    }

    protected function parseRequestedFields()
    {
        $class = class_basename($this->transform);
        $param = Response::param('requested_fields') ?? Session::get("{$class}.requested_fields");
        if (!is_null($param)) {
            if (is_array($param)) {
                $requestedFields = $param;
            } else {
                $requestedFields = explode(',', $param);
            }
            if ($requestedFields) {
                $data = [];
                foreach ($requestedFields as $requested_field) {
                    if (array_has($this->field, $requested_field)) {
                        array_set($data, $requested_field, array_get($this->field, $requested_field));
                    }
                }
                $this->field = $data;
            }
        }
    }

    protected function parseExcludeFields()
    {
        $class = class_basename($this->transform);
        $param = Response::param('exclude_fields') ?? Session::get("{$class}.exclude_fields");

        if (!is_null($param)) {
            if (is_array($param)) {
                $excludeFields = $param;
            } else {
                $excludeFields = explode(',', $param);
            }
            if ($excludeFields) {
                $this->field = array_except($this->field, $excludeFields);
            }
        }
    }
}
