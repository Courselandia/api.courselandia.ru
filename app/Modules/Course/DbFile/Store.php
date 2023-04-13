<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile;

use Config;
use Storage;
use App\Modules\Course\Entities\CourseRead;

/**
 * Хранилище данных.
 */
class Store
{
    /**
     * Получит курсы из файла на основе введенного фильтра.
     *
     * @param array|null $filters Фильтры.
     *
     * @return CourseRead|null Считанные курсы.
     */
    public static function read($offset = 0, $limit = 36, ?array $sorts = [], ?array $filters = []): ?CourseRead
    {
        if (
            Config::get('app.course_db_file')
            && $offset === 0
            && $limit === 36
            && (isset($sorts['name']) && mb_strtolower($sorts['name']) === 'asc')
        ) {
            if (empty($filters)) {
                $pathFile = '/db/courses/default.obj';

                if (Storage::drive('local')->exists($pathFile)) {
                    $source = Storage::drive('local')->get($pathFile);

                    return unserialize($source);
                }
            } else if (count($filters) === 1) {
                $pathFile = '';

                if (isset($filters['directions-id'])) {
                    $pathFile = '/db/directions/' . $filters['directions-id'] . '.obj';
                } else if (isset($filters['categories-id']) && count($filters['categories-id']) === 1) {
                    $pathFile = '/db/categories/' . $filters['categories-id'][0] . '.obj';
                } else if (isset($filters['professions-id']) && count($filters['professions-id']) === 1) {
                    $pathFile = '/db/professions/' . $filters['professions-id'][0] . '.obj';
                } else if (isset($filters['school-id']) && count($filters['school-id']) === 1) {
                    $pathFile = '/db/schools/' . $filters['school-id'][0] . '.obj';
                } else if (isset($filters['skills-id']) && count($filters['skills-id']) === 1) {
                    $pathFile = '/db/skills/' . $filters['skills-id'][0] . '.obj';
                } else if (isset($filters['teachers-id']) && count($filters['teachers-id']) === 1) {
                    $pathFile = '/db/teachers/' . $filters['teachers-id'][0] . '.obj';
                } else if (isset($filters['tools-id']) && count($filters['tools-id']) === 1) {
                    $pathFile = '/db/tools/' . $filters['tools-id'][0] . '.obj';
                }

                if ($pathFile && Storage::drive('local')->exists($pathFile)) {
                    $source = Storage::drive('local')->get($pathFile);

                    return unserialize($source);
                }
            }
        }

        return null;
    }
}
