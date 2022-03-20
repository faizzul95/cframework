<?php

class Controller
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

        $exclude = explode(',', $_ENV['GUEST_PAGE']);

        if (in_array($currentClass, $exclude)) {
            $this->session = new \Configuration\SessionManager();
            if ($currentClass == 'Auth') {
                if (!$this->session->has('isLoggedIn')) {
                    checkMaintenance();
                } else if ($this->session->get('roleID') != 0) {
                    checkMaintenance();
                }
            }
        } else {
            if (session()->get('roleID') != 0) {
                session();
                checkMaintenance();
            }
        }

        $this->db = db();
    }

    // Initializes a new instance of the static class
    public static function init()
    {
        return new static();
    }

    // Get static class
    public static function getClass()
    {
        return static::class;
    }

    // Non-static function to get static class
    public function getStaticClass()
    {
        return static::class;
    }
}
