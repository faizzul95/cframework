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
