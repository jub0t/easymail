<?php

include "./config.php";
include "./db.php";

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

$headers = getallheaders();
$api_key = $headers["api_key"];

if (!isset($api_key)) {
    array_push($messages, "Authentication key not found.");
    finish();
} else {
    $allowed = verify_api_key($api_key);

    if (!$allowed) {
        array_push($messages, "Unauthorized to use this server, use a valid API Key.");
        finish();
    }
}

// Logic Start
$mail_sent = SendEasyMail(
    $input["receivers"],
    $input["message"]
);
// Logic End

if ($mail_sent) {
    $success = true;
    array_push($messages, "Email is on its way!");
} else {
    array_push($messages, "Something went wrong while sending email");
}

finish();
