<?php

$jsonData = json_decode(file_get_contents('php://input'), true);

$myfile = fopen("test.txt", "w") or die("Unable to open file!");
fwrite($myfile, "twes");
fclose($myfile);


if($jsonData['epc'] !== "") {
  require_once '../db.func.php';
  $epc = trim($jsonData['epc']);

  $query = DB::prepare("INSERT INTO `post_data` VALUES (:epc, :time_stamp);");
  $query->execute(['epc' => $epc, 'time_stamp' => time()]);

  echo json_encode(true);
}
else {
  echo json_encode(false);
}

?>
