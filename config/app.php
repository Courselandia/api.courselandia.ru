<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'courselandia_ru'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'admin_url' => env('APP_ADMIN_URL'),

    'api_url' => env('APP_API_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => env('APP_LOCALE', 'ru'),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'course_precache' => env('COURSE_PRECACHE', false),

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,

        //

        App\Providers\ValidatorServiceProvider::class,
        App\Providers\CurrencyServiceProvider::class,
        App\Providers\GeocoderServiceProvider::class,
        App\Providers\GeoServiceProvider::class,
        App\Providers\SmsServiceProvider::class,
        App\Providers\MorphServiceProvider::class,
        App\Providers\TypographyServiceProvider::class,

        // Modules

        App\Modules\Access\Providers\AccessServiceProvider::class,
        App\Modules\Access\Providers\RouteServiceProvider::class,

        App\Modules\Article\Providers\ArticleServiceProvider::class,
        App\Modules\Article\Providers\RouteServiceProvider::class,

        App\Modules\OAuth\Providers\OAuthServiceProvider::class,
        App\Modules\OAuth\Providers\RouteServiceProvider::class,

        App\Modules\Act\Providers\ActServiceProvider::class,
        // App\Modules\Act\Providers\RouteServiceProvider::class,

        App\Modules\Task\Providers\TaskServiceProvider::class,
        App\Modules\Task\Providers\RouteServiceProvider::class,

        App\Modules\Alert\Providers\AlertServiceProvider::class,
        App\Modules\Alert\Providers\RouteServiceProvider::class,

        App\Modules\Analyzer\Providers\AnalyzerServiceProvider::class,
        App\Modules\Analyzer\Providers\RouteServiceProvider::class,

        App\Modules\Core\Providers\CoreServiceProvider::class,
        App\Modules\Core\Providers\RouteServiceProvider::class,

        App\Modules\Document\Providers\DocumentServiceProvider::class,
        App\Modules\Document\Providers\RouteServiceProvider::class,

        App\Modules\Employment\Providers\EmploymentServiceProvider::class,
        App\Modules\Employment\Providers\RouteServiceProvider::class,

        App\Modules\Process\Providers\ProcessServiceProvider::class,
        App\Modules\Process\Providers\RouteServiceProvider::class,

        App\Modules\Feedback\Providers\FeedbackServiceProvider::class,
        App\Modules\Feedback\Providers\RouteServiceProvider::class,

        App\Modules\Image\Providers\ImageServiceProvider::class,
        App\Modules\Image\Providers\RouteServiceProvider::class,

        App\Modules\Location\Providers\LocationServiceProvider::class,
        App\Modules\Location\Providers\RouteServiceProvider::class,

        App\Modules\Log\Providers\LogServiceProvider::class,
        App\Modules\Log\Providers\RouteServiceProvider::class,

        App\Modules\Metatag\Providers\MetatagServiceProvider::class,
        //App\Modules\Metatag\Providers\RouteServiceProvider::class,

        App\Modules\Publication\Providers\PublicationServiceProvider::class,
        App\Modules\Publication\Providers\RouteServiceProvider::class,

        App\Modules\User\Providers\UserServiceProvider::class,
        App\Modules\User\Providers\RouteServiceProvider::class,

        App\Modules\Direction\Providers\DirectionServiceProvider::class,
        App\Modules\Direction\Providers\RouteServiceProvider::class,

        App\Modules\Profession\Providers\ProfessionServiceProvider::class,
        App\Modules\Profession\Providers\RouteServiceProvider::class,

        App\Modules\Category\Providers\CategoryServiceProvider::class,
        App\Modules\Category\Providers\RouteServiceProvider::class,

        App\Modules\Skill\Providers\SkillServiceProvider::class,
        App\Modules\Skill\Providers\RouteServiceProvider::class,

        App\Modules\Tool\Providers\ToolServiceProvider::class,
        App\Modules\Tool\Providers\RouteServiceProvider::class,

        App\Modules\School\Providers\SchoolServiceProvider::class,
        App\Modules\School\Providers\RouteServiceProvider::class,

        App\Modules\Teacher\Providers\TeacherServiceProvider::class,
        App\Modules\Teacher\Providers\RouteServiceProvider::class,

        App\Modules\Salary\Providers\SalaryServiceProvider::class,
        App\Modules\Salary\Providers\RouteServiceProvider::class,

        App\Modules\Review\Providers\ReviewServiceProvider::class,
        App\Modules\Review\Providers\RouteServiceProvider::class,

        App\Modules\Faq\Providers\FaqServiceProvider::class,
        App\Modules\Faq\Providers\RouteServiceProvider::class,

        App\Modules\Course\Providers\CourseServiceProvider::class,
        App\Modules\Course\Providers\RouteServiceProvider::class,

        App\Modules\Writer\Providers\WriterServiceProvider::class,
        App\Modules\Writer\Providers\RouteServiceProvider::class,

        App\Modules\Plagiarism\Providers\PlagiarismServiceProvider::class,
        App\Modules\Plagiarism\Providers\RouteServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // Advanced classes
        'Geographer' => MenaraSolutions\Geographer\Integrations\LaravelFacade::class,
        'Debugbar' => Barryvdh\Debugbar\Facades\Debugbar::class,
        'MongoDb' => Jenssegers\Mongodb\Eloquent\Model::class,

        'XmlParser' => Orchestra\Parser\Xml\Facade::class,
        'Size' => Intervention\Image\Facades\Image::class,

        // Own classes
        'Util' => App\Models\Facades\Util::class,
        'Morph' => App\Models\Facades\Morph::class,
        'Typography' => App\Models\Facades\Typography::class,
        'Device' => App\Models\Facades\Device::class,
        'Bot' => App\Models\Facades\Bot::class,
        'Act' => App\Modules\Act\Facades\Act::class,
        'Currency' => App\Models\Facades\Currency::class,
        'Sms' => App\Models\Facades\Sms::class,
        'Geo' => App\Models\Facades\Geo::class,
        'Geocoder' => App\Models\Facades\Geocoder::class,
        'ImageStore' => App\Modules\Image\Facades\Image::class,
        'DocumentStore' => App\Modules\Document\Facades\Document::class,
        'Alert' => App\Modules\Alert\Facades\Alert::class,
        'OAuth' => App\Modules\OAuth\Facades\OAuth::class,
        'Writer' => App\Modules\Writer\Facades\Writer::class,
        'ArticleCategory' => App\Modules\Article\Facades\ArticleCategory::class,
        'Plagiarism' => App\Modules\Plagiarism\Facades\Plagiarism::class,
        'AnalyzerCategory' => App\Modules\Analyzer\Facades\AnalyzerCategory::class,
    ])->toArray(),
];
