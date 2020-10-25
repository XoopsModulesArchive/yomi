<?php

// For XOOPS
include 'header.php';

require dirname(__DIR__) . '/cfg.php';
require dirname(__DIR__) . '/temp.php';

if (ini_get('magic_quotes_gpc')) {
    $_GET = array_map('stripslashes', $_GET);
}
$_GET = array_map('htmlspecialchars', $_GET);

if (!$_GET[page]) {
    $_GET[page] = 1;
}

#(1)検索結果表示画面(search)
#入力値の整形
if (!$_GET['item_id']) {
    $_GET['item_id'] = $_POST['item_id'];
}
if (preg_match("/\D/", $_GET['item_id']) || (!$_GET['item_id'])) {
    mes('指定値が不正です', 'ページ指定エラー', 'java');
}
$item_id = $_GET['item_id'];
#結果表示
require "$EST[temp_path]search.html";
exit;

function open_for_search($target_kt, $target_day, $sort)
{
    global $xoopsDB, $EST, $item_id, $write;

    $i = 0;

    $query = "SELECT * FROM $EST[sqltb]log WHERE id=" . $item_id;

    ##検索処理実行

    $result = $xoopsDB->query($query) || die('Query failed1');

    while (false !== ($line = $GLOBALS['xoopsDB']->fetchRow($result))) {
        $write[] = $line;

        $i++;
    }

    if (!count($write)) {
        $i = 0;
    }

    return $i;
}

#(5)外部検索エンジンへのリンク一覧を表示(&PR_mata_page)
function PR_meta_page($location_list)
{
    $T_flag = 1;

    echo '<table style="width:90%;padding:8px;" align="center" width="90%" cellpadding=8>';

    foreach ($location_list as $list) {
        [$Dengine, $Durl] = explode('<>', $list);

        if (5 == $T_flag) {
            echo '</tr>';

            $T_flag = 1;
        }

        if (1 == $T_flag) {
            echo '<tr>';
        } ?>
        <td class="yomi-s" style="text-align:center;"><a href="<?= $Durl ?>" target="<?= $_POST['target'] ?>"><font size="+1"><?= $Dengine ?></font></a></td>
        <?php
        $T_flag++;
    }

    if (2 != $T_flag) {
        echo '</tr>';
    }

    echo '</table>';
}

#(t1)メッセージ画面出力(mes)
#書式:&mes($arg1,$arg2,$arg3);
#機能:メッセージ画面を出力する
#引数:$arg1=>表示するメッセージ
#     $arg2=>ページのタイトル(省略時は「メッセージ画面」)
#     $arg3=>・JavaScriptによる「戻る」ボタン表示=java
#            ・HTTP_REFERERを使う場合=env
#            ・管理室へのボタン=kanri
#            ・通常のURL又はパスを指定する場合にはそのURL又はパスを記入
#            ・省略時は非表示
#戻り値:なし
function mes($MES, $TITLE, $arg3 = '')
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
        $BACK_URL = "【<a href=\"$_SERVER[HTTP_REFERER]\">戻る</a>】";
    } elseif (!$arg3) {
        $BACK_URL = '';
    } else {
        $BACK_URL = "【<a href=\"$arg3\">戻る</a>】";
    }

    require "$EST[temp_path]mes.html";

    exit;
}

?>
