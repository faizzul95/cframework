<?php

use eftec\bladeone\BladeOne;

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
        $blade = new BladeOne($views, $cache, BLADE_MODE); // MODE set in env
        $data['token'] = $blade->getCsrfToken(true);

        try {
            $blade->setBaseUrl(base_url . 'public/'); // with or without trail slash
            echo $blade->run($file, $data);
        } catch (Exception $e) {
            echo "error found " . $e->getMessage() . "<br>" . $e->getTraceAsString();
        }
    } else {
        require_once '../app/views/' . $view . '.php';
    }
}

function render($view, $data = [], $type = true)
{
    $data['menuArray'] = getMenu();
    $data['menuAsideArray'] = getAsideMenu();
    $data['menuAbilities'] = getPermission();

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
        $blade = new BladeOne($views, $cache, BLADE_MODE); // MODE set in env
        $data['token'] = $blade->getCsrfToken(true);
        // $isvalid=$blade->csrfIsValid(true, '_mytoken');
        $blade->setAuth(getCurrentUserID(), getCurrentUserRole(), getPermission());
        $blade->setBaseUrl(base_url . 'public/'); // with or without trail slash
        // dd($blade, $data);
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

function asset($param)
{
    return base_url . 'public/' . $param;
}

function url($param)
{
    $param = htmlspecialchars($param, ENT_NOQUOTES, 'UTF-8');
    $param = filter_var($param, FILTER_SANITIZE_URL);
    return base_url . $param;
}

function model($model)
{
    require_once '../app/models/' . $model . '.php';
    return new $model;
}

function message($code, $text = 'create')
{
    // http_response_code($code); // use in api

    if ($code == 200) {
        return ucfirst($text) . ' successfully';
    } else {
        return 'Please consult the system administrator';
    }
}

function _requestStatus($code)
{

    $status = array(
        200 => 'OK',
        201 => 'Created',                       // POST/PUT resulted in a new resource, MUST include Location header
        202 => 'Accepted',                      // request accepted for processing but not yet completed, might be disallowed later
        204 => 'No Content',                    // DELETE/PUT fulfilled, MUST NOT include message-body
        304 => 'Not Modified',                  // If-Modified-Since, MUST include Date header
        400 => 'Bad Request',                   // malformed syntax
        403 => 'Forbidden',                     // unauthorized
        404 => 'Not Found',                     // request URI does not exist
        405 => 'Method Not Allowed',            // HTTP method unavailable for URI, MUST include Allow header
        415 => 'Unsupported Media Type',        // unacceptable request payload format for resource and/or method
        426 => 'Upgrade Required',
        451 => 'Unavailable For Legal Reasons', // REDACTED
        500 => 'Internal Server Error',         // all other errors
        501 => 'Not Implemented'                // (currently) unsupported request method
    );

    return (isset($status[$code]) ? $status[$code] : $status[500]);
}

function isAjax()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    } else {
        return false;
    }
}

function error($type = NULL)
{
    if ($type == '403') {
        view('error/403', ['title' => '403']);
    } else if ($type == '404') {
        view('error/404', ['title' => '404']);
    } else if ($type == '500') {
        view('error/404', ['title' => '500']);
    } else if ($type == 'maintenance') {
        view('error/maintenance', ['title' => 'Maintenance']);
    }
}

function nodata($filesName = '3.png')
{
    $filesName = getAllNoDataIMG();
    echo "<div id='nodata' class='col-lg-12 mb-4 mt-2'>
          <center>
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid mb-3' width='38%'>
            <h3 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:15px'> 
             <strong> NO INFORMATION FOUND </strong>
            </h3>
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;'> 
                Here are some action suggestions for you to try :- 
            </h6>
          </center>
          <div class='row d-flex justify-content-center w-100'>
            <div class='col-lg m-1 text-left' style='max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;'>
              1. Try the registrar function (if any).<br>
              2. Change your word or search selection.<br>
              3. Contact the system support immediately.<br>
            </div>
          </div>
        </div>";
}

