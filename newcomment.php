<?php

include 'header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';

// パラメータを変数にセット
$vars = array_merge($_POST, $_GET);
$prms = ['item_id', 'comment_id', 'mode', 'order', 'ok'];
foreach ($prms as $prm) {
    if (isset($vars[$prm])) {
        if ('item_id' == $prm || 'comment_id' == $prm) {
            $vars[$prm] = (int)$vars[$prm];
        }

        $$prm = $vars[$prm];
    } else {
        $$prm = false;
    }
}

$q = 'select title from ' . $xoopsDB->prefix('yomi_log') . " where id=$item_id";
$result = $xoopsDB->query($q);
[$ltitle] = $xoopsDB->fetchRow($result);
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
//$subject = htmlspecialchars($ltitle);
$subject = htmlspecialchars($ltitle, ENT_QUOTES | ENT_HTML5);
$pid = 0;
OpenTable();
echo '<h4>・ ' . $subject . ' のコメント投稿</h4>';
require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
CloseTable();
include 'footer.php';
