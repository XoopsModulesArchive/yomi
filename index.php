<?php
################################################################
# Yomi-Search Ver4 [サーチエンジン] (Since:1999/09/22)
#   (C) 1999-2001 by yomi
#   Eメール: yomi@pekori.to
#   ホームページ: http://yomi.pekori.to/
################################################################

## ---[利用規約]------------------------------------------------------------+
## 1. このスクリプトはフリーソフトです。このスクリプトを使用した
##    いかなる損害に対して作者は一切の責任を負いません。
## 2. このスクリプトを使用した時点で利用規約(http://yomi.pekori.to/kiyaku.html)
##    に同意したものとみなさせていただきます。
##    ご使用になる前に必ずお読みください。
## 3. 同梱の「アイコン (new.gif/recom.gif/sougogif.gif) 」の著作権は
##   「牛飼いとアイコンの部屋  (http://www.ushikai.com/)」に帰属しています。
## -------------------------------------------------------------------------+

use Xmf\Request;

if (is_readable('shorturl.php')) {
    include 'shorturl.php';
}

if (defined('YOMI_SHORTURL') && YOMI_SHORTURL) {
    if (!empty($_SERVER['PATH_INFO']) && preg_match("/^\/(.+)$/", $_SERVER['PATH_INFO'], $match)) {
        $match[1] = preg_replace("/\/$/", '', $match[1]);

        if ('new' == $match[1]) {
            $_GET['mode'] = 'new';
        } elseif ('renew' == $match[1]) {
            $_GET['mode'] = 'renew';
        } elseif ('m1' == $match[1]) {
            $_GET['mode'] = 'm1';
        } elseif ('m2' == $match[1]) {
            $_GET['mode'] = 'm2';
        } else {
            $parms = explode('/', $match[1]);

            $_GET['kt'] = implode('_', $parms);

            $_GET['mode'] = 'kt';
        }
    }
}

//For XOOPS
include 'header.php';

require_once dirname(__DIR__) . '/cfg.php';
require_once dirname(__DIR__) . '/temp.php';

$EST['script'] = $EST['cgi_path_url'] . $EST['script'];
$EST['search'] = $EST['cgi_path_url'] . $EST['search'];
$EST['rank'] = $EST['cgi_path_url'] . $EST['rank'];
$EST['admin'] = $EST['cgi_path_url'] . $EST['admin'];
$EST['html_path_url'] = $EST['cgi_path_url'] . $EST['html_path_url'];
$EST['img_path_url'] = $EST['cgi_path_url'] . $EST['img_path_url'];

if (!isset($_GET['mode'])) {
    $_GET['mode'] = '';
}
#各モードへ分岐
#-----------------#
if ($_GET['mode']) {
    if (!$EST['home']) {
        $EST['home'] = $EST['script'];
    }

    if ('kt' == $_GET['mode']) {
        #各カテゴリの時の表示タイトル

        $Stitle = $ganes[$_GET['kt']];

        [$Spre_kt_file] = explode('_', $_GET['kt']);
    } elseif ('new' == $_GET['mode']) {
        $Stitle = '新着サイト';

        $Spre_kt_file = 'new_ys';
    } #新着サイトの表示タイトル,ファイル名

    elseif ('renew' == $_GET['mode']) {
        $Stitle = '更新サイト';

        $Spre_kt_file = 'renew_ys';
    } #更新サイトの表示タイトル,ファイル名

    elseif ('m1' == $_GET['mode']) {
        $Stitle = '超おすすめサイト';

        $Spre_kt_file = 'm1_ys';
    } #m1サイトの表示タイトル,ファイル名

    elseif ('m2' == $_GET['mode']) {
        $Stitle = 'おすすめサイト';

        $Spre_kt_file = 'm2_ys';
    } #m2サイトの表示タイトル,ファイル名

    elseif ('random' == $_GET['mode']) {
        random();
    } #ランダムジャンプ

    elseif ('link' == $_GET['mode']) {
        yomi_link();
    } #リンクジャンプ

    else {
        mes('指定したモードは存在しません(mode=' . $_GET['mode'] . ')', 'モード選択エラー', 'java');
    }

    ##ページ設定

    $_GET['page'] = (isset($_GET['page'])) ? preg_replace("/\D/", '', $_GET['page']) : 1;

    if ($_GET['page'] < 1 || $_GET['page'] > 1000) {
        $_GET['page'] = 1;
    }

    require $EST['temp_path'] . 'kt.html';
} elseif ($EST['home'] && $EST['top']) {
    location($EST['home']);
} else {
    $query = 'SELECT COUNT(*) FROM ' . $EST['sqltb'] . 'log';

    $result = $xoopsDB->query($query) || die("Query failed yomi40 $query");

    $tmp = $GLOBALS['xoopsDB']->fetchRow($result); #総登録数

    $Cpre_gane = $tmp[0];

    $CK_data = get_cookie();

    if (isset($CK_data[7])) {
        $EST['defo_hyouji'] = $CK_data[7];
    }

    require $EST['temp_path'] . 'top.html';
}
exit;

