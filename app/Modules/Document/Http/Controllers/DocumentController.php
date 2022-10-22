<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Http\Controllers;

use App\Models\Rep\RepositoryQueryBuilder;
use Util;
use DocumentStore;
use Storage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Modules\Document\Http\Requests\DocumentCreateRequest;
use App\Modules\Document\Http\Requests\DocumentUpdateRequest;
use App\Modules\Document\Http\Requests\DocumentDestroyRequest;

/**
 * Класс контроллер для документа.
 */
class DocumentController extends Controller
{
    /**
     * Получение байт кода документа.
     *
     * @param  string  $name  Название документа.
     *
     * @return Response Ответ.
     */
    public function read(string $name): Response
    {
        $info = pathinfo($name);

        $id = substr($info['basename'], 0, Util::strlen($info['basename']) - Util::strlen($info['extension']) - 1);
        $format = strtolower($info['extension']);

        $document = DocumentStore::get(new RepositoryQueryBuilder($id));

        if ($document->format === $format) {
            return (new Response(DocumentStore::getByte($id)))
                ->header('Cache-Control', 'max-age=2592000')
                ->header('Content-type', mime_content_type($name));
        }

        return (new Response(null, 404));
    }

    /**
     * Создание документа.
     *
     * @param  DocumentCreateRequest  $request  Запрос.
     *
     * @return JsonResponse Ответ.
     */
    public function create(DocumentCreateRequest $request): JsonResponse
    {
        $request->file('file')->move(
            storage_path('app/public/documents/'),
            $request->get('id').'.'.$request->get('format')
        );

        return response()->json(['success' => true]);
    }

    /**
     * Обновление документа.
     *
     * @param  DocumentUpdateRequest  $request  Запрос.
     *
     * @return JsonResponse Ответ.
     */
    public function update(DocumentUpdateRequest $request): JsonResponse
    {
        $request->file('file')->move(
            storage_path('app/public/documents/'),
            $request->get('id').'.'.$request->get('format')
        );

        return response()->json(['success' => true]);
    }

    /**
     * Удаление документа.
     *
     * @param  DocumentDestroyRequest  $request  Запрос.
     *
     * @return JsonResponse Ответ.
     */
    public function destroy(DocumentDestroyRequest $request): JsonResponse
    {
        Storage::disk('documents')->delete($request->get('id').'.'.$request->get('format'));

        return response()->json(['success' => true]);
    }
}
