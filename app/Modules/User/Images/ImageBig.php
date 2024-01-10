<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Images;

use Size;
use ImageStore;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Helpers\Image;
use CodeBuds\WebPConverter\WebPConverter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

/**
 * Большое изображение.
 */
class ImageBig implements CastsAttributes
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
     * @return null|int|string ID запись изображения.
     * @throws ParameterInvalidException
     */
    public function set($model, string $key, mixed $value, array $attributes): null|int|string
    {
        return Image::set($key, $value, function (string $key, UploadedFile $value) use ($attributes) {
            $folder = 'users';
            $path = ImageStore::tmp($value->getClientOriginalExtension());

            Size::make($value)->resize(
                1200,
                null,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            )->save($path);

            $imageWebp = $value->getClientOriginalExtension() !== 'webp'
                ? WebPConverter::createWebpImage($path, ['saveFile' => true])
                : ['path' => $path];

            ImageStore::setFolder($folder);
            $image = new ImageEntity();
            $image->path = $imageWebp['path'];

            if (isset($attributes[$key]) && $attributes[$key] !== '') {
                return ImageStore::update($attributes[$key], $image);
            }

            return ImageStore::create($image);
        });
    }
}