function nodataJs($filesName = '3.png')
{
    $filesName = getAllNoDataIMG();
    return "<div id='nodata' class='col-lg-12 mb-4 mt-2'>\
          <center>\
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid mb-3' width='38%'>\
            <h3 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:15px'> \
             <strong> NO INFORMATION FOUND </strong>\
            </h3>\
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;'> \
                Here are some action suggestions for you to try :- \
            </h6>\
          </center>\
          <div class='row d-flex justify-content-center w-100'>\
            <div class='col-lg m-1 text-left' style='max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;'>\
                1. Try the registrar function (if any).<br>\
                2. Change your word or search selection.<br>\
                3. Contact the system support immediately.<br>\
            </div>\
          </div>\
        </div>";
}

function noSelectDataLeft($type = 'Type', $filesName = '5.png')
{
    $uppercaseText = strtoupper($type);
    echo "
        <div id='nodata' class='col-lg-12 mb-2 mt-4' style='margin-top:50px!important;'>
          <center>
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid mb-5' width='50%'>
            <h3 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:15px'> 
             <strong> NO $uppercaseText SELECTED </strong>
            </h3>
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;'> 
                Select any $type on the left
            </h6>
          </center>
        </div>";
}

function noAnnouncementJs($filesName = 'search-folder.png')
{
    return "<div id='nodata' class='col-lg-12 mb-2 mt-5'>
          <center>
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid mb-4' width='100%'>
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:25px'>
             <strong> NO NEW ANNOUNCEMENTS </strong>
            </h6>
          </center>
        </div>";
}

function noSearchFound($filesName = 'search-folder.png')
{
    return "<div id='nodata' class='col-lg-12 mb-4 mt-2'>
          <center>
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid' width='80%'>
            <h5 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:20px'> 
             <strong> NO INFORMATION FOUND </strong>
            </h5>
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;'> 
                Here are some action suggestions for you to try :- 
            </h6>
          </center>
          <div class='row d-flex justify-content-center w-100'>
            <div class='col-lg m-1 text-left' style='max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;'>
                1. Change your word or search selection.<br>
                2. Contact the system support immediately.<br>
            </div>
          </div>
        </div>";
}

function noSearchFoundJs($filesName = 'search-folder.png')
{
    return "<div id='nodata' class='col-lg-12 mb-4 mt-2'>\
          <center>\
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid' width='80%'>\
            <h5 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:20px'> \
             <strong> NO INFORMATION FOUND </strong>\
            </h5>\
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;'> \
                Here are some action suggestions for you to try :- \
            </h6>\
          </center>\
          <div class='row d-flex justify-content-center w-100'>\
            <div class='col-lg m-1 text-left' style='max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;'>\
                1. Change your word or search selection.<br>\
                2. Contact the system support immediately.<br>\
            </div>\
          </div>\
        </div>";
}

function noSearchQuery($filesName = 'search-folder.png')
{
    $filesName = getAllNoDataIMG();
    return "<div id='nodata' class='col-lg-12 mb-4 mt-2'>
          <center>
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid mb-3' width='35%'>
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:20px'> 
             <strong> It's empty here </strong>
            </h6>
          </center>
        </div>";
}

function noSearchQueryJs($filesName = 'search-folder.png')
{
    return "<div id='nodata' class='col-lg-12 mb-4 mt-2'>\
          <center>\
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid' width='80%'>\
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:20px'> \
             <strong> It's empty here </strong>\
            </h6>\
          </center>\
        </div>";
}

function cantViewPDF($filesName = '8.png')
{
    $filesName = getAllNoDataIMG();
    return "<div id='nodata' class='col-lg-12 mb-4'>
          <center>
            <img src='" . url('public/framework/img/nodata/' . $filesName) . "' class='img-fluid mb-4' width='80%' style='margin-top:50px'>
            <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:50px'>
             <strong> Ops. your device does not appear to be supported to view this file. </strong>
            </h6>
          </center>
        </div>";
}

