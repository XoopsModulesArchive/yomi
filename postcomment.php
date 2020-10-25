<?php

include '../../mainfile.php';

$xoopsOption['show_rblock'] = 0;
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

global $xoopsUser, $xoopsDB;
// パラメータを変数にセット
$vars = array_merge($_POST, $_GET);
$prms = ['item_id', 'comment_id', 'mode', 'order', 'ok', 'subject', 'icon', 'message', 'nosmiley', 'pid', 'preview', 'post'];
foreach ($prms as $prm) {
    if (isset($vars[$prm])) {
        if ('item_id' == $prm || 'comment_id' == $prm || 'pid' == $prm) {
            $vars[$prm] = (int)$vars[$prm];
        }

        $$prm = $vars[$prm];
    } else {
        $$prm = false;
    }
}

require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';
if (isset($_POST['preview'])) {
    $_POST['op'] = 'preview';
}
if (isset($_POST['post'])) {
    $_POST['op'] = 'post';
}

if (isset($_POST['op'])) {
    $add = '1';

    switch ($_POST['op']) {
        case 'preview':
            require XOOPS_ROOT_PATH . '/header.php';
            $myts = MyTextSanitizer::getInstance();
            $p_subject = htmlspecialchars($subject, ENT_QUOTES | ENT_HTML5);
            if ($nosmiley && $nohtml) {
                $p_comment = $myts->previewTarea($message, 0, 0, 1);
            } elseif ($nohtml) {
                $p_comment = $myts->previewTarea($message, 0, 1, 1);
            } elseif ($nosmiley) {
                $p_comment = $myts->previewTarea($message, 1, 0, 1);
            } else {
                $p_comment = $myts->previewTarea($message, 1, 1, 1);
            }
            themecenterposts($p_subject, $p_comment);
            $subject = htmlspecialchars($subject, ENT_QUOTES | ENT_HTML5);
            $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5);
            OpenTable();
            require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
            CloseTable();
            break;
        case 'post':
            require XOOPS_ROOT_PATH . '/header.php';
            if (!empty($comment_id)) {
                $photocomment = new XoopsComments($xoopsDB->prefix('yomi_comments'), $comment_id);

                $accesserror = 0;

                if ($xoopsUser) {
                    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                        if ($photocomment->getVar('user_id') != $xoopsUser->getVar('uid')) {
                            $accesserror = 1;
                        }
                    }
                } else {
                    $accesserror = 1;
                }

                if (1 == $accesserror) {
                    redirect_header('single_link.php?item_id=' . $item_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '', 1, _PL_EDITNOTALLOWED);

                    exit();
                }

                $add = '0';
            } else {
                $photocomment = new XoopsComments($xoopsDB->prefix('yomi_comments'));

                $photocomment->setVar('pid', $pid);

                $photocomment->setVar('item_id', $item_id);

                $photocomment->setVar('ip', $REMOTE_ADDR);

                if ($xoopsUser) {
                    $uid = $xoopsUser->getVar('uid');
                } else {
                    if (1 == $xoopsConfig['anonpost']) {
                        $uid = 0;
                    } else {
                        redirect_header('single_link.php?item_id=' . $item_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '', 1, _PL_ANONNOTALLOWED);

                        exit();
                    }
                }

                $photocomment->setVar('user_id', $uid);
            }
            $photocomment->setVar('subject', $subject);
            $photocomment->setVar('comment', $message);
            $photocomment->setVar('nohtml', $nohtml);
            $photocomment->setVar('nosmiley', $nosmiley);
            $photocomment->setVar('icon', $icon);
            $photocomment->store();

            $xoopsDB->query('update ' . $xoopsDB->prefix('yomi_log') . ' set comments=comments+' . $add . " where id=$item_id ");

            redirect_header('single_link.php?item_id=' . $item_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '', 2, _PL_THANKSFORPOST);
            exit();
            break;
    }
} else {
    //redirect_header("single_link.php?item_id=".$item_id."&amp;order=".$order."&amp;mode=".$mode."",2);

    exit();
}
require XOOPS_ROOT_PATH . '/footer.php';
