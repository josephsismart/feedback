<?php

function ai_analyze_sentiment($text)
{
    $payload = [
        "model" => "phi3",
        "prompt" =>
            "Analyze the sentiment of this feedback.\n" .
            "Respond ONLY in JSON like this:\n" .
            "{ \"sentiment\": \"NEGATIVE\", \"label\": \"SLOW INTERNET\" }\n\n" .
            "Feedback: \"$text\"",
        "stream" => false
    ];

    $ch = curl_init("http://localhost:11434/api/generate");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) return null;

    $json = json_decode($response, true);
    return json_decode($json['response'], true);
}