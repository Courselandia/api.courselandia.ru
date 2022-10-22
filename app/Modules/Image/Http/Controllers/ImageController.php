<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Http\Controllers;

use App\Models\Rep\RepositoryQueryBuilder;
use Util;
use ImageStore;
use Storage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Modules\Image\Http\Requests\ImageCreateRequest;
use App\Modules\Image\Http\Requests\ImageUpdateRequest;
use App\Modules\Image\Http\Requests\ImageDestroyRequest;

/**
 * Класс контроллер для изображения.
 */
class ImageController extends Controller
{
    /**
     * Получение байт кода изображения.
     *
     * @param  string  $name  Название изображения.
     *
     * @return Response Ответ.
     */
    public function read(string $name): Response
    {
        $info = pathinfo($name);

        $id = substr($info['basename'], 0, Util::strlen($info['basename']) - Util::strlen($info['extension']) - 1);
        $format = strtolower($info['extension']);

        $image = ImageStore::get(new RepositoryQueryBuilder($id));

        if ($image->format === $format) {
            $format = null;

            if ($format == 'png') {
                $format = 'image/png';
            } elseif ($format == 'jpg') {
                $format = 'image/jpeg';
            } elseif ($format == 'gif') {
                $format = 'image/gif';
            } elseif ($format == 'swf') {
                $format = 'image/application/x-shockwave-flash';
            }

            if ($format) {
                return (new Response(ImageStore::getByte($id)))
                    ->header('Cache-Control', 'max-age=2592000')
                    ->header('Content-type', $format);
            }
        }

        return (new Response(null, 404));
    }

    /**
     * Создание изображения.
     *
     * @param  ImageCreateRequest  $request  Запрос.
     *
     * @return JsonResponse Ответ.
     */
    public function create(ImageCreateRequest $request): JsonResponse
    {
        $request->file('file')->move(
            storage_path('app/public/images/'),
            $request->get('id').'.'.$request->get('format')
        );

        return response()->json(['success' => true]);
    }

    /**
     * Обновление изображения.
     *
     * @param  ImageUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Ответ.
     */
    public function update(ImageUpdateRequest $request): JsonResponse
    {
        $request->file('file')->move(
            storage_path('app/public/images/'),
            $request->get('id').'.'.$request->get('format')
        );

        return response()->json(['success' => true]);
    }

    /**
     * Удаление изображения.
     *
     * @param  ImageDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Ответ.
     */
    public function destroy(ImageDestroyRequest $request): JsonResponse
    {
        Storage::disk('images')->delete($request->get('id').'.'.$request->get('format'));

        return response()->json(['success' => true]);
    }
}
