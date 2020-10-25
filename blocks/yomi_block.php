<?php

function b_yomi_show($options)
{
    global $ganes, $gane_top, $EST;

    if (is_readable(XOOPS_ROOT_PATH . '/modules/yomi/shorturl.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/yomi/shorturl.php';
    }

    require_once XOOPS_ROOT_PATH . '/modules/yomi/pl/cfg.php';

    require_once XOOPS_ROOT_PATH . '/modules/yomi/pl/temp.php';

    $EST['script'] = $EST['cgi_path_url'] . $EST['script'];

    $block = [];

    $table_cols = $options[0];

    $table_per = round(100 / $table_cols);

    $Ekt = $EST['script'] . '?mode=kt&kt=';

    $yomi_reg_tag = XOOPS_URL . '/modules/yomi/' . 'regist_ys.php?mode=new';

    $yomi_new_tag = $EST['script'] . '?mode=new';

    $block['title'] = _MI_YOMI_BNAME1;

    $block['content'] = '<table style="width:100%;" cellpadding=3>';

    $i = 0;

    $table_kt = $table_cols;

    $st_flag = 0;

    $cld_fl = 0;

    gane_st(); #カテゴリ属性をロード

    ksort($ganes, SORT_STRING);

    reset($ganes);

    while (list($Gnos, $value) = each($ganes)) {
        $Gno = (explode('_', $Gnos));

        if (1 == count($Gno)) { ###最上層カテゴリの場合
            if ($st_flag && $cld_fl) {
                $block['content'] .= '<a href="' . yomi_makelink($bf_kt_no) . '">...</a>';
            } else {
                $st_flag = 1;
            }

            $cld_fl = 0;

            if ($table_kt == $table_cols && 1 == $i) {
                $block['content'] .= '</small>';

                $block['content'] .= '</td></tr>';
            }

            if (0 == $i) {
                $i = 1;
            }

            if ($table_kt == $table_cols) {
                $block['content'] .= '<tr valign=top>';

                $table_kt = 1;
            } else {
                $table_kt++;
            }

            if (1 != $table_kt) {
                $block['content'] .= '</small>';

                $block['content'] .= '</td>';
            }

            $block['content'] .= '<td width="' . $table_per . '%" style="padding:3px;text-align:left;"><a href="' . yomi_makelink($Gnos) . '"><b>' . $value . '</b></a><br>';

            $block['content'] .= '<small>';

            $bf_kt_no = $Gnos;
        } else { ####サブカテゴリの場合
            if (isset($gane_top[$Gnos])) {
                $cld_fl = 1;

                //list($Gname) = explode(":",$Gname);

                $block['content'] .= '<a href="' . yomi_makelink($Gnos) . "\">$value</a>, ";
            }
        }
    }

    unset($gane_top); #カテゴリ属性をアンロード

    if ($cld_fl) {
        $block['content'] .= '<a href="' . $Ekt . $bf_kt_no . '">...</a>';
    }

    $block['content'] .= '</td></tr>';

    $block['content'] .= '<tr><td colspan=' . $table_cols . ' style="text-align:center;padding:3px;">';

    $block['content'] .= '- <a href="' . $yomi_reg_tag . '">' . _MB_YOMI_NEWREG . '</a> - ';

    $block['content'] .= '<a href="' . $yomi_new_tag . '">' . _MB_YOMI_NEWARV . '</a> -';

    $block['content'] .= '<br><span style="font-size:90%;">総登録件数: <script language="JavaScript" src="' . XOOPS_URL . '/modules/yomi/count.js.php"></script><noscript> ?(要JavaScript) </noscript>サイトです。</span>';

    $block['content'] .= '</td></tr></table>';

    return $block;
}

function b_yomi_top($options)
{
    global $xoopsDB;

    $block = [];

    $block['title'] = _MI_YOMI_BNAME2;

    $block['content'] = '';

    $mode = '';    #人気ランキング
    //$mode = "rank_bf";	#前回の人気ランキング
    //$mode = "all";	#人気ランキング(累計)
    $kensu = $options[0]; #表示する件数
    $kikan = $options[1];    #集計期間（日

    $log_lines = [];

    $Clog = 0;

    $time = time();

    if ('all' == $mode) {
        $start = 0;

        $end = $time;
    } elseif ('rank_bf' == $mode) {
        $start = $time - $kikan * 172800;

        $end = $time - $kikan * 86400 + 1;
    } else {
        $start = $time - $kikan * 86400;

        $end = $time;
    }

    $ranking = [];

    $query = 'SELECT id,COUNT(*) AS pt FROM ' . $xoopsDB->prefix('yomi_rank') . " WHERE time BETWEEN $start AND $end GROUP BY id ORDER BY pt DESC";

    $result = $xoopsDB->query($query) || die("Query failed rank109 $query");

    while (false !== ($Rank = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $id = (string)$Rank['id'];

        $ranking[$id] = $Rank['pt'];
    }

    $query = 'SELECT id,COUNT(*) AS pt FROM ' . $xoopsDB->prefix('yomi_rev') . " WHERE time BETWEEN $start AND $end GROUP BY id ORDER BY pt DESC";

    $result = $xoopsDB->query($query) || die("Query failed rank109 $query");

    while (false !== ($Rank = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $id = (string)$Rank['id'];

        $ranking[$id] = $Rank['pt'];
    }

    arsort($ranking);

    $count = 0;

    foreach ($ranking as $id => $pt) {
        $count++;

        if ($count > $kensu) {
            break;
        }

        $kt_fl = 0;

        $query = 'SELECT * FROM ' . $xoopsDB->prefix('yomi_log') . " WHERE id='" . $id . "' LIMIT 1";

        $result2 = $xoopsDB->query($query) || die("Query failed rank120 $query");

        $Slog = $GLOBALS['xoopsDB']->fetchRow($result2);

        if ($Slog[0]) {
            $Slog['pt'] = $pt;

            $log_lines[] = $Slog;

            $Clog++;
        }
    }

    if ($Clog) {
        $block['content'] .= '<div style="text-align:left;">';

        //if($_GET['page'] == "1"){$pre_rank=1;}else{$pre_rank=0;}

        $rank = $pre_rank = 0;

        foreach ($log_lines as $Slog) {
            $pt = $Slog['pt'];

            $jump_url = XOOPS_URL . '/modules/yomi/jump.php?id=' . $Slog[0];

            $link_url = XOOPS_URL . '/modules/yomi/single_link.php?item_id=' . $Slog[0];

            $block['content'] .= '<a href=' . $jump_url . ' target="_blank" title="直接ジャンプ"><strong><big>&middot;</big></strong></a>&nbsp;<a href="' . $link_url . '">' . $Slog[1] . '</a><small>(' . $pt . 'pt)</small><br>';
        }

        $block['content'] .= '</div>';

        $block['content'] .= '<div style="text-align:center"><small>(過去' . $kikan . '日間のTOP' . $kensu . ')</small></div>';
    } else {
        $block['content'] .= 'ランキングデータを集計中';
    }

    return $block;
}

function b_yomi_new($options)
{
    global $xoopsDB;

    $block = [];

    $block['title'] = _MI_YOMI_BNAME3;

    $block['content'] = '';

    $kensu = $options[0]; #表示する件数

    $block['content'] .= '<div style="text-align:left;">';

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('yomi_log') . ' WHERE id > 0 ORDER BY stamp DESC LIMIT ' . $kensu;

    $result = $xoopsDB->query($query) || die("Query failed rank109 $query");

    while (false !== ($Slog = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $jump_url = XOOPS_URL . '/modules/yomi/jump.php?id=' . $Slog['id'];

        $link_url = XOOPS_URL . '/modules/yomi/single_link.php?item_id=' . $Slog['id'];

        $block['content'] .= '<a href=' . $jump_url . ' target="_blank" title="直接ジャンプ"><strong><big>&middot;</big></strong></a>&nbsp;<a href="' . $link_url . '">' . $Slog['title'] . '</a><small>(' . mb_substr($Slog['last_time'], 0, 10) . ')</small><br>';
    }

    $block['content'] .= '</div>';

    return $block;
}

function b_yomi_edit($options)
{
    $form = '' . _MB_YOMI_COLS . '&nbsp;';

    $form .= "<input type='text' name='options[]' value='" . $options[0] . "'>";

    return $form;
}

function b_yomi_top_edit($options)
{
    $form = '' . _MB_YOMI_NUM . '&nbsp;';

    $form .= "<input type='text' name='options[]' value='" . $options[0] . "'><br>";

    $form .= '' . _MB_YOMI_PER . '&nbsp;';

    $form .= "<input type='text' name='options[]' value='" . $options[1] . "'>";

    return $form;
}

function b_yomi_new_edit($options)
{
    $form = '' . _MB_YOMI_NUM . '&nbsp;';

    $form .= "<input type='text' name='options[]' value='" . $options[0] . "'>";

    return $form;
}
