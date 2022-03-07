<?php

function excel($table_name)
{
  // code here
}

function pdf($dataToPrint, $option = array(), $type = 1)
{
  ini_set('display_errors', '1');
  ob_end_clean();
  ini_set('memory_limit', '2048M');
  ini_set('max_execution_time', 0);

  // code to export here

  $result = file_put_contents($file_to_save, '#PUT_FUNCTION_OUTPUT_HERE');

  if ($result) {
    return ['resCode' => 200, 'message' => $option];
  } else {
    return ['resCode' => 400, 'message' => "Can't generate PDF"];
  }
}

function encode_img_base64($img_path = NULL)
{

  $base64 = NULL;

  if (!empty($img_path)) {
    $path = $img_path;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
  }

  return $base64;
}
