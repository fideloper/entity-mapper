<?php  namespace EntityMapper\Parser; 


use ReflectionClass;
use ReflectionMethod;
use EntityMapper\Reflector\MethodCollection;
use EntityMapper\Reflector\GetterMethod;
use EntityMapper\Reflector\SetterMethod;

class MethodParser implements ParserInterface {

    use CommentParser;

    protected $methodCollection;

    public function __construct()
    {
        $this->methodCollection = new MethodCollection();
    }

    public function parse(ReflectionClass $class)
    {
        $methods = $class->getMethods();

        return $this->parseMethods( $methods );
    }

    protected function parseMethods( Array $methods )
    {
        foreach( $methods as $method )
        {
            // This populates $this->methodCollection
            $this->parseMethod( $method );
        }

        return $this->methodCollection;
    }

    /**
     * Parse method for variable
     * Methods can be both a getter and setter
     * @param ReflectionMethod $method
     */
    private function parseMethod(ReflectionMethod $method)
    {
        $comment = $method->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $tags = $this->parseTags($tags);

        $isSetter = $this->getIsSetter( $tags );
        $isGetter = $this->getIsGetter( $tags );


        if( $isSetter )
        {
            $setterMethod = new SetterMethod( $method->getShortName(), $tags['setter'] );
            $this->methodCollection->addSetter($setterMethod->variable(), $setterMethod);
        }

        if( $isGetter )
        {
            $getterMethod = new GetterMethod( $method->getShortName(), $tags['getter'] );
            $this->methodCollection->addGetter($getterMethod->variable(), $getterMethod);
        }
    }

    protected function getIsSetter(Array $tags)
    {
        return isset($tags['setter']);
    }

    protected function getIsGetter(Array $tags)
    {
        return isset($tags['getter']);
    }


} 