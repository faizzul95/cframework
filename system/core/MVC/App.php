<?php

class App
{
  protected $controller = 'Auth';
  protected $method = 'index';
  protected $params = [];

  function __construct()
  {
    $this->session = new \Configuration\SessionManager();
    $url = $this->urlParse();

    $systemController = $this->systemController();
    $controllerName = $methodName = '';

    // method / function name
    if (!isset($url[0])) {
      $url[0] = $this->controller;
    }

    // Controller
    try {
      if ($this->checkController($url[0])) {
        $this->controller = ucfirst($url[0]);
        $controllerName = ucfirst($url[0]);
        unset($url[0]);
      } else if ($this->session->get('isLoggedIn')) {
        $this->controller = 'Dashboard';
      } else {
        error('404');
        exit();
      }
    } catch (Exception $e) {
      error('404');
      exit();
    }

    if (in_array($this->controller, $systemController)) {
      require_once '../system/core/MVC/' . $this->controller . '.php';
    } else {
      require_once '../app/controllers/' . $this->controller . '.php';
    }

    $this->controller = new $this->controller;

    // method / function name
    if (isset($url[1])) {

      try {
        if ($this->checkMethod($this->controller, $url[1])) {
          $this->method =  $url[1];
          $methodName =  $url[1];
          unset($url[1]);
        } else {
          error('404');
          exit();
        }
      } catch (Exception $e) {
        error('404');
        exit();
      }
    }

    // parameter
    if (!empty($url)) {
      $this->params = array_values($url);
    }

    if (isAjax()) {
      if (validateCsrf(2, getBearerToken()) === true) {
        call_user_func_array([$this->controller, $this->method], $this->params);
      } else {
        http_response_code(401);
      }
    } else {
      call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // if (isAjax()) {
    //   call_user_func_array([$this->controller, $this->method], $this->params);
    //   exit();
    // } else if (!isAjax()) {

    //   if (!empty($this->params)) {
    //     call_user_func_array([$this->controller, $this->method], $this->params);
    //     exit();
    //   }
    //   // else if ($this->permission($controllerName, $methodName)) {
    //   //   call_user_func_array([$this->controller, $this->method], $this->params);
    //   //   exit();
    //   // } 
    //   else {
    //     error('403');
    //     exit();
    //   }
    // } else {
    //   error('403');
    //   exit();
    // }
  }

  public function checkController($url)
  {
    if (in_array(ucfirst($url), $this->systemController())) {
      $filesPath = '../system/core/MVC/' . ucfirst($url) . '.php';
    } else {
      $filesPath = '../app/controllers/' . ucfirst($url) . '.php';
    }

    if (!file_exists($filesPath)) {
      // throw new Exception('Controller Not exist.');
      return false;
    }
    return true;
  }

  public function checkMethod($controller, $url)
  {
    if (!method_exists($controller, $url)) {
      // throw new Exception('Method Not exist.');
      return false;
    }
    return true;
  }

  public function urlParse()
  {
    if (isset($_GET['url'])) {

      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);

      return $url;
    }
  }

  public function systemController()
  {
    return ["Migrate", "Seed", "Sysadmin"];
  }
}
