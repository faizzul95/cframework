<?php

use Dompdf\Dompdf;

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

  // instantiate and use the dompdf class
  define("DOMPDF_UNICODE_ENABLED", true);

  $dompdf = new Dompdf();

  $options = $dompdf->getOptions();
  $options->setChroot('/var/www/html/michelia/public/assets/');
  $options->setDefaultFont('Courier');
  $options->set('isRemoteEnabled', true);
  $options->set('isHtml5ParserEnabled', true);
  $options->set('isJavascriptEnabled', true);
  $options->set('isFontSubsettingEnabled', true);
  $dompdf->setOptions($options);

  $dompdf->loadHtml($dataToPrint);

  // (Optional) Setup the paper size and orientation
  $dompdf->setPaper($option['size'], $option['orientation']);

  // Render the HTML as PDF
  $dompdf->render();

  // Output the generated PDF to Browser
  // $dompdf->stream($option['filename'] . ".pdf", array("Attachment" => true));

  $file_to_save = $option['path'] . '/' . $option['filename'] . '.pdf';

  $result = file_put_contents($file_to_save, $dompdf->output());

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
