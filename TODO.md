1. Entity needs a better name
2. Change `Entity` methods from `column($key)` to `columns` and return collection of columns
3. Create Collections based on Illuminate\Support\Collection, use for Columns, Methods collections
4. Consider making the table object an aggregate root for tables, columns and "methods" (getters/setters) ("Entity Description")
5. Use Illuminate\Cache for Cache (a requirement? Or create strategies?) Only implement cache if present. "Suggested dependency"