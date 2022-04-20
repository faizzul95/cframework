<?php

use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use Rakit\Validation\Validator;
use voku\helper\AntiXSS;

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

function session()
{
    $session = new \Configuration\SessionManager();

    if (filter_var($_ENV['AUTH_SESSION_CHECK'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
        if (!$session->has('isLoggedIn')) {
            redirect('auth/logout', true);
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

function d($str, $jsconsole = false)
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
        $value = htmlspecialchars_decode($value, ENT_QUOTES | ENT_SUBSTITUTE);
    }
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

function folder($foldername = 'default', $id = 0, $type = 'image')
{
    $foldername = str_replace(array('\'', '/', '"', ',', ';', '<', '>', '@', '|'), '_', preg_replace('/\s+/', '_', $foldername));

    $folder = 'upload/' . $type . '/' . $foldername;

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

function checkMaintenance()
{
    if (filter_var($_ENV['MAINTENANCE_MODE'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
        error('maintenance');
        exit();
    } else {
        return false;
    }
}


/**
 * get the full name (name \ namespace) of a class from its file path
 * result example: (string) "I\Am\The\Namespace\Of\This\Class"
 *
 * @param $filePathName
 *
 * @return  string
 */
function getClassFullNameFromFile($filePathName)
{
    return getClassNamespaceFromFile($filePathName) . '\\' . getClassNameFromFile($filePathName);
}


/**
 * build and return an object of a class from its file path
 *
 * @param $filePathName
 *
 * @return  mixed
 */
function getClassObjectFromFile($filePathName)
{
    $classString = getClassFullNameFromFile($filePathName);

    $object = new $classString;

    return $object;
}


/**
 * get the class namespace form file path using token
 *
 * @param $filePathName
 *
 * @return  null|string
 */
function getClassNamespaceFromFile($filePathName)
{
    $src = file_get_contents($filePathName);

    $tokens = token_get_all($src);
    $count = count($tokens);
    $i = 0;
    $namespace = '';
    $namespace_ok = false;
    while ($i < $count) {
        $token = $tokens[$i];
        if (is_array($token) && $token[0] === T_NAMESPACE) {
            // Found namespace declaration
            while (++$i < $count) {
                if ($tokens[$i] === ';') {
                    $namespace_ok = true;
                    $namespace = trim($namespace);
                    break;
                }
                $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
            }
            break;
        }
        $i++;
    }
    if (!$namespace_ok) {
        return null;
    } else {
        return $namespace;
    }
}

/**
 * get the class name form file path using token
 *
 * @param $filePathName
 *
 * @return  mixed
 */
function getClassNameFromFile($filePathName)
{
    $php_code = file_get_contents($filePathName);

    $classes = array();
    $tokens = token_get_all($php_code);
    $count = count($tokens);
    for ($i = 2; $i < $count; $i++) {
        if (
            $tokens[$i - 2][0] == T_CLASS
            && $tokens[$i - 1][0] == T_WHITESPACE
            && $tokens[$i][0] == T_STRING
        ) {

            $class_name = $tokens[$i][1];
            $classes[] = $class_name;
        }
    }

    return $classes[0];
}
