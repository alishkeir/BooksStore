<?php

namespace App\Providers;

use Alomgyar\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if ($this->app->environment('local')) {
        //     $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        //     $this->app->register(TelescopeServiceProvider::class);
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Factory::guessFactoryNamesUsing(function (string $model_name) {
            $namespace = 'Database\\Factories\\';
            $model_name = Str::afterLast($model_name, '\\');

            return $namespace.$model_name.'Factory';
        });
        // if (!Str::contains(Request()->getHttpHost(), '22200') && Request()->getHttpHost() !== 'alomgyar.lh' && Request()->getHttpHost() !== 'alomgyarbe.local') {
        //     $this->app['request']->server->set('HTTPS', true);
        // }
        if (! app()->environment('local')) {
            \URL::forceScheme('https');
        }

        // if (! app()->environment('production')) {
        //     Mail::alwaysTo('dev@email.address');
        // }

        Request::macro('customer', function () {
            return new Customer;
        });

        Blade::directive('huf', function ($money) {
            return "<?php echo number_format($money, 0, ',', ' ') . ' Ft'; ?>";
        });

        Blade::directive('huftizedes', function ($money) {
            return "<?php echo number_format($money, 1, ',', ' ') . ' Ft'; ?>";
        });
    }
}
