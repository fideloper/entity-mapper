<?php namespace EntityMapper\Parser;

use ReflectionClass;

interface ParserInterface {

    public function parse(ReflectionClass $class);
} 