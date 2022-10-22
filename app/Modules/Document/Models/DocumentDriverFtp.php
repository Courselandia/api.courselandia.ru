<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use App\Modules\Document\Contracts\DocumentDriver;
use Config;
use App\Models\Exceptions\FtpException;
use Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Класс драйвер хранения документов с использованием FTP протокола.
 */
class DocumentDriverFtp extends DocumentDriver
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
        self::$connection = ftp_connect(Config::get('document.store.ftp.server'));

        if (!self::$connection) {
            throw new FtpException(
                trans(
                    'document::models.documentDriverFtp.errorConnection',
                    ['server' => Config::get('document.store.ftp.server')]
                )
            );
        }

        $status = self::$login = ftplogin(
            self::$connection,
            Config::get('document.store.ftp.login'),
            Config::get('document.store.ftp.password')
        );

        if (!$status) {
            throw new FtpException(
                trans(
                    'document::models.documentDriverFtp.errorLogin',
                    [
                        'login' => Config::get('document.store.ftp.login'),
                        'server' => Config::get('document.store.ftp.server')
                    ]
                )
            );
        }

        $status = ftp_pasv(self::$connection, true);

        if (!$status) {
            throw new FtpException(
                trans(
                    'document::models.documentDriverFtp.errorPassiveMode',
                    ['server' => Config::get('document.store.ftp.server')]
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
                    'document::models.documentDriverFtp.errorConnection',
                    ['server' => Config::get('document.store.ftp.server')]
                )
            );
        }
    }

    /**
     * Метод получения пути к документу.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет путь к документу.
     */
    public function path(string $folder, int|string $id, string $format): ?string
    {
        return 'doc/read/'.$folder.'/'.$id.'.'.$format;
    }

    /**
     * Метод получения физического пути к документу.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет физический путь к документу.
     */
    public function pathSource(string $folder, int|string $id, string $format): ?string
    {
        return Config::get('app.api_url').$this->path($folder, $id, $format);
    }

    /**
     * Метод чтения документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет байт код документа.
     * @throws FtpException
     */
    public function read(string $folder, int|string $id, string $format): ?string
    {
        if (self::$connection && self::$login) {
            $name = storage_path('app/tmp/'.$id.'.'.$format);

            if (!Storage::disk('tmp')->exists($id.'.'.$format)) {
                $file = Config::get('document.store.ftp.path').'/'.'/'.$folder.'/'.$id.'.'.$format;
                $status = ftp_get(self::$connection, $name, $file);

                if (!$status) {
                    throw new FtpException(trans('document::models.documentDriverFtp.errorRead', ['file' => $file]));
                }
            }

            return Storage::disk('tmp')->get($id.'.'.$format);
        }

        return null;
    }

    /**
     * Метод создания документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности создания документа.
     * @throws FtpException
     */
    public function create(string $folder, int|string $id, string $format, string $path): bool
    {
        if (self::$connection && self::$login) {
            $file = Config::get('document.store.ftp.path').'/'.$folder.'/'.$id.'.'.$format;
            $status = ftp_put(self::$connection, $file, $path);

            if (!$status) {
                throw new FtpException(
                    trans('document::models.documentDriverFtp.errorCreate', ['path' => $path, 'file' => $file])
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Метод обновления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности обновления документа.
     * @throws FtpException
     */
    public function update(string $folder, int|string $id, string $format, string $path): bool
    {
        if (self::$connection && self::$login) {
            $file = Config::get('document.store.ftp.path').'/'.$folder.'/'.$id.'.'.$format;
            $status = ftp_put(self::$connection, $file, $path);

            if (!$status) {
                throw new FtpException(
                    trans('document::models.documentDriverFtp.errorUpdate', ['path' => $path, 'file' => $file])
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Метод удаления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return bool Вернет статус успешности удаления документа.
     * @throws FtpException
     */
    public function destroy(string $folder, int|string $id, string $format): bool
    {
        if (self::$connection && self::$login) {
            $file = Config::get('document.store.ftp.path').'/'.$folder.'/'.$id.'.'.$format;
            $status = ftp_delete(self::$connection, $file);

            if (!$status) {
                throw new FtpException(trans('document::models.documentDriverFtp.errorDeleting', ['file' => $file]));
            }

            return true;
        }

        return false;
    }
}
