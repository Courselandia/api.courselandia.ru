<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Models;

use App\Modules\Image\Contracts\ImageDriver;
use Config;
use App\Models\Exceptions\FtpException;
use Storage;

/**
 * Класс драйвер хранения изображений с использованием FTP протокола.
 */
class ImageDriverFtp extends ImageDriver
{
    /**
     * Содержит ссылку на подключения по FTP.
     *
     * @var resource
     */
    protected static $connection;

    /**
     * Содержит ссылку на аутентификацию соединения через FTP.
     *
     * @var resource
     */
    protected static $login;

    /**
     * Конструктор.
     * Производим подключение к серверу.
     *
     * @throws FtpException
     */
    public function __construct()
    {
        self::$connection = ftp_connect(Config::get('image.store.ftp.server'));

        if (!self::$connection) {
            throw new FtpException(
                trans(
                    'image::models.imageDriverFtp.errorConnection',
                    ['server' => Config::get('image.store.ftp.server')]
                )
            );
        }

        $status = self::$login = ftplogin(
            self::$connection,
            Config::get('image.store.ftp.login'),
            Config::get('image.store.ftp.password')
        );

        if (!$status) {
            throw new FtpException(
                trans(
                    'image::models.imageDriverFtp.errorLogin',
                    [
                        'login' => Config::get('image.store.ftp.login'),
                        'server' => Config::get('image.store.ftp.server')
                    ]
                )
            );
        }

        $status = ftp_pasv(self::$connection, true);

        if (!$status) {
            throw new FtpException(
                trans(
                    'image::models.imageDriverFtp.errorPassiveMode',
                    ['server' => Config::get('image.store.ftp.server')]
                )
            );
        }
    }

    /**
     * Деструктор.
     * Производим отключение от сервера.
     *
     * @throws FtpException
     */
    public function __destruct()
    {
        $status = ftp_close(self::$connection);

        if (!$status) {
            throw new FtpException(
                trans(
                    'image::models.imageDriverFtp.errorConnection',
                    ['server' => Config::get('image.store.ftp.server')]
                )
            );
        }
    }

    /**
     * Метод получения пути к изображению.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет путь к изображению.
     */
    public function path(string $folder, int|string $id, string $format): ?string
    {
        return 'img/read/'.$folder.'/'.$id.'.'.$format;
    }

    /**
     * Метод получения физического пути к изображению.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет физический путь к изображению.
     */
    public function pathSource(string $folder, int|string $id, string $format): ?string
    {
        return Config::get('app.api_url').$this->path($folder, $id, $format);
    }

    /**
     * Метод чтения изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет байт код изображения.
     * @throws FtpException
     */
    public function read(string $folder, int|string $id, string $format): ?string
    {
        if (self::$connection && self::$login) {
            $name = storage_path('app/tmp/'.$id.'.'.$format);

            if (!Storage::disk('tmp')->exists($id.'.'.$format)) {
                $file = Config::get('image.store.ftp.path').'/'.'/'.$folder.'/'.$id.'.'.$format;
                $status = ftp_get(self::$connection, $name, $file);

                if (!$status) {
                    throw new FtpException(trans('image::models.imageDriverFtp.errorRead', ['file' => $file]));
                }
            }

            return Storage::disk('tmp')->get($id.'.'.$format);
        }

        return null;
    }

    /**
     * Метод создания изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     * @param  string  $path  Путь к изображению.
     *
     * @return bool Вернет статус успешности создания изображения.
     * @throws FtpException
     */
    public function create(string $folder, int|string $id, string $format, string $path): bool
    {
        if (self::$connection && self::$login) {
            $file = Config::get('image.store.ftp.path').'/'.$folder.'/'.$id.'.'.$format;
            $status = ftp_put(self::$connection, $file, $path);

            if (!$status) {
                throw new FtpException(
                    trans('image::models.imageDriverFtp.errorCreate', ['path' => $path, 'file' => $file])
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Метод обновления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     * @param  string  $path  Путь к изображению.
     *
     * @return bool Вернет статус успешности обновления изображения.
     * @throws FtpException
     */
    public function update(string $folder, int|string $id, string $format, string $path): bool
    {
        if (self::$connection && self::$login) {
            $file = Config::get('image.store.ftp.path').'/'.$folder.'/'.$id.'.'.$format;
            $status = ftp_put(self::$connection, $file, $path);

            if (!$status) {
                throw new FtpException(
                    trans('image::models.imageDriverFtp.errorUpdate', ['path' => $path, 'file' => $file])
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Метод удаления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return bool Вернет статус успешности удаления изображения.
     * @throws FtpException
     */
    public function destroy(string $folder, int|string $id, string $format): bool
    {
        if (self::$connection && self::$login) {
            $file = Config::get('image.store.ftp.path').'/'.$folder.'/'.$id.'.'.$format;
            $status = ftp_delete(self::$connection, $file);

            if (!$status) {
                throw new FtpException(trans('image::models.imageDriverFtp.errorDeleting', ['file' => $file]));
            }

            return true;
        }

        return false;
    }
}
