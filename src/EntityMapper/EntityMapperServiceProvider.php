<?php  namespace EntityMapper;

use EntityMapper\Cache\EntityCache;
use EntityMapper\Cache\NullEntityCache;
use Illuminate\Support\ServiceProvider;

class EntityMapperServiceProvider extends ServiceProvider {

    public function register()
    {
        Repository::setApp($this->app);

        if( $this->app->bound('db') )
        {
            Repository::setConnectionResolver($this->app['db']);
        }

        $this->app->singleton('\EntityManager\EntityMapper', function($app)
        {
            return new EntityMapper($app);
        });

        $this->app->singleton('\EntityMapper\Cache\EntityCacheInterface', function($app)
        {
            if( $this->app->bound('cache') )
            {
                return new EntityCache( $app['cache'], $app->make('\EntityMapper\ClassInflector') );
            }

            return new NullEntityCache( $app->make('\EntityMapper\ClassInflector') );
        });
    }
}