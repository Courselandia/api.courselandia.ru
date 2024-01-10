<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Images;

use Size;
use ImageStore;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Helpers\Image;
use CodeBuds\WebPConverter\WebPConverter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use SVG\SVG;

/**
 * Среднее изображение.
 */
class ImageMiddle implements CastsAttributes
{
    /**
     * Получение.
     *
     * @param Model $model Модель.
     * @param string $key Ключ.
     * @param mixed $value Значение.
     * @param array $attributes Атрибуты.
     *
     * @return ImageEntity|null Маленькое изображение.
     */
    public function get($model, string $key, mixed $value, array $attributes): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get($value);
        }

        return $value;
    }

    /**
     * Установка.
     *
     * @param Model $model Модель.
     * @param string $key Ключ.
     * @param mixed $value Значение.
     * @param array $attributes Атрибуты.
     *
     * @return null|int|string Путь к изображению или его ID.
     * @throws ParameterInvalidException
     */
    public function set($model, string $key, mixed $value, array $attributes): null|int|string
    {
        return Image::set(
            $key,
            $value,
            function (string $key, UploadedFile $value) {
                $folder = 'courses';

                if ($value->getClientOriginalExtension() === 'svg') {
                    $imageSvg = SVG::fromFile($value->path());
                    $imageRaster = $imageSvg->toRasterImage(500, 500);
                    $path = ImageStore::tmp('png');
                    imagepng($imageRaster, $path);

                    $image = Size::make($path);
                } else {
                    $path = ImageStore::tmp($value->getClientOriginalExtension());
                    $image = Size::make($value);
                }

                $image->resize(
                    500,
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->save($path);

                $imageWebp = WebPConverter::createWebpImage($path, ['saveFile' => true]);

                ImageStore::setFolder($folder);
                $image = new ImageEntity();
                $image->path = $imageWebp['path'];

                if (isset($attributes[$key]) && $attributes[$key] !== '') {
                    return ImageStore::update($attributes[$key], $image);
                }

                return ImageStore::create($image);
            }
        );
    }
}
