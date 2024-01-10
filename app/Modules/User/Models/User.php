<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Models;

use App\Modules\Task\Models\Task;
use App\Modules\User\Filters\UserFilter;
use CodeBuds\WebPConverter\WebPConverter;
use Exception;
use Size;
use ImageStore;
use Eloquent;
use App\Models\Status;
use App\Models\Flags;
use App\Models\Delete;
use App\Models\Sortable;
use EloquentFilter\Filterable;
use App\Models\Validate;
use Illuminate\Http\UploadedFile;
use App\Models\BelongsToOneTrait;
use App\Modules\Image\Helpers\Image;
use App\Modules\User\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Image\Entities\Image as ImageEntity;

/**
 * Класс модель для таблицы пользователей на основе Eloquent.
 *
 * @property int|string $id
 * @property int|string|UploadedFile|ImageEntity $image_small_id Маленькое изображение.
 * @property int|string|UploadedFile|ImageEntity $image_middle_id Среднее изображение.
 * property int|string|UploadedFile|ImageEntity $image_big_id Большое изображение.
 * @property string $login Логин.
 * @property string $password Пароль.
 * @property string $remember_token Токен.
 * @property string $first_name Имя.
 * @property string $second_name Фамилия.
 * @property string $phone Телефон.
 * @property bool $two_factor Двухфакторная аутентификация.
 * @property bool $status Значение статуса.
 *
 * @property-read UserRecovery $recovery
 * @property-read UserVerification $verification
 * @property-read UserAuth $auth
 * @property-read UserRole $role
 * @property-read Task[] $tasks
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use BelongsToOneTrait;
    use Delete;
    use Flags;
    use HasFactory;
    use Sortable;
    use Notifiable;
    use SoftDeletes;
    use Status;
    use Validate;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'image_small_id',
        'image_middle_id',
        'image_big_id',
        'login',
        'password',
        'remember_token',
        'first_name',
        'second_name',
        'phone',
        'two_factor',
        'status',
        'flags'
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'image_small_id' => 'max:255',
            'image_middle_id' => 'max:255',
            'image_big_id' => 'max:255',
            'login' => 'required|email|between:1,191',
            'password' => 'required',
            'first_name' => 'nullable|max:191',
            'second_name' => 'nullable|max:191',
            'phone' => 'nullable|max:30',
            'two_factor' => 'nullable|boolean',
            'status' => 'required|boolean'
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    protected function getNames(): array
    {
        return [
            'image_small_id' => trans('user::models.user.imageSmallId'),
            'image_middle_id' => trans('user::models.user.imageMiddleId'),
            'image_big_id' => trans('user::models.user.imageBigId'),
            'login' => trans('user::models.user.login'),
            'password' => trans('user::models.user.password'),
            'remember_token' => trans('user::models.user.rememberToken'),
            'first_name' => trans('user::models.user.firstName'),
            'second_name' => trans('user::models.user.secondName'),
            'phone' => trans('user::models.user.phone'),
            'two_factor' => trans('user::models.user.twoFactor'),
            'status' => trans('user::models.user.status')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(UserFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    /**
     * Определяем свойство, которое хранит значение в модели для канала отправки сообщения по средствам телефона.
     *
     * @return string|null
     */
    public function routeNotificationForPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Определяем свойство, которое хранит значение в модели для канала отправки сообщения по средствам e-mail.
     *
     * @return string|null
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->login;
    }

    /**
     * Определяем свойство, которое хранит значение в модели для канала отправки сообщения по средствам Nexmo.
     *
     * @return string|null
     */
    public function routeNotificationForNexmo(): ?string
    {
        return $this->phone;
    }

    /**
     * Преобразователь атрибута - запись: маленькое изображение.
     *
     * @param mixed $value Значение атрибута.
     *
     * @return void
     * @throws Exception
     */
    public function setImageSmallIdAttribute(mixed $value): void
    {
        $name = 'image_small_id';
        $folder = 'users';

        $this->attributes[$name] = Image::set($name, $value, function (string $name, UploadedFile $value) use ($folder) {
            $path = ImageStore::tmp($value->getClientOriginalExtension());

            Size::make($value)->fit(60, 60)->save($path);

            $imageWebp = $value->getClientOriginalExtension() !== 'webp'
                ? WebPConverter::createWebpImage($path, ['saveFile' => true])
                : ['path' => $path];

            ImageStore::setFolder($folder);
            $image = new ImageEntity();
            $image->path = $imageWebp['path'];

            if (isset($this->attributes[$name]) && $this->attributes[$name] !== '') {
                return ImageStore::update($this->attributes[$name], $image);
            }

            return ImageStore::create($image);
        });
    }

    /**
     * Преобразователь атрибута - получение: маленькое изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Маленькое изображение.
     */
    public function getImageSmallIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get($value);
        }

        return $value;
    }

    /**
     * Преобразователь атрибута - запись: среднее изображение.
     *
     * @param mixed $value Значение атрибута.
     *
     * @return void
     * @throws Exception
     */
    public function setImageMiddleIdAttribute(mixed $value): void
    {
        $name = 'image_middle_id';
        $folder = 'users';

        $this->attributes[$name] = Image::set($name, $value, function (string $name, UploadedFile $value) use ($folder) {
            $path = ImageStore::tmp($value->getClientOriginalExtension());

            Size::make($value)->resize(
                350,
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

            if (isset($this->attributes[$name]) && $this->attributes[$name] !== '') {
                return ImageStore::update($this->attributes[$name], $image);
            }

            return ImageStore::create($image);
        });
    }

    /**
     * Преобразователь атрибута - получение: среднее изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Среднее изображение.
     */
    public function getImageMiddleIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get($value);
        }

        return $value;
    }

    /**
     * Преобразователь атрибута - запись: большое изображение.
     *
     * @param mixed $value Значение атрибута.
     *
     * @return void
     * @throws Exception
     */
    public function setImageBigIdAttribute(mixed $value): void
    {
        $name = 'image_big_id';
        $folder = 'users';

        $this->attributes[$name] = Image::set($name, $value, function (string $name, UploadedFile $value) use ($folder) {
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

            if (isset($this->attributes[$name]) && $this->attributes[$name] !== '') {
                return ImageStore::update($this->attributes[$name], $image);
            }

            return ImageStore::create($image);
        });
    }

    /**
     * Преобразователь атрибута - получение: среднее изображение.
     *
     * @param  mixed  $value  Значение атрибута.
     *
     * @return ImageEntity|null Среднее изображение.
     */
    public function getImageBigIdAttribute(mixed $value): ?ImageEntity
    {
        if (is_numeric($value) || is_string($value)) {
            return ImageStore::get($value);
        }

        return $value;
    }

    /**
     * Получить запись верификации.
     *
     * @return HasOne Модель верификации пользователя.
     */
    public function verification(): HasOne
    {
        return $this->hasOne(UserVerification::class);
    }

    /**
     * Получить запись восстановления пароля пользователя.
     *
     * @return HasOne Модель восстановления пароля пользователя.
     */
    public function recovery(): HasOne
    {
        return $this->hasOne(UserRecovery::class);
    }

    /**
     * Получить аутентификации пользователя.
     *
     * @return HasMany Модель аутентификации пользователя.
     */
    public function auths(): HasMany
    {
        return $this->hasMany(UserAuth::class);
    }

    /**
     * Получить роль пользователя.
     *
     * @return HasOne Роль пользователя.
     */
    public function role(): HasOne
    {
        return $this->hasOne(UserRole::class, 'user_id', 'id');
    }

    /**
     * Задания пользователя.
     *
     * @return HasMany Модели заданий.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
