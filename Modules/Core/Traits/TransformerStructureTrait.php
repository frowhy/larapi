<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/28
 * Time: 上午10:16
 */

namespace Modules\Core\Traits;

use Modules\Core\Abstracts\TransformerAbstract;
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
                foreach ($requestedFields as $requestedField) {

                    if ($this instanceof TransformerAbstract) {

                        $scope = null;

                        if (str_contains($requestedField, '.')) {
                            $requestedFieldArray = explode('.', $requestedField);
                            $scope = array_first($requestedFieldArray);
                            $requestedField = array_last($requestedFieldArray);
                        }

                        if ($scope === $this->getCurrentScope()->getScopeIdentifier()) {
                            if (array_has($this->field, $requestedField)) {
                                array_set($data, $requestedField, array_get($this->field, $requestedField));
                            }
                        }

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

                foreach ($excludeFields as $excludeField) {

                    if ($this instanceof TransformerAbstract) {

                        $scope = null;

                        if (str_contains($excludeField, '.')) {
                            $excludeFieldArray = explode('.', $excludeField);
                            $scope = array_first($excludeFieldArray);
                            $excludeField = array_last($excludeFieldArray);
                        }

                        if ($scope === $this->getCurrentScope()->getScopeIdentifier()) {
                            if (array_has($this->field, $excludeField)) {
                                $this->field = array_except($this->field, $excludeField);
                            }
                        }
                    }
                }
            }
        }
    }
}
