<?php  namespace EntityMapper; 

use EntityMapper\Cache\EntityCache;
use Illuminate\Support\ServiceProvider;

class EntityMapperServiceProvider extends ServiceProvider {

    public function register()
    {
        Repository::setApp($this->app);

        if( $this->app->bound('db') )
        {
            Repository::setConnectionResolver($this->app['db']);
        }

        $this->app->bind('\EntityMapper\Cache\EntityCache', function($app)
        {
            return new EntityCache( $app->make('\EntityMapper\ClassInflector') );
        });

        $this->app->bind('\EntityMapper\EntityMapper', function($app)
        {
            return new EntityMapper( $app->make('\EntityMapper\Cache\EntityCache') );
        });
    }
} 