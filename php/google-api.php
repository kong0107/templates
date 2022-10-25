<?php
    /** PHP setting (跟 Google 無關)
     *  1. 安裝 composer: https://getcomposer.org/
     *  2. 用 composer 安裝 google-api-php-client: https://github.com/googleapis/google-api-php-client
     *  3. 確認 `php.ini` 中的 `curl.cainfo` 有啟用: https://stackoverflow.com/questions/18255203/#21487471
     */
    require_once './vendor/autoload.php';

    /** Google setting (跟 PHP 無關)
     *  1. 登入 Google Cloud: https://console.cloud.google.com/
     *  2. 新增（或選擇）專案
     *  3. 啟用 Google Calendar API: https://console.cloud.google.com/apis/dashboard
     *  4. 新增（或選擇）服務帳戶: https://console.cloud.google.com/iam-admin/serviceaccounts
     *  5. 在服務帳戶中新增金鑰，類型為 JSON ，把自動下載的檔案存好（不可放在 DocumentRoot 裡，不然就設定 .htaccess ），並設定下方路徑。
     *  6. 找到「服務帳戶」的電郵地址。
     *  7. 在 Google Calendar 中將需要存取的日曆的「與特定使用者共用日曆」加入前述電郵地址（不用加入非開發者私人電郵）。
     *  8. 複製前述日曆 ID （格式同電郵）到下方變數。
     */
    putenv('GOOGLE_APPLICATION_CREDENTIALS=./config/google-service-account.key.json'); // 設定金鑰檔案路徑
    $calendar_id = 'your_calendar_id@group.calendar.google.com';

    $client = new Google_Client(); // 準備連線
    $client->useApplicationDefaultCredentials(); // 使用預設驗證方式（即讀取上方設定路徑的金鑰檔案）
    $client->addScope(Google_Service_Calendar::CALENDAR_EVENTS); // 設定要使用的功能
    $service = new Google_Service_Calendar($client); // 開始連線


    /** API Use
     *  參考官網，每一個方法 (method) 的頁面裡，最下面都有範例，右邊也可試行操作。
     *  https://developers.google.com/calendar/api/v3/reference/events/
     */
    $event = new Google_Service_Calendar_Event(array(
        'summary' => '標題欄位名稱不是 title ',
        'description' => '說明欄裡<em>可以</em>使用 HTML 。',

        // 非全天的事件，用 `dateTime` 指定，格式 YYYY-MM-DDTHH:mm:ss+08:00
        'start' => array(
            'dateTime' => '2022-10-23T22:30:00+08:00'
        ),
        'end' => array(
            'dateTime' => '2022-10-23T23:30:00+08:00'
        ),

        // 全天事件用 `date` 指定日期。注意結束日要比起始日大。
        // 'start' => array(
        //     'date' => '2022-10-31'
        // ),
        // 'end' => array(
        //     'date' => '2022-11-01'
        // )
    ));
    $event = $service->events->insert($calendar_id, $event);
    echo $event->getId();

    echo chr(10);
    // 官方的 `htmlLink` 其實連不到，但只有這個值可以取得 `eid` （注意跟 `id` 和 `iCalUID` 都不同）。
    $link = 'https://calendar.google.com/calendar/event?action=VIEW&' . parse_url($event->htmlLink, PHP_URL_QUERY);
    echo $link;

?>
