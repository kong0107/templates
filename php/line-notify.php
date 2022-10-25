<?php
/**
 * LINE Notify API: https://notify-bot.line.me/doc/en/
 */

// Constants
$url = 'https://notify-api.line.me/api/notify';

// Configuration
$access_token = '<access_token>';
$content = array(
    'message' => 'string message is always required',

    // 'imageFile' => './favicon-192x192.png',

    // 'imageThumbnail' => 'http://kong0107.github.io/images/favicon-192x192.png',
    // 'imageFullsize' => 'https://pbs.twimg.com/media/FdRPkmiaMAAM03G?format=jpg&name=medium',

    /// available stickers: https://developers.line.biz/en/docs/messaging-api/sticker-list/
    // 'stickerPackageId' => 11537,
    // 'stickerId' => 52002741,

    // 'notificationDisabled' => true
);
$header_auth = 'Authorization: Bearer ' . $access_token;


/**
 * Without cURL, not supporting image uploading.
 * https://www.php.net/manual/en/context.http.php#refsect1-context.http-examples
 */
$context = stream_context_create(array(
    'http' => array( // Always `http`, not `https`, even the request URL is https.
        'method'  => 'POST',
        'header'  => array(
            // 'Content-type: multipart/form-data', // seems not work in this method
            'Content-type: application/x-www-form-urlencoded',
            $header_auth
        ),
        'content' => http_build_query($content)
    )
));

// Solution 1: simple way but no response header
$result = file_get_contents($url, false, $context);
echo $result;
echo "\n\n";

// Solution 2: use this if you want more info
$stream = fopen($url, 'r', false, $context);
$res_head = stream_get_meta_data($stream); // nested associated array
$res_body = stream_get_contents($stream); // json string
fclose($stream);
print_r($res_head['wrapper_data']);
echo $res_body;
echo "\n\n";

/**
 * Solution 3: Use cURL to support image upload
 * https://www.php.net/manual/zh/function.curl-setopt.php
 */
if(isset($content['imageFile']))
    $content['imageFile'] = new CURLFile($content['imageFile']);

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true, // curl_exec() would return a string instead of print out
    CURLOPT_HEADER => true, // header of the reply would be included in the body
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array(
        'Content-type: multipart/form-data',
        // 'Content-type: application/x-www-form-urlencoded', // this does not support image upload, and `$content` shall be wrapped by `http_build_query()`
        $header_auth
    ),
    CURLOPT_POSTFIELDS => $content
));
$result = curl_exec($ch);
// $info = curl_getinfo($ch);
curl_close($ch);

// print_r($info);
echo $result;

?>
