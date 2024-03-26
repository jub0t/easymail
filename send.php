<?php

include "./config.php";

header("Content-type: application/json");
$success = false;
$messages = [];

// Logic Start
SendMail(
    [
        [
            "email" => "jforeverything2007@gmail.com",
            "displayName" => "John Doe"
        ]
    ],
    [
        "body" => "<h1>Test</h1>",
        "subject"  => "Test for email"
    ]
);
// Logic End

echo json_encode([
    "success" => $success,
    "messages" => $messages,
    "data" => [
        // "email_sent" => $email_sent
    ]
]);
