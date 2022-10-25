<?php
/**
 * 1. Run an HTTP server.
 * 2. Add service to LINE Notify: https://notify-bot.line.me/my/services/
 * 3. Tell users to request https://notify-bot.line.me/oauth/authorize?response_type=code&scope=notify&response_mode=form_post&client_id=xxxxxxxxxxxxxxxxxxxx&redirect_uri=http://xxx.xxxx.xx/&state=someRandomStr
 * 4. This code is run on `redirect_uri`.
 * 5. Save the `access_token` for future message sending. (`code` can be used only once)
 */

/**
 * Receiving `code` and use it to get an access token.
 */
$url = 'https://notify-bot.line.me/oauth/token';
$content = array(
    'grant_type' => 'authorization_code',
    'code' => $_POST['code'],
    'redirect_uri' => 'http://xxxxx.xxx/xxx.php',
    'client_id' => 'xxxx',
    'client_secret' => 'xxxxxxxxxxxxx'
);
$context = stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'content' => http_build_query($content)
    )
));
$result = file_get_contents($url, false, $context);
if(!$result) exit('Failed to get an access token');
$result = json_decode($result, true);


/**
 * Use access token to send message.
 */
$url = 'https://notify-api.line.me/api/notify';
$header = array(
    'Content-type: application/x-www-form-urlencoded',
    'Authorization: Bearer ' . $result['access_token']
);
$content = array(
    'message' => 'Success!',
    // 'imageFile' => './favicon-192x192.png', // not supported without cURL
    // 'imageThumbnail' => 'http://kong0107.github.io/images/favicon-192x192.png',
    // 'imageFullsize' => 'https://pbs.twimg.com/media/FdRPkmiaMAAM03G?format=jpg&name=medium',
    // 'stickerPackageId' => 11537,
    // 'stickerId' => 52002741,
    // 'notificationDisabled' => true
);
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => $header,
        'content' => http_build_query($content)
    )
));
$result = file_get_contents($url, false, $context);
?>
