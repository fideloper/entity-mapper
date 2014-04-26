<?php  namespace EntityMapper;

use EntityMapper\Cache\EntityCache;
use EntityMapper\Cache\NullEntityCache;
use Illuminate\Support\ServiceProvider;

class EntityMapperServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->app->alias('em.cache', 'EntityMapper\Cache\EntityCacheInterface');
        $this->app->alias('em.mapper', 'EntityManager\EntityMapper');
    }

    public function register()
    {
        Repository::setApp($this->app);

        if( $this->app->bound('db') )
        {
            Repository::setConnectionResolver($this->app['db']);
        }

        $this->app['em.cache'] = $this->app->share(function($app)
        {
            if( $this->app->bound('cache') )
            {
                return new EntityCache( $app['cache'], $app->make('EntityMapper\ClassInflector') );
            }

            return new NullEntityCache( $app->make('EntityMapper\ClassInflector') );
        });

        $this->app['em.mapper'] = $this->app->share(function($app)
        {
            return new EntityMapper($app, $app['em.cache']);
        });
    }
}