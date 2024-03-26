<?php

include "./config.php";

header("Content-type: application/json");
$success = false;
$messages = [];

// Validation
$method = $_SERVER["REQUEST_METHOD"];

function finish()
{
    global $messages, $success;

    echo json_encode([
        "success" => $success,
        "messages" => $messages,
    ]);

    // End the request here

    exit();
}

if ($method != "POST") {
    array_push($messages, "Request method should be POST");
    finish();
}

// Parsing Body
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

if (!isset($input["receivers"])) {
    array_push($messages, "Must provide atleast one receiver");
    finish();
}

if (!isset($input["message"])) {
    array_push($messages, "Enter a valid message to send.");
    finish();
}

// Logic Start
SendMail(
    $input["receivers"],
    $input["message"]
);
// Logic End

$success = true;
finish();
