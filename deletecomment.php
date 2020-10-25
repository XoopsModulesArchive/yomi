<?php

include '../../mainfile.php';

$prms = ['comment_id', 'mode', 'order', 'ok'];
foreach ($prms as $prm) {
    if (isset($vars[$prm])) {
        if ('item_id' == $prm) {
            $vars[$prm] = (int)$vars[$prm];
        }

        $$prm = $vars[$prm];
    } else {
        $$prm = false;
    }
}

$xoopsOption['show_rblock'] = 0;
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

global $xoopsUser, $xoopsDB;

require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';

if (!$xoopsUser) {
    require XOOPS_ROOT_PATH . '/header.php';

    echo '<h4>' . _PL_DELNOTALLOWED . '</h4>';

    echo '<br>';

    echo '<a href="javascript:history.go(-1)">' . _PL_GOBACK . '</a>';

    require XOOPS_ROOT_PATH . '/footer.php';

    exit();
}  
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        require XOOPS_ROOT_PATH . '/header.php';

        echo '<h4>' . _PL_DELNOTALLOWED . '</h4>';

        echo '<br>';

        echo '<a href="javascript:history.go(-1)">' . _PL_GOBACK . '</a>';

        require XOOPS_ROOT_PATH . '/footer.php';

        exit();
    }

if (!empty($ok)) {
    if (!empty($comment_id)) {
        $photocomment = new XoopsComments($xoopsDB->prefix('yomi_comments'), $comment_id);

        $deleted = $photocomment->delete();

        $item_id = $photocomment->getVar('item_id');

        [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('yomi_comments') . " where item_id = $item_id"));

        $xoopsDB->queryF('update ' . $xoopsDB->prefix('yomi_log') . " set comments=$numrows where id=$item_id ");
    }

    redirect_header('single_link?item_id=' . $item_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '', 2, _PL_COMMENTSDEL);

    exit();
}  
    require XOOPS_ROOT_PATH . '/header.php';
    OpenTable();
    echo '<div align="center">';
    echo "<h4 style='color:#ff0000;'>" . _PL_AREYOUSURE . '</h4>';
    echo "<table><tr><td>\n";
    echo yomi_TextForm('deletecomment.php?comment_id=' . $comment_id . '&amp;mode=' . $mode . '&amp;order=' . $order . '&amp;ok=1', _YES);
    echo "</td><td>\n";
    echo yomi_TextForm('javascript:history.go(-1)', _NO);
    echo "</td></tr></table>\n";
    echo '</div>';
    CloseTable();

require XOOPS_ROOT_PATH . '/footer.php';

function yomi_TextForm($url, $value)
{
    return '<form action="' . $url . '" method="post"><input type="submit" value="' . $value . '"></form>';
}
