<?php namespace M44rt3np44uw\OPSkins\Providers;

use GuzzleHttp\Client;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use M44rt3np44uw\OPSkins\OPSkins;

/**
 * Class IsSteamRIPServiceProvider
 * @package M44rt3np44uw\IsSteamRIP\Providers
 */
class IsSteamRIPServiceProvider extends ServiceProvider
{
    /**
     * Defer
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerIsSteamRIP();
        $this->aliasIsSteamRIP();
    }

    /**
     * Register bindings.
     */
    private function registerBindings()
    {
        $this->app->singleton('GuzzleHttp\Guzzle', function()
        {
            return new Client();
        });
    }

    /**
     * Register IsSteamRIP
     */
    private function registerIsSteamRIP()
    {
        $this->app->bind('M44rt3np44uw\OPSkins\OPSkins', function()
        {
            return new OPSkins($this->app['GuzzleHttp\Guzzle']);
        });
    }

    /**
     * Register alias
     */
    private function aliasIsSteamRIP()
    {
        if(!$this->aliasExists('OPSkins'))
        {
            AliasLoader::getInstance()->alias(
                'OPSkins',
                OPSkins::class
            );
        }
    }

    /**
     * Check if an alias exists.
     *
     * @param $alias
     * @return bool
     */
    private function aliasExists($alias)
    {
        return array_key_exists($alias, AliasLoader::getInstance()->getAliases());
    }

    /**
     * Provides array.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'M44rt3np44uw\OPSkins\OPSkins',
            'GuzzleHttp\Client'
        ];
    }
}