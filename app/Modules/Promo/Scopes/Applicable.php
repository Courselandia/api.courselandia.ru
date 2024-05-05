<?php
/**
 * Модуль промоматериалов.
 * Этот модуль содержит все классы для работы с промоматериалами: промокоды и промоакции.
 *
 * @package App\Modules\Promo
 */

namespace App\Modules\Promo\Scopes;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Трейт для модели, которая реализована для промоматериалов.
 *
 * @method static Builder applicable()
 */
trait Applicable
{
    /**
     * Заготовка запроса для получения только активных промокодов.
     *
     * @param Builder $query Запрос.
     */
    public function scopeApplicable(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->where(function ($query) {
                $query
                    ->whereNull('date_start')
                    ->whereNull('date_end');
            })->orWhere(function ($query) {
                $query
                    ->where(DB::raw("'" . Carbon::now()->format('Y-m-d') . "'"), '>=', DB::raw('date_start'))
                    ->whereNull('date_end');
            })->orWhere(function ($query) {
                $query
                    ->whereNull('date_start')
                    ->where(DB::raw("'" . Carbon::now()->startOfDay()->format('Y-m-d') . "'"), '<=', DB::raw('date_end'));
            })->orWhere(function ($query) {
                $query
                    ->where(DB::raw("'" . Carbon::now()->format('Y-m-d') . "'"), '>=', DB::raw('date_start'))
                    ->where(DB::raw("'" . Carbon::now()->startOfDay()->format('Y-m-d') . "'"), '<=', DB::raw('date_end'));
            });
        })->where('status', 1);
    }
}
