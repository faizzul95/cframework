<?php

use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use Rakit\Validation\Validator;
use voku\helper\AntiXSS;
use eftec\bladeone\BladeOne;

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\MySQL;

// server side datatable
function serverSideDT($db)
{
    return new Datatables(new MySQL($db->getConnection()));
}

function validator()
{
    // reff : https://github.com/rakit/validation
    return $validator = new Validator;

    $validator->setTranslations([
        'and' => 'dan',
        'or' => 'atau',
        'is' => 'ialah',
    ]);
}

function purify($post)
{
    // reff : https://github.com/voku/anti-xss
    $antiXss = new AntiXSS();
    $antiXss->removeEvilAttributes(array('style')); // allow style-attributes
    return $antiXss->xss_clean($post);
}

function antiXss($data)
{
    $antiXss = new AntiXSS();
    $antiXss->removeEvilAttributes(array('style')); // allow style-attributes

    $xssFound = false;
    if (isArray($data)) {
        foreach ($data as $post) {
            $antiXss->xss_clean($post);
            if ($antiXss->isXssFound()) {
                $xssFound = true;
            }
        }
    } else {
        $antiXss->xss_clean($data);
        if ($antiXss->isXssFound()) {
            $xssFound = true;
        }
    }

    return $xssFound;
}

function passDecrypt($dbpass, $enterpass)
{
    if (password_verify($enterpass, $dbpass)) {
        return true;
    } else {
        return false;
    }
}

// reff : https://github.com/EFTEC/BladeOne
function view($view, $data = [], $type = true)
{
    $viewArr = explode("/", $view);
    $folder = (count($viewArr) > 1) ? $viewArr[0] : NULL;
    $file = (count($viewArr) == 1) ? $viewArr[0] : $viewArr[1];

    $views = '../app/views/' . $folder;
    $cache = '../system/cache/' . $folder;

    if (!file_exists('../app/views/' . $view . '.php')) {
        error('404');
        exit();
    }

    if ($type) {
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run($file, $data);
    } else {
        require_once '../app/views/' . $view . '.php';
    }
}

function render($view, $data = [], $type = true)
{
    $data['menuArray'] = getMenu();
    $data['menuAsideArray'] = getAsideMenu();

    $viewArr = explode("/", $view);
    $folder = (count($viewArr) > 1) ? $viewArr[0] : NULL;
    $file = (count($viewArr) == 1) ? $viewArr[0] : $viewArr[1];

    $views = '../app/views/' . $folder;
    $cache = '../system/cache/' . $folder;

    if (!file_exists('../app/views/' . $view . '.php')) {
        error('404');
        exit();
    }

    if ($type) {
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO); // MODE set in env
        echo $blade->run($file, $data);
    } else {
        require_once '../app/templates/header.php';
        require_once '../app/views/' . $view . '.php';
        require_once '../app/templates/footer.php';
    }
}

function redirect($path, $permanent = false)
{
    header('Location: ' . url($path), true, $permanent ? 301 : 302);
    exit();
}

function model($model)
{
    require_once '../app/models/' . $model . '.php';
    return new $model;
}

function session()
{
    $session = new \Configuration\SessionManager();

    if (filter_var($_ENV['AUTH_SESSION_CHECK'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
        if (!$session->has('isLoggedIn')) {
            redirect('auth/logout');
        }
    } else {
        // $this->session = new \Configuration\SessionManager();
    }

    return $session;
}

function isLogin()
{
    $session = new \Configuration\SessionManager();

    if (!$session->has('isLoggedIn')) {
        return false;
        exit;
    }

    return true;
}

function d($str, $die = false)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';

    if ($die) die;
}

function dump($str, $jsconsole = false)
{
    if (!$jsconsole) {
        echo '<pre>';
        \var_dump($str);
        echo '</pre>';
    } else {
        echo '<script>console.log(' . \json_encode($str) . ')</script>';
    }
}

function json($data, $except = false)
{
    // header("Content-type:application/json");
    if (is_array($data))
        array_walk_recursive($data, "specialsCharDecode");

    echo ($except) ? json_encode($data, JSON_PRETTY_PRINT) : json_encode(purify($data), JSON_PRETTY_PRINT);
}

function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function specialsCharDecode(&$value)
{
    if (!is_bool($value)) {
        $value = htmlspecialchars_decode($value, ENT_QUOTES);
    }
}

function formatIC($icno, $type = 1)
{

    if ($type == 1) {
        // count length. nric should be 12.
        if (strlen($icno) == 12) {
            // add - to ic number
            $first = substr($icno, 0, 6);
            $second = substr($icno, 6, 2);
            $third = substr($icno, 8, 4);

            $icno = $first . '-' . $second . '-' . $third;
        }
    } else {
        // remove - to ic number
        $icno = str_replace("-", "", $icno);
    }

    return $icno;
}

// remove cache file
function removeCache($folder = NULL, $removeFile = false)
{

    if ($removeFile == false) {
        $dirPath = (empty($folder)) ? "../system/cache/" : "../system/cache/" . $folder;
    } else {
        $dirPath = $folder;
    }

    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    removeCache($dirPath . DIRECTORY_SEPARATOR . $object, true);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        reset($objects);
        rmdir($dirPath);
    }
}


function checkMaintenance()
{
    if (filter_var($_ENV['MAINTENANCE_MODE'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
        error('maintenance');
        exit();
    } else {
        return false;
    }
}

function checkPageAccess($roleAccess = array(), $exceptID = array())
{
    $userID = session()->get('userID');
    $roleID = session()->get('roleID');

    if (!in_array($userID, $exceptID)) {
        if (in_array($roleID, $roleAccess)) {
            return false;
            exit;
        } else {
            error('403');
            exit;
        }
    }
}

function folder($foldername = 'default', $id = 0, $type = 1)
{
    $folder = 'assets/' . $folderType . '/' . $id;

    // check if folder current email id not exist, 
    // create one with permission (server) to upload

    if (!is_dir($folder)) {

        $old = umask(0);
        mkdir($folder, 0755, true);
        umask($old);

        chmod($folder, 0755);
    }

    return $folder;
}

function removefolder($foldername = 'default')
{
    $dir = $foldername;
    $structure = glob(rtrim($dir, "/") . '/*');
    if (is_array($structure)) {
        foreach ($structure as $file) {
            if (is_dir($file)) recursiveRemove($file);
            elseif (is_file($file)) unlink($file);
        }
    }

    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}
