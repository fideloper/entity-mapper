<?php  namespace EntityMapper; 

class EntityNotFoundException extends \Exception {


    public function __construct($entity, $message = "", $code = 0, $previous = null)
    {
        $this->message = "No query results for model ".$entity.".";
        parent::__construct($message, $code, $previous);
    }
} 