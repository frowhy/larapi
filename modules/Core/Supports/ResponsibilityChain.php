<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2019-03-08
 * Time: 11:23
 */

namespace Modules\Core\Supports;

class ResponsibilityChain
{
    private $isError = false;
    private $result = null;
    private $lastResult = null;
    private $lastErrorResult = null;

    public function append(Response $result, bool $isLastResult = false): ResponsibilityChain
    {
        if (!$this->isError) {
            $this->result = $result;

            if (!is_true($result)) {
                $this->isError = true;
            }
        }

        if (is_true($isLastResult)) {
            if (!is_true($result)) {
                $this->lastErrorResult = $result;
            } else {
                $this->lastResult = $result;
            }
        }

        return $this;
    }

    public function handle(): Response
    {
        if (!$this->isError && !is_null($this->lastResult)) {
            return $this->lastResult;
        }

        if ($this->isError && !is_null($this->lastErrorResult)) {
            return $this->lastErrorResult;
        }

        return $this->result;
    }
}
