# To Do

1. @var namespace, when parsing. If class is named spaced `\Some` and var's classname is `Namespace\Class`, then the @var fully qualified classname should be `\Some\Namespace\Class`.
2. Relationships (wheeee)
3. Write Queries (save/update, delete) with Entity (`$respository->save($entity)`, `$respository->delete($entity)`)
	* Including using specified Getter's for getting data to save/update
	* Interface with `__toDb()` method on Value Objects?