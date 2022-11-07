<?php

/**
 * Redirect to the specified URL instantly or after some seconds.
 */
function redirect(
    string $url,
    int $seconds = 0
) : void {
    if($time < 0) $time = 0;
    if(!$time && !headers_sent()) header('Location: ' . $url);
    printf('<meta http-equiv="refresh" content="%d; url=%s">', $seconds, $url);
    printf('<script>setTimeout(() => location.href = "%s", %d);</script>', $url, $seconds * 1000);
}

/**
 * LINE Notify
 */
function send_line_notify(
    string|array $content,
    string $access_token
) : mixed {
    /// See https://notify-bot.line.me/doc/en/
    if(gettype($content) === 'string') $content = array('message' => $content);
    $header = array(
        'Content-type: application/x-www-form-urlencoded',
        'Authorization: Bearer ' . $access_token
    );
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => $header,
            'content' => http_build_query($content)
        )
    ));
    $json = file_get_contents('https://notify-api.line.me/api/notify', false, $context);
    return ($json === false) ? false : json_decode($json);
}
