<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/9/7
 * Time: 下午5:50
 */

namespace Modules\Core\Traits;

use Storage;

trait ModelThumbnailTrait
{
    private $disk;

    private function getDiskInstance()
    {
        if (null === $this->disk) {
            $this->disk = Storage::disk(config('thumbnail.disk'));
        }

        return $this->disk;
    }

    public function getOriginalImageAttribute($value)
    {
        return $this->getStorageUrl($value);
    }

    public function getThumbnailAttribute($value)
    {
        return $this->getStorageUrl($value);
    }

    private function replace_seeder($value)
    {
        return str_replace(['storage/app/public/', '//'], ['', '/'], $value);
    }

    private function getStorageUrl($value)
    {
        return $value === null
            ? $value
            : $this->getDiskInstance()
                   ->url($this->replace_seeder($this->urlEncode($value)));
    }

    private function urlEncode($value)
    {
        $array = collect(explode('/', $value));

        return $array->put($array->count() - 1, urlencode($array->last()))->implode('/');
    }
}
