<?php

$ncco = [
    [
        "action" => "talk",
        "text" => "Thank you for joining the call today. You will now be added to the conference.",
        "language" => "en-GB",
        "style" => 0,
        "premium" => true
    ],
    [
        "action" => "conversation",
        "name" => "weekly-team-meeting"
    ]
];

header("Content-Type: application/json");
echo json_encode($ncco);