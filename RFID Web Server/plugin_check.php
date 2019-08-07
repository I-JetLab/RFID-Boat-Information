<?php

/*
** PLUGIN: Check for POST request from RFID reader
**
** The RFID reading will send a POST request to the
** server and the server will log it with a time stamp.
** If the timestamp is within a few seconds of the current
** time stamp then it will be returned to the main page.
**
*/

require_once 'db.func.php';
date_default_timezone_set('UTC');


$current_timestamp = time();


// Submit a query for the latest POST request
$query = (DB::query("SELECT * FROM `post_data` ORDER BY `timestamp` DESC LIMIT 1")->fetchAll())[0];

// Check timestamp to see if it was most recent within a few seconds.
if(abs($query['timestamp'] - $current_timestamp) <= 3) {
  $_query = (DB::query("SELECT * FROM `data` WHERE `epc` = '{$query['epc']}' LIMIT 1")->fetchAll())[0];
  echo json_encode($_query);
}
else {
  http_response_code(400);
}
?>