function getAllNoDataIMG()
{
    $results_array = array_diff(scandir('../public/framework/img/nodata'), array('.', '..'));
    $random_keys = array_rand($results_array, 3);
    $filesName = $results_array[$random_keys[0]];

    return $filesName;
}

function loader($status = true)
{
    if ($status) {
        return "<div id='loaderData' class='col-lg-12 mb-4 mt-2'>
                    <center>
                    <img src='" . asset('assets/loader.gif') . "' class='img-fluid mb-3' width='80%'>
                    </center>
                </div>";
    }
}

function paginate($current_page = 1, $total_no_of_pages = 1, $pageLimit = 10, $functionName = 'getData')
{
    $prevDis = ($current_page <= 1) ? 'disabled' : '';
    $nextDis = ($current_page >= $total_no_of_pages) ? 'disabled' : '';

    $second_last = $total_no_of_pages - 1; // total pages minus 1
    $offset = ($current_page - 1) * 10;
    $previous_page = $current_page - 1;
    $next_page = $current_page + 1;
    $adjacents = "2";

    // pagination-space, pagination-circle
    $pageLink = '<ul class="pagination justify-content-center pagination-circle">';

    $pageLink .= "<li class='page-item $prevDis'>
                            <a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $previous_page . ")'> 
                                <i class='fa fa-arrow-left' aria-hidden='true'></i>
                            </a>
                          </li>";

    if ($total_no_of_pages <= 10) {
        for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
            if ($counter == $current_page) {
                $pageLink .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
            } else {
                $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $counter . ")'>$counter</a></li>";
            }
        }
    } elseif ($total_no_of_pages > 10) {

        if ($current_page <= 4) {
            for ($counter = 1; $counter < 8; $counter++) {
                if ($counter == $current_page) {
                    $pageLink .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                } else {
                    $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $counter . ")'>$counter</a></li>";
                }
            }

            $pageLink .= "<li class='page-item'><a class='page-link'>...</a></li>";
            $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $second_last . ")'>$second_last</a></li>";
            $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $total_no_of_pages . ")'>$total_no_of_pages</a></li>";
        } elseif ($current_page > 4 && $current_page < $total_no_of_pages - 4) {
            $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(1)'>1</a></li>";
            $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(2)'>2</a></li>";
            $pageLink .= "<li class='page-item'><a class='page-link'>...</a></li>";

            for ($counter = $current_page - $adjacents; $counter <= $current_page + $adjacents; $counter++) {
                if ($counter == $current_page) {
                    $pageLink .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                } else {
                    $pageLink .= "<li><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $counter . ")'>$counter</a></li>";
                }
            }

            $pageLink .= "<li class='page-item'><a class='page-link'>...</a></li>";
            $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $second_last . ")'>$second_last</a></li>";
            $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $total_no_of_pages . ")'>$total_no_of_pages</a></li>";
        }
    } else {
        $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(1)'>1</a></li>";
        $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='$functionName(2)'>2</a></li>";
        $pageLink .= "<li class='page-item'><a class='page-link'>...</a></li>";
        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
            if ($counter == $page_no) {
                $pageLink .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
            } else {
                $pageLink .= "<li class='page-item'><a class='page-link' href='javascript:void(0)' onclick='getData(" . $counter . ")'>$counter</a></li>";
            }
        }
    }

    $pageLink .= "<li class='page-item $nextDis'>
                    <a class='page-link' href='javascript:void(0)' onclick='$functionName(" . $next_page . ")'> 
                        <i class='fa fa-arrow-right' aria-hidden='true'></i>
                    </a>
                </li>";
    $pageLink .= '</ul>';

    echo "<div id='paginate' class='row'> 
            <div class='col-lg-12'>
                <center>";
    echo $pageLink;
    echo "      </center>
            </div>
        </div>";
}

function timestamp()
{
    return date('Y-m-d H:i:s');
}

function isMobile()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        return preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|boost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    };
    return false;
}
