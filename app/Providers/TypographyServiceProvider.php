<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use App\Models\Typography\Typography;
use Illuminate\Support\ServiceProvider;

/**
 * Класс сервис-провайдера для типографирования.
 */
class TypographyServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::bind(
            'typography',
            function () {
                return new Typography();
            }
        );
    }
}
