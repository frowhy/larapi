<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/9/7
 * Time: 下午5:16
 */

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Image;
use Storage;

trait ThumbnailTrait
{
    /**
     * @param Model|string $model
     * @param                     $id
     * @param UploadedFile|string $originalImage
     * @param array $options
     *
     * @return bool
     */
    private function saveThumbnail(string $model, $id, $originalImage, $options = [])
    {
        $disk = Storage::disk(config('thumbnail.disk'));
        $filename = $this->makeThumbnail($originalImage);
        $fetch = app($model)->find($id);
        if ($fetch->thumbnail !== '') {
            $disk->delete($fetch->thumbnail);
        }
        $fetch->thumbnail = $filename;

        return $fetch->save($options);
    }

    /**
     * @param UploadedFile|string $originalImage
     * @param                     $andOriginalImage
     *
     * @return array|string
     */
    private function makeThumbnail($originalImage, $andOriginalImage = false)
    {
        $disk = Storage::disk(config('thumbnail.disk'));
        $presetWidth = config('thumbnail.width');
        $presetHeight = config('thumbnail.height');
        $image = Image::make($originalImage);
        $ext = $image->extension ?? $originalImage->getClientOriginalExtension();
        if ($andOriginalImage) {
            $originalImagePath = config('admin.upload.directory.image').'/'.md5(uniqid()).'.'.$ext;
            $disk->put($originalImagePath, $image->stream($ext, 100));
        }
        if ($image->width() === $image->height()) {
            $image->resize($presetWidth, $presetHeight);
        } else {
            if ($image->width() > $image->height()) {
                $image->heighten($presetHeight);
                $image->widen($presetWidth);
            } else if ($image->width() < $image->height()) {
                $image->widen($presetWidth);
                $image->heighten($presetHeight);
            }
            $image->resizeCanvas($presetWidth, $presetHeight);
        }
        $thumbnailPath = config('thumbnail.path').md5(uniqid()).'.'.$ext;
        $disk->put($thumbnailPath, $image->stream($ext, config('thumbnail.quality')));

        /** @var string $originalImagePath */
        return $andOriginalImage ? ['original_image' => $originalImagePath, 'thumbnail' => $thumbnailPath]
            : $thumbnailPath;
    }
}
