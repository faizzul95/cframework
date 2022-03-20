<?php

function csrfProvider()
{
    $sessionProvider = new EasyCSRF\NativeSessionProvider();
    return $easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
}

function csrf_token()
{
    $easyCSRF = csrfProvider();
    return $easyCSRF->generate('csrf-token');
}

function csrf_field($fieldNo = NULL)
{
    $easyCSRF = csrfProvider();
    $currentToken = $easyCSRF->getCurrentToken('csrf-token') ?? csrf_token();

    return "<input type=\"hidden\" id=\"_token{$fieldNo}\" name=\"_token\" value=\"$currentToken\" class=\"form-control\" />";
}

function csrf_removeToken()
{
    $easyCSRF = csrfProvider();
    return $easyCSRF->removeToken('csrf-token');
}

function validateCsrf($type = 2, $token = NULL)
{
    if (antiXss($_POST) === false) {
        $easyCSRF = csrfProvider();
        $postToken = (isset($_POST['_token'])) ? $_POST['_token'] : NULL;
        $token = $token ?? $postToken;

        if (!empty($token)) {
            try {
                // reff : https://github.com/gilbitron/EasyCSRF
                if ($type == 1) {
                    // Makes the token expire after $timespan seconds (null = never)
                    return $easyCSRF->check('csrf-token', $token);
                } else if ($type == 2) {
                    // Make token reusable - Tokens can be made reusable and not one-time only (useful for ajax-heavy requests).
                    return $easyCSRF->check('csrf-token', $token, null, true);
                } else {
                    // Example 1 hour expiration
                    return $easyCSRF->check('csrf-token', $token, 60 * 60);
                }
            } catch (InvalidCsrfTokenException $e) {
                return $e->getMessage();
            }
        } else {
            return 'CSRF token not found';
        }
    } else {
        return 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated !';
    }
}

function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    return "You have CORS!";
}


/** 
 * Get header Authorization
 * */
function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

        // echo "<pre>";
        //print_r($requestHeaders);
        // echo "</pre>";

        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        } else if (isset($requestHeaders['X-Csrf-Token'])) {
            $headers = trim($requestHeaders['X-Csrf-Token']);
        }
    }
    return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken()
{
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches) > 0) {
            return $matches[1];
        } else {
            return $headers;
        }
    }
    return $headers;
}
