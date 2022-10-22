<?php
/**
 * Основные провайдеры.
 */

namespace App\Providers;

use App\Models\Validators\FilterDateRangeValidator;
use App\Models\Validators\FilterDateValidator;
use App\Models\Validators\FiltersValidator;
use App\Models\Validators\IdsValidator;
use App\Models\Validators\MediaValidator;
use App\Models\Validators\PathValidator;
use App\Models\Validators\SortsValidator;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;

use App\Models\Validators\DateMongoValidator;
use App\Models\Validators\UniqueSoftValidator;
use App\Models\Validators\FloatValidator;
use App\Models\Validators\PhoneValidator;
use App\Models\Validators\FloatBetweenValidator;
use App\Models\Validators\IpMaskValidator;

/**
 * Класс сервис-провайдера для валидации.
 */
class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Обработчик события загрузки приложения.
     */
    public function boot()
    {
        Validator::extend('date_mongo', DateMongoValidator::class);
        Validator::extend('unique_soft', UniqueSoftValidator::class);
        Validator::extend('float', FloatValidator::class);
        Validator::extend('phone', PhoneValidator::class);
        Validator::extend('float_between', FloatBetweenValidator::class);
        Validator::extend('ip_mask', IpMaskValidator::class);
        Validator::extend('media', MediaValidator::class);
        Validator::extend('path', PathValidator::class);
        Validator::extend('ids', IdsValidator::class);
        Validator::extend('sorts', SortsValidator::class);
        Validator::extend('filters', FiltersValidator::class);
        Validator::extend('filter_date_range', FilterDateRangeValidator::class);
        Validator::extend('filter_date', FilterDateValidator::class);

        Validator::replacer('float_between', function ($message, $attribute, $rule, $parameters) {
            $message = str_replace(':min', $parameters[0], $message);
            return str_replace(':max', $parameters[1], $message);
        });

        Validator::replacer('media', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':formats', implode(', ', $parameters), $message);
        });

        Validator::replacer('sorts', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':fields', implode(', ', $parameters), $message);
        });

        Validator::replacer('filters', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':fields', implode(', ', $parameters), $message);
        });

        Validator::replacer('filter_date_range', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':fields', implode(', ', $parameters), $message);
        });

        Validator::replacer('filter_date', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':fields', implode(', ', $parameters), $message);
        });
    }
}
