<?php

/**
 * Redirect to the specified URL instantly or after some seconds.
 * @param  string $url
 * @param  int $seconds
 * @return void
 */
function redirect(string $url, int $seconds = 0): void {
    if($time < 0) $time = 0;
    if(!$time && !headers_sent()) header('Location: ' . $url);
    printf('<meta http-equiv="refresh" content="%d; url=%s">', $seconds, $url);
    printf('<script>setTimeout(() => location.href = "%s", %d);</script>', $url, $seconds * 1000);
}


function sql_escape($str) {
    return str_replace(chr(0x27), chr(0x5c) . chr(0x27), $str);
}

class mysqlii extends mysqli {

    public function get_all(string $sql): array|false {
        $result = $this->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }

    public function get_col(string $sql): array|false {
        $result = $this->query($sql);
        if(!$result) return false;
        $ret = array();
        while($v = $result->fetch_column()) $ret[] = $v;
        return $ret;
    }

    public function get_one(string $sql): null|int|float|string|false {
        $result = $this->query($sql);
        return $result ? $result->fetch_column() : false;
    }

    public function get_row(string $sql): array|null|false {
        $result = $this->query($sql);
        return $result ? $result->fetch_assoc() : false;
    }
}


/**
 * LINE
 */
function send_line_notify($content, $access_token) {
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
    file_get_contents('https://notify-api.line.me/api/notify', false, $context);
}