<?php

class Seeder
{
    public function __construct()
    {
        $currentClass = get_called_class();
        $class = new ExtendedReflectionClass($currentClass);

        // check if "use" have been declare 
        if (!empty($class->getUseStatements())) {
            foreach ($class->getUseStatements() as $key => $value) {
                $className = $value['class'];
                $alias = $value['as'];
                $this->{$alias} = new $className; // create new object
            }
        }
    }
}