#(1)メッセージ画面出力(mes)
#書式:mes($arg1,$arg2,$arg3);
#機能:メッセージ画面を出力する
#引数:$arg1=>表示するメッセージ
#     $arg2=>ページのタイトル(省略時は「メッセージ画面」)
#     $arg3=>・JavaScriptによる「戻る」ボタン表示=java
#            ・HTTP_REFERERを使う場合=env
#            ・管理室へのボタン=kanri
#            ・通常のURL又はパスを指定する場合にはそのURL又はパスを記入
#            ・省略時は非表示
#戻り値:なし
function mes($MES, $TITLE, $arg3)
{
    global $EST;

    global $xoopsOption, $xoopsConfig, $xoopsLogger, $xoopsTpl;

    global $x_ver, $ver;

    if (!$TITLE) {
        $TITLE = 'メッセージ画面';
    }

    if ('java' == $arg3) {
        $BACK_URL = '<form><input type=button value="&nbsp;&nbsp;&nbsp;&nbsp;戻る&nbsp;&nbsp;&nbsp;&nbsp;" onClick="history.back()"></form>';
    } elseif ('env' == $arg3) {
        $BACK_URL = '【<a href="' . Request::getString('HTTP_REFERER', '', 'SERVER') . '">戻る</a>】';
    } elseif (!$arg3) {
        $BACK_URL = '';
    } else {
        $BACK_URL = "【<a href=\"$arg3\">戻る</a>】";
    }

    require $EST['temp_path'] . 'mes.html';

    exit;
}

#(2)ランダムジャンプ(random)
function random()
{
    global $EST, $xoopsDB;

    $i = 1;

    $query = 'SELECT * FROM ' . $EST['sqltb'] . 'log';

    $result = $xoopsDB->query($query) || die("Query failed yomi85 $query");

    $total_url = $GLOBALS['xoopsDB']->getRowsNum($result);

    [$usec, $sec] = explode(' ', microtime());

    mt_srand((float)$sec + ((float)$usec * 100000));

    $id = mt_rand(1, $total_url);

    $query = 'SELECT url FROM ' . $EST['sqltb'] . 'log';

    $result = $xoopsDB->query($query) || die('Query failed yomi91');

    while (false !== ($tmp = $GLOBALS['xoopsDB']->fetchArray($result))) {
        if ($i == $id) {
            break;
        }

        $i++;
    }

    //$GLOBALS['xoopsDB']->close($link);

    location($tmp['url']);

    exit;
}

#(3)リンクジャンプ処理(link)
function yomi_link()
{
    $_GET['id'] = preg_replace("/\D/", '', $_GET['id']);

    if ($_GET['id']) {
        #refererチェック

        if (!Request::getString('HTTP_REFERER', '', 'SERVER')) {
            $fl = 1;
        } #refererが無いときにカウントしない場合にはこの行を削除

        $ref_list = explode(',', $EST['rank_ref']);

        if (!$EST['rank_ref']) {
            $fl = 1;
        } else {
            foreach ($ref_list as $tmp) {
                if (mb_strstr(Request::getString('HTTP_REFERER', '', 'SERVER'), $tmp)) {
                    $fl = 1;
                }
            }
        }

        if ($fl) {
            $_GET['id'] = str_replace("\n", '', $_GET['id']);

            $fp = fopen($EST['log_path'] . 'rank_temp_ys.cgi', 'ab');

            flock($fp, LOCK_EX);

            fwrite($fp, $_GET['id'] . '<>' . time() . '<>' . $_SERVER['REMOTE_ADDR'] . "\n");

            fclose($fp);
        }
    }

    if ($_GET['url']) {
        location($_GET['url']);
    }
}
##-- end of yomi.php --##
