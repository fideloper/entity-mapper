1. Use `Illuminate\Cache` Only implement cache if present (`$app['cache']`)
2. Begin EntityMapping (data set/array/whatever the data is and use it to hydrate based on `ReflectionClass` properties available)
3. Repository needs creating (using Illuminate\Database).
    * Dynamically create a repository for a table/model
    * Or use classes defined Repository