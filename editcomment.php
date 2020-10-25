<?php

include 'header.php';
require dirname(__DIR__) . '/temp.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

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

global $xoopsUser, $xoopsDB;

$pollcomment = new XoopsComments($xoopsDB->prefix('yomi_comments'), $comment_id);
$nohtml = $pollcomment->getVar('nohtml');
$nosmiley = $pollcomment->getVar('nosmiley');
$icon = $pollcomment->getVar('icon');
$item_id = $pollcomment->getVar('item_id');
$subject = $pollcomment->getVar('subject', 'E');
$message = $pollcomment->getVar('comment', 'E');
OpenTable();
require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
CloseTable();
include 'footer.php';
