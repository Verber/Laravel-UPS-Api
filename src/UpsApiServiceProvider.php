<?php

namespace Ptondereau\LaravelUpsApi;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Ups\AddressValidation;
use Ups\Locator;
use Ups\QuantumView;
use Ups\Rate;
use Ups\TimeInTransit;
use Ups\Tracking;
use Ups\Tradeability;

/**
 * This is the Ups Api service provider class.
 *
 * @author Pierre Tondereau <pierre@doers.fr>
 */
class UpsApiServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAddressValidation();
        $this->registerQuantumView();
        $this->registerTracking();
        $this->registerRate();
        $this->registerTimeInTransit();
        $this->registerLocator();
        $this->registerTradeability();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/ups.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('ups.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('ups');
        }
        $this->mergeConfigFrom($source, 'ups');
    }

    /**
     * Register the AddressValidation class.
     *
     * @return void
     */
    protected function registerAddressValidation()
    {
        $this->app->singleton('ups.address-validation', function (Container $app) {
            $config = $app->config->get('ups');

            return new AddressValidation($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Register the QuantumView class.
     *
     * @return void
     */
    protected function registerQuantumView()
    {
        $this->app->singleton('ups.quantum-view', function (Container $app) {
            $config = $app->config->get('ups');

            return new QuantumView($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Register the Tracking class.
     *
     * @return void
     */
    protected function registerTracking()
    {
        $this->app->singleton('ups.tracking', function (Container $app) {
            $config = $app->config->get('ups');

            return new Tracking($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Register the Rate class.
     *
     * @return void
     */
    protected function registerRate()
    {
        $this->app->singleton('ups.rate', function (Container $app) {
            $config = $app->config->get('ups');

            return new Rate($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Register the TimeInTransit class.
     *
     * @return void
     */
    protected function registerTimeInTransit()
    {
        $this->app->singleton('ups.time-in-transit', function (Container $app) {
            $config = $app->config->get('ups');

            return new TimeInTransit($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Register the Locator class.
     *
     * @return void
     */
    protected function registerLocator()
    {
        $this->app->singleton('ups.locator', function (Container $app) {
            $config = $app->config->get('ups');

            return new Locator($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Register the Tradeability class.
     *
     * @return void
     */
    protected function registerTradeability()
    {
        $this->app->singleton('ups.tradeability', function (Container $app) {
            $config = $app->config->get('ups');

            return new Tradeability($config['access_key'], $config['user_id'], $config['password']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'ups.address-validation',
            'ups.quantum-view',
            'ups.tracking',
            'ups.rate',
            'ups.time-in-transit',
            'ups.locator',
            'ups.tradeability',
        ];
    }
}
