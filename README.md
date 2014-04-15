Entity Mapper
=============

An Entity Mapping ORM, based on `Illuminate\Database`. This will hopefully become an easy to use (easier than Doctrine)
entity mapper, to straddle the line between feature complete and too heavy to use.

It does a fair bit of Reflection-based parsing to map entities and map them to database data, so some caching will be
used if `Illuminate\Cache` is available, or if someone wants to implement the CacheInterface with their own implementation.

These are all just fancy plans for now.