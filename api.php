<?php

add_action('rest_api_init', function () {

    register_rest_route('paraphraser/v1', 'paraphrased', array(
        'methods' => 'POST',
        'callback' => 'sendParaphraserData',
        'args' => array(),
        'permission_callback' => '__return_true'
    ));
});


function sendParaphraserData($req)
{
    $parameters = $req->get_params();

    $data = $parameters['data'];
    $lang = $parameters['lang'];
    $mode = $parameters['mode'];
    $style = $parameters['style'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.paraphraser.io/paraphrasing-api');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, "data=". $data. "&lang=." . $lang."&mode=". $mode. "&style=" . $style);

$headers = array();
$headers[] = array('Content-Type' => 'application/x-www-form-urlencoded', 'Authorization: ' => '15c6ca18e71c4fd9647db77f813e2cb2');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$jsonContainer = json_decode($result);

    return $jsonContainer->paraphrasedContent;
}


?>
