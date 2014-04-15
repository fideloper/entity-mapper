<?php  namespace EntityMapper; 

class EntityMapper {

    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * @var ClassInflector
     */
    protected $entityInflector;

    public function __construct($entityClassName, ClassInflector $entityInflector)
    {
        $this->entityClassName = $entityClassName;
        $this->entityInflector = $entityInflector;
    }
} 