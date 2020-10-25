<?php

if (!class_exists('HypCommonFunc')) {
    class HypCommonFunc
    {
        // 1バイト文字をエンティティ化

        public function str_to_entity(&$str)
        {
            $e_mail = '';

            $i = 0;

            while ($str[$i]) {
                $e_mail .= '&#' . ord((string)$str[$i]) . ';';

                $i++;
            }

            $str = $e_mail;

            return $str;
        }

        // ",' で括ったフレーズ対応スプリット

        public function phrase_preg_split($str)
        {
            $words = [];

            $str = preg_replace("/(\"|'|”|’)(.+?)(?:\\1)/e", "str_replace(' ','\x08','$2')", $str);

            $words = preg_preg_split('/\s+/', $str, -1, PREG_SPLIT_NO_EMPTY);

            $words = str_replace("\x08", ' ', $words);

            return $words;
        }

        // 配列対応 & gpc 対応のstripslashes

        public function stripslashes_gpc(&$v)
        {
            if (ini_get('magic_quotes_gpc')) {
                if (is_array($v)) {
                    $arr = [];

                    foreach ($v as $k => $m) {
                        $arr[$k] = self::stripslashes_gpc($m);
                    }

                    $v = $arr;
                } else {
                    $v = stripslashes($v);
                }
            }

            return $v;
        }

        // RSS関連のキャッシュを削除する

        public function clear_rss_cache($files = [])
        {
            require_once XOOPS_ROOT_PATH . '/class/template.php';

            if (empty($files) || !is_array($files)) {
                $files = [
                    'db:BopComments_rss.html',
                    'db:whatsnew_rss.html',
                    'db:whatsnew_atom.html',
                    'db:whatsnew_rdf.html',
                    'db:whatsnew_pda.html',
                    'db:whatsnew_block_bop.html',
                    'db:whatsnew_block_mod.html',
                    'db:whatsnew_block_date.html',
                ];
            }

            $tpl = new XoopsTpl();

            $tpl->xoops_setCaching(2);

            foreach ($files as $tgt) {
                if ($tgt) {
                    $tpl->clear_cache($tgt);
                }
            }
        }

        // RPC Update Ping を打つ

        public function update_rpc_ping($default_update = 'http://bulkfeeds.net/rpc http://ping.myblog.jp http://ping.bloggers.jp/rpc/ http://blog.goo.ne.jp/XMLRPC http://ping.cocolog-nifty.com/xmlrpc http://rpc.technorati.jp/rpc/ping')
        {
            global $xoopsConfig;

            //RSSキャッシュファイルを削除

            self::clear_rss_cache();

            $update_ping2 = $default_update;

            $update_ping = preg_preg_split("/[\s,]+/", $update_ping2);

            $ping_blog_name = $xoopsConfig['sitename'];

            $ping_url = XOOPS_URL . '/';

            $ping_update = <<<EOF
	<?xml version="1.0"?>
	<methodCall>
		<methodName>weblogUpdates.ping</methodName>
		<params>
		<param><value>$ping_blog_name</value></param>
		<param><value>$ping_url</value></param>
		</params>
	</methodCall>
EOF;

            $ping_update = mb_convert_encoding($ping_update, 'UTF-8', 'EUC-JP');

            $ping_update_leng = mb_strlen($ping_update);

            foreach ($update_ping as $up) {
                if ('' != $up) {
                    $uph = preg_replace("http:\/\/", '', $up);

                    [$host, $uri] = preg_split('/', $uph, 2);

                    [$host, $port] = preg_split(':', $host);

                    if ('' == $port) {
                        $port = 80;

                        $add_port = '';
                    } else {
                        $add_port = ":$port";
                    }

                    $files = @fsockopen($host, $port, $errNo, $errStr, 10);

                    @fwrite($files, "POST /$uri HTTP/1.0\r\n");

                    @fwrite($files, "Host: $host$add_port\r\n");

                    @fwrite($files, "Content-Length: $ping_update_leng\r\n");

                    @fwrite($files, "User-Agent: XOOPS update pinger Ver 1.00\r\n");

                    @fwrite($files, "Content-Type: text/xml\r\n");

                    @fwrite($files, "\r\n");

                    @fwrite($files, (string)$ping_update);

                    fclose($files);
                }
            }
        }

        public function make_context($text, $words = [], $l = 255)
        {
            static $strcut = '';

            if (!$strcut) {
                $strcut = create_function(
                    '$a,$b,$c',
                    (function_exists('mb_strcut')) ? 'return mb_strcut($a,$b,$c);' : 'return strcut($a,$b,$c);'
                );
            }

            $text = str_replace(['&lt;', '&gt;', '&amp;', '&quot;', '&#039;'], ['<', '>', '&', '"', "'"], $text);

            if (!is_array($words)) {
                $words = [];
            }

            $ret = '';

            $q_word = str_replace(' ', '|', preg_quote(implode(' ', $words), '/'));

            if (preg_match("/$q_word/i", $text, $match)) {
                $ret = ltrim(preg_replace('/\s+/', ' ', $text));

                [$pre, $aft] = array_pad(preg_preg_split("/$q_word/i", $ret, 2), 2, '');

                $m = (int)($l / 2);

                $ret = (mb_strlen($pre) > $m) ? '... ' : '';

                $ret .= $strcut($pre, max(mb_strlen($pre) - $m + 1, 0), $m) . $match[0];

                $m = $l - mb_strlen($ret);

                $ret .= $strcut($aft, 0, min(mb_strlen($aft), $m));

                if (mb_strlen($aft) > $m) {
                    $ret .= ' ...';
                }
            }

            if (!$ret) {
                $ret = $strcut($text, 0, $l);
            }

            return htmlspecialchars($ret, ENT_NOQUOTES);
        }

        public function set_need_refresh($mode)
        {
            if ($mode) {
                setcookie('HypNeedRefresh', '1');
            } else {
                setcookie('HypNeedRefresh', '', time() - 3600);
            }
        }

        // HTML の meta タグから文字エンコーディングを取得する

        public function get_encoding_by_meta($html)
        {
            $codesets = [
                'shift_jis' => 'Shift_JIS',
                'x-sjis' => 'Shift_JIS',
                'euc-jp' => 'EUC-JP',
                'x-euc-jp' => 'EUC-JP',
                'iso-2022-jp' => 'JIS',
                'utf-8' => 'UTF-8',
            ];

            if (preg_match("/<meta[^>]*content=(?:\"|')[^\"'>]*charset=([^\"'>]+)(?:\"|')[^>]*>/is", $html, $match)) {
                $encode = mb_strtolower($match[1]);

                if (array_key_exists($encode, $codesets)) {
                    return $codesets[$encode];
                }
  

                return 'EUC-JP,UTF-8,Shift_JIS,JIS';
            }
  

            return 'EUC-JP,UTF-8,Shift_JIS,JIS';
        }

        // サムネイル画像を作成。

        // 成功ならサムネイルのファイルのパス、不成功なら元ファイルパスを返す

        public function make_thumb($o_file, $s_file, $max_width, $max_height, $zoom_limit = '5,90', $refresh = false)
        {
            //GD のバージョンを取得

            static $gd_ver = null;

            if (null === $gd_ver) {
                $gd_ver = self::gdVersion();
            }

            // すでに作成済み

            if (!$refresh && file_exists($s_file)) {
                return $s_file;
            }

            // gd fuction のチェック

            if ($gd_ver < 1 || !function_exists('imagecreate')) {
                return $o_file;
            }//gdをサポートしていない

            // gd のバージョンによる関数名の定義

            $imagecreate = ($gd_ver >= 2) ? 'imagecreatetruecolor' : 'imagecreate';

            $imageresize = ($gd_ver >= 2) ? 'imagecopyresampled' : 'imagecopyresized';

            $size = @getimagesize($o_file);

            if (!$size) {
                return $o_file;
            }//画像ファイルではない

            // 元画像のサイズ

            $org_w = $size[0];

            $org_h = $size[1];

            if ($max_width >= $org_w && $max_height >= $org_h) {
                return $o_file;
            }//指定サイズが元サイズより大きい

            // 縮小率の設定

            [$zoom_limit_min, $zoom_limit_max] = explode(',', $zoom_limit);

            $zoom = min(($max_width / $org_w), ($max_height / $org_h));

            if (!$zoom || $zoom < $zoom_limit_min / 100 || $zoom > $zoom_limit_max / 100) {
                return $o_file;
            }//ZOOM値が範囲外

            $width = $org_w * $zoom;

            $height = $org_h * $zoom;

            // サムネイルのファイルタイプが指定されている？(.jpg)

            $s_ext = '';

            $s_ext = preg_replace("/\.([^\.]+)$/", '$1', $s_file);

            switch ($size[2]) {
                case '1': //gif形式
                    if (function_exists('imagecreatefromgif')) {
                        $src_im = imagecreatefromgif($o_file);

                        $colortransparent = imagecolortransparent($src_im);

                        if ('jpg' != $s_ext && $colortransparent > -1) {
                            // 透過色あり

                            $dst_im = imagecreate($width, $height);

                            imagepalettecopy($dst_im, $src_im);

                            imagefill($dst_im, 0, 0, $colortransparent);

                            imagecolortransparent($dst_im, $colortransparent);

                            imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $width, $height, $org_w, $org_h);
                        } else {
                            // 透過色なし

                            $dst_im = $imagecreate($width, $height);

                            $imageresize($dst_im, $src_im, 0, 0, 0, 0, $width, $height, $org_w, $org_h);

                            imagetruecolortopalette($dst_im, imagecolorstotal($src_im));
                        }

                        touch($s_file);

                        if ('jpg' == $s_ext) {
                            imagejpeg($dst_im, $s_file);
                        } else {
                            if (function_exists('imagegif')) {
                                imagegif($dst_im, $s_file);
                            } else {
                                imagepng($dst_im, $s_file);
                            }
                        }

                        $o_file = $s_file;
                    }
                    break;
                case '2': //jpg形式
                    $src_im = imagecreatefromjpeg($o_file);
                    $dst_im = $imagecreate($width, $height);
                    $imageresize($dst_im, $src_im, 0, 0, 0, 0, $width, $height, $org_w, $org_h);
                    touch($s_file);
                    imagejpeg($dst_im, $s_file);
                    $o_file = $s_file;
                    break;
                case '3': //png形式
                    $src_im = imagecreatefrompng($o_file);
                    if (imagecolorstotal($src_im)) {
                        // PaletteColor

                        $colortransparent = imagecolortransparent($src_im);

                        if ('jpg' != $s_ext && $colortransparent > -1) {
                            // 透過色あり

                            $dst_im = imagecreate($width, $height);

                            imagepalettecopy($dst_im, $src_im);

                            imagefill($dst_im, 0, 0, $colortransparent);

                            imagecolortransparent($dst_im, $colortransparent);

                            imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $width, $height, $org_w, $org_h);
                        } else {
                            // 透過色なし

                            $dst_im = $imagecreate($width, $height);

                            $imageresize($dst_im, $src_im, 0, 0, 0, 0, $width, $height, $org_w, $org_h);

                            imagetruecolortopalette($dst_im, imagecolorstotal($src_im));
                        }
                    } else {
                        // TrueColor

                        $dst_im = $imagecreate($width, $height);

                        $imageresize($dst_im, $src_im, 0, 0, 0, 0, $width, $height, $org_w, $org_h);
                    }
                    touch($s_file);
                    if ('jpg' == $s_ext) {
                        imagejpeg($dst_im, $s_file);
                    } else {
                        imagepng($dst_im, $s_file);
                    }
                    $o_file = $s_file;
                    break;
                default:
                    break;
            }

            @imagedestroy($dst_im);

            @imagedestroy($src_im);

            return $o_file;
        }

        // GD のバージョンを取得

        // RETURN 0:GDなし, 1:Ver 1, 2:Ver 2

        public function gdVersion($user_ver = 0)
        {
            if (!extension_loaded('gd')) {
                return 0;
            }

            static $gd_ver = 0;

            // Just accept the specified setting if it's 1.

            if (1 == $user_ver) {
                $gd_ver = 1;

                return 1;
            }

            // Use the static variable if function was called previously.

            if (2 != $user_ver && $gd_ver > 0) {
                return $gd_ver;
            }

            // Use the gd_info() function if possible.

            if (function_exists('gd_info')) {
                $ver_info = gd_info();

                preg_match('/\d/', $ver_info['GD Version'], $match);

                $gd_ver = $match[0];

                return $match[0];
            }

            // If phpinfo() is disabled use a specified / fail-safe choice...

            if (false !== strpos(ini_get('disable_functions'), "phpinfo")) {
                if (2 == $user_ver) {
                    $gd_ver = 2;

                    return 2;
                }  

                $gd_ver = 1;

                return 1;
            }

            // ...otherwise use phpinfo().

            ob_start();

            phpinfo(8);

            $info = ob_get_contents();

            ob_end_clean();

            $info = mb_stristr($info, 'gd version');

            preg_match('/\d/', $info, $match);

            $gd_ver = $match[0];

            return $match[0];
        }
    }

    /*
     *   HTTPリクエストを発行し、データを取得する
     * $url     : http://から始まるURL(http://user:pass@host:port/path?query)
     * $method  : GET, POST, HEADのいずれか(デフォルトはGET)
     * $headers : 任意の追加ヘッダ
     * $post    : POSTの時に送信するデータを格納した配列('変数名'=>'値')
     * $redirect_max : HTTP redirectの回数制限
    */

    if (!class_exists('Hyp_HTTP_Request')) {
        class Hyp_HTTP_Request
        {
            public $url = '';

            public $method = 'GET';

            public $headers = '';

            public $post = [];

            // リダイレクト回数制限

            public $redirect_max = 10;

            // 同期モード or 非同期モード

            public $blocking = true;

            // 接続試行回数

            public $connect_try = 1;

            // 接続時タイムアウト

            public $connect_timeout = 30;

            // 通信時タイムアウト

            public $read_timeout = 10;

            // プロキシ使用？

            public $use_proxy = 0;

            // proxy ホスト

            public $proxy_host = 'proxy.xxx.yyy.zzz';

            // proxy ポート番号

            public $proxy_port = '';

            // プロキシサーバを使用しないホストのリスト

            public $no_proxy = [
                '127.0.0.1',
                'localhost',
                //'192.168.1.0/24',
                //'no-proxy.com',
            ];

            // プロキシ認証

            public $need_proxy_auth = 0;

            public $proxy_auth_user = '';

            public $proxy_auth_pass = '';

            // result
            public $query = '';   // Query String
            public $rc = '';      // Response Code
            public $header = '';  // Header
            public $data = '';    // Data

            public function init()
            {
                $this->url = '';

                $this->method = 'GET';

                $this->headers = '';

                $this->post = [];

                // result
                $this->query = '';   // Query String
                $this->rc = '';      // Response Code
                $this->header = '';  // Header
                $this->data = '';    // Data
            }

            public function get()
            {
                $max_execution_time = ini_get('max_execution_time');

                $max_execution_time = ($max_execution_time) ?: 30;

                $rc = [];

                $arr = parse_url($this->url);

                if (!$this->connect_try) {
                    $this->connect_try = 1;
                }

                $via_proxy = $this->use_proxy and via_proxy($arr['host']);

                // query

                $arr['query'] = isset($arr['query']) ? '?' . $arr['query'] : '';

                // port

                $arr['port'] = $arr['port'] ?? 80;

                $url_base = $arr['scheme'] . '://' . $arr['host'] . ':' . $arr['port'];

                $url_path = $arr['path'] ?? '/';

                $this->url = ($via_proxy ? $url_base : '') . $url_path . $arr['query'];

                $query = $this->method . ' ' . $this->url . " HTTP/1.0\r\n";

                $query .= 'Host: ' . $arr['host'] . "\r\n";

                $query .= "User-Agent: hyp_http_request/1.0\r\n";

                // proxyのBasic認証

                if ($this->need_proxy_auth and isset($this->proxy_auth_user) and isset($this->proxy_auth_pass)) {
                    $query .= 'Proxy-Authorization: Basic ' . base64_encode($this->proxy_auth_user . ':' . $this->proxy_auth_pass) . "\r\n";
                }

                // Basic 認証用

                if (isset($arr['user']) and isset($arr['pass'])) {
                    $query .= 'Authorization: Basic ' . base64_encode($arr['user'] . ':' . $arr['pass']) . "\r\n";
                }

                $query .= $this->headers;

                // POST 時は、urlencode したデータとする

                if ('POST' == mb_strtoupper($this->method)) {
                    if (is_array($this->post)) {
                        $_send = [];

                        foreach ($this->post as $name => $val) {
                            $_send[] = $name . '=' . urlencode($val);
                        }

                        $data = implode('&', $_send);

                        $query .= "Content-Type: application/x-www-form-urlencoded\r\n";

                        $query .= 'Content-Length: ' . mb_strlen($data) . "\r\n";

                        $query .= "\r\n";

                        $query .= $data;
                    } else {
                        $query .= 'Content-Length: ' . mb_strlen($this->post) . "\r\n";

                        $query .= "\r\n";

                        $query .= $this->post;
                    }
                } else {
                    $query .= "\r\n";
                }

                //set_time_limit($this->connect_timeout * $this->connect_try + 60);

                $fp = $connect_try_count = 0;

                while (!$fp && $connect_try_count < $this->connect_try) {
                    @set_time_limit($this->connect_timeout + $max_execution_time);

                    $fp = fsockopen(
                        $via_proxy ? $this->proxy_host : $arr['host'],
                        $via_proxy ? $this->proxy_port : $arr['port'],
                        $errno,
                        $errstr,
                        $this->connect_timeout
                    );

                    if ($fp) {
                        break;
                    }

                    $connect_try_count++;

                    sleep(2); //2秒待つ
                }

                if (!$fp) {
                    $this->query = $query;  // Query String
                    $this->rc = $errno;  // エラー番号
                    $this->header = '';      // Header
                    $this->data = $errstr; // エラーメッセージ
                    return;
                }

                fwrite($fp, $query);

                // 非同期モード

                if (!$this->blocking) {
                    fclose($fp);

                    $this->query = $query;

                    $this->rc = 200;

                    $this->header = '';

                    $this->data = 'Blocking mode is FALSE';

                    return;
                }

                $response = '';

                while (!feof($fp)) {
                    if ($this->read_timeout) {
                        @set_time_limit($this->read_timeout + $max_execution_time);

                        stream_set_timeout($fp, $this->read_timeout);
                    }

                    $_response = fread($fp, 4096);

                    $_status = stream_get_meta_data($fp);

                    if (false === $_status['timed_out']) {
                        $response .= $_response;
                    } else {
                        fclose($fp);

                        $this->query = $query;

                        $this->rc = 408;

                        $this->header = '';

                        $this->data = 'Request Time-out';

                        return;
                    }
                }

                fclose($fp);

                $resp = explode("\r\n\r\n", $response, 2);

                $rccd = explode(' ', $resp[0], 3); // array('HTTP/1.1','200','OK\r\n...')

                $rc = (int)$rccd[1];

                // Redirect

                switch ($rc) {
                    case 302: // Moved Temporarily
                    case 301: // Moved Permanently
                        if (preg_match('/^Location: (.+)$/m', $resp[0], $matches) and --$this->redirect_max > 0) {
                            $this->url = trim($matches[1]);

                            if (!preg_match('/^https?:\//', $this->url)) { // no scheme
                                if ('/' != $this->url[0]) { // Relative path
                                    // to Absolute path
                                    $this->url = mb_substr($url_path, 0, mb_strrpos($url_path, '/')) . '/' . $this->url;
                                }

                                // add sheme,host

                                $this->url = $url_base . $this->url;
                            }

                            return $this->get();
                        }
                }

                $this->query = $query;    // Query String
                $this->rc = $rc;          // Response Code
                $this->header = $resp[0]; // Header
                $this->data = $resp[1];   // Data
            }

            // プロキシを経由する必要があるかどうか判定

            public function via_proxy($host)
            {
                static $ip_pattern = '/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?:\/(.+))?$/';

                if (!$this->use_proxy) {
                    return false;
                }

                $ip = gethostbyname($host);

                $l_ip = ip2long($ip);

                $valid = (is_int($l_ip) and long2ip($l_ip) == $ip); // valid ip address

                foreach ($this->no_proxy as $network) {
                    if ($valid and preg_match($ip_pattern, $network, $matches)) {
                        $l_net = ip2long($matches[1]);

                        $mask = array_key_exists(2, $matches) ? $matches[2] : 32;

                        $mask = is_numeric($mask) ? pow(2, 32) - pow(2, 32 - $mask) : // "10.0.0.0/8"
                            ip2long($mask);                 // "10.0.0.0/255.0.0.0"
                        if (($l_ip & $mask) == $l_net) {
                            return false;
                        }
                    } else {
                        if (preg_match('/' . preg_quote($network, '/') . '/', $host)) {
                            return false;
                        }
                    }
                }

                return true;
            }
        }
    }

    // create a instance in global scope

    //$GLOBALS['hypCommonFunc'] = new HypCommonFunc() ;

    // Make context for search by nao-pon

    if (!function_exists('xoops_make_context')) {
        function xoops_make_context($text, $words = [], $l = 255)
        {
            return HypCommonFunc::make_context($text, $words, $l);
        }
    }

    if (!function_exists('xoops_update_rpc_ping')) {
        function xoops_update_rpc_ping($default_update = 'http://bulkfeeds.net/rpc http://ping.myblog.jp http://ping.bloggers.jp/rpc/ http://blog.goo.ne.jp/XMLRPC http://ping.cocolog-nifty.com/xmlrpc http://rpc.technorati.jp/rpc/ping')
        {
            return HypCommonFunc::update_rpc_ping($default_update);
        }
    }
}
