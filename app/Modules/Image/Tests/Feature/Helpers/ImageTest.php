<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Tests\Feature\Helpers;

use Size;
use ImageStore;
use App\Modules\Image\Entities\Image as ImageEntity;
use App\Modules\Image\Helpers\Image;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Тестирование: Класс помощника для сохранения и получения изображений.
 */
class ImageTest extends TestCase
{
    /**
     * Сохранение изображения.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $image = UploadedFile::fake()->image('test.jpg', 1000, 1000);

        $result = Image::set('test', $image, function (string $name, UploadedFile $value) {
            $path = ImageStore::tmp($value->getClientOriginalExtension());

            Size::make($value)->resize(
                300,
                300,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            )->crop(300, 300)->save($path);

            ImageStore::setFolder('test');
            $image = new ImageEntity();
            $image->path = $path;

            return ImageStore::create($image);
        });

        $this->assertNotNull($result);
    }

    /**
     * Обновление изображения.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $image = UploadedFile::fake()->image('test.jpg', 1000, 1000);

        $result = Image::set('test', $image, function (string $name, UploadedFile $value) {
            $path = ImageStore::tmp($value->getClientOriginalExtension());

            Size::make($value)->resize(
                60,
                60,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            )->crop(60, 60)->save($path);

            ImageStore::setFolder('test');
            $image = new ImageEntity();
            $image->path = $path;

            $result = ImageStore::create($image);

            return ImageStore::update($result, $image);
        });

        $this->assertNotNull($result);
    }
}
