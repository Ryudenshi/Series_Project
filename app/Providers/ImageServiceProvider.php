<?php

namespace Intervention\Image;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Зареєструйте будь-які послуги в контейнері.
     *
     * @return void
     */
    public function register()
    {
        // Реєстрація об'єкта для обробки зображень
        $this->app->singleton('image', function ($app) {
            return new ImageManager();
        });
    }

    /**
     * Виконайте будь-які додаткові налаштування після реєстрації.
     *
     * @return void
     */
    public function boot()
    {
        // Виконується при старті додатка
    }
}
