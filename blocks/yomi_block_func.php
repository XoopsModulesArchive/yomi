<?php

if (!class_exists('HypCommonFunc')) {
    require_once XOOPS_ROOT_PATH . '/modules/yomi/include/hyp_common_func.php';
}
function b_yomi_show_items($Slog, $title_m, $message_m, $w_m, $h_m)
{
    //ini_set('allow_url_fopen',1);

    // 変数セット

    foreach ($Slog as $key => $val) {
        $$key = $val;
    }

    //$Slog['id'],$Slog['title'],$Slog['url'],$Slog['message'],$Slog['banner'],$Slog['last_time']

    $ret = '';

    // Yomiサーチの詳細画面を開く場合

    $jump_url = XOOPS_URL . '/modules/yomi/single_link.php?item_id=' . $id;

    $target = " target='_self'";

    // 直接対象サイトへ移動する場合

    //$jump_url= XOOPS_URL."/modules/yomi/jump.php?id=".$id;

    //$target = " target='_blank'";

    // タイトル文字数制限

    $title_t = mb_strimwidth($title, 0, $title_m + 1, '..');

    // 説明文字数制限

    if (0 == $message_m) {
        $message_t = '';
    } else {
        $message_t = mb_strimwidth($message, 0, $message_m + 1, '..');
    }

    // ' をクォート

    $message = str_replace("'", '&#039;', $message);

    $title = str_replace("'", '&#039;', $title);

    // キャッシュロゴ取得

    [$banner, $img_size] = b_yomi_get_img_cache($banner, $id);

    // イメージサイズ取得

    //$img_size = (strpos($banner,XOOPS_URL) === 0)? getimagesize($banner) : FALSE;

    // イメージサイズ調整

    if (!$w_m && !$h_m) {
        $banner = '';
    } elseif (!$w_m) {
        $size_tag = " height='{$h_m}'";
    } elseif (!$h_m) {
        $size_tag = " width='{$w_m}'";
    } else {
        if ($img_size) {
            $_w = $img_size[0] / $w_m;

            $_h = $img_size[1] / $h_m;

            $zoom = max($_w, $_h);

            if ($zoom) {
                $w_m = floor($img_size[0] / $zoom);

                $h_m = floor($img_size[1] / $zoom);
            }
        }

        $size_tag = " width='{$w_m}' height='{$h_m}'";
    }

    if ($banner) {
        $ret .= "<div class='xoops_yomi_block_image'><a href='$jump_url' alt='$title' title='$title'$target><img src='$banner' {$size_tag}></a></div>";
    }

    $ret .= "<div class='xoops_yomi_block_title'><a href=\"" . $jump_url . "\" title='$title'$target>" . $title_t . '</a></div>';

    if ($message_t) {
        $ret .= "<div title='$message' class='xoops_yomi_block_message'>" . $message_t . '</div>';
    }

    if ($pt) {
        $ret .= "<div class='xoops_yomi_block_pt'>{$pt}pt <span class='xoops_yomi_block_in_out'>(IN:{$in}, OUT:{$out})</span></div>";
    }

    return $ret;
}

function b_yomi_show_cols($result, $cols, $title_m, $message_m, $w_m, $h_m)
{
    $xoops_yomi_style_tag = '';

    //echo XOOPS_ROOT_PATH."/modules/yomi/style_load.php<br>";

    //require_once (XOOPS_ROOT_PATH."/modules/yomi/style_load.php");

    $td_style = 'width:' . (int)(100 / $cols) . '%;';

    $td_style = " style=\"$td_style\"";

    $ret = $xoops_yomi_style_tag;

    $ret .= "<table style='width:100%'><tr>";

    $col = 0;

    if (is_array($result)) {
        foreach ($result as $Slog) {
            $col++;

            $ret .= "<td$td_style class='xoops_yomi_block_td'>";

            $ret .= b_yomi_show_items($Slog, $title_m, $message_m, $w_m, $h_m);

            $ret .= '</td>';

            if ($col >= $cols) {
                $col = 0;

                $ret .= "</tr>\n<tr>";
            }
        }
    } else {
        while (false !== ($Slog = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $col++;

            $ret .= "<td$td_style class='xoops_yomi_block_td'>";

            $ret .= b_yomi_show_items($Slog, $title_m, $message_m, $w_m, $h_m);

            $ret .= '</td>';

            if ($col >= $cols) {
                $col = 0;

                $ret .= "</tr>\n<tr>";
            }
        }
    }

    $ret = preg_replace("/<\/tr>\n<tr>$/", '', $ret);

    // マスが足らない時に足らない分を補わない時は以下一行をコメントアウトする。

    if ($cols - $col && $col > 0) {
        $ret .= str_repeat("<td$td_style class='xoops_yomi_block_td'></td>", $cols - $col);
    }

    $ret .= '</tr></table>';

    return b_yomi_load_css() . $ret;
    //return $ret;
}

// URLから画像キャッシュURIを返す
function b_yomi_get_img_cache($url, $id)
{
    // URLチェック

    if (!preg_match("#^https?://.+\.(jpe?g|png|gif)$#i", $url)) {
        return ['', false];
    }

    // 画像ディレクトリ パーミッション 666

    $dir = XOOPS_ROOT_PATH . '/modules/yomi/blocks/logos/';

    $imgurl = XOOPS_URL . '/modules/yomi/blocks/logos/';

    // キャッシュ時間(h)

    $cache_h = 24;

    $ext = preg_replace("/^.*(\.[^.]+)/", '$1', $url);

    $filename = $dir . $id . $ext;

    $imgurl .= $id . $ext;

    if (file_exists($filename) && filemtime($filename) + $cache_h * 3600 > time()) {
        return [$imgurl, getimagesize($filename)];
    }

    // 指定ファイルをキャッシュする

    $d = new Hyp_HTTP_Request();

    $d->url = $url;

    $d->connect_timeout = 3;

    $d->read_timeout = 2;

    $d->get();

    if (200 !== $d->rc || !$d->data) {
        return [$url, false];
    }

    if ($fp = fopen($filename, 'wb')) {
        fwrite($fp, $d->data);

        fclose($fp);
    }

    return [$imgurl, getimagesize($filename)];
}

// スタイルシートを読み込む
function b_yomi_load_css()
{
    static $load = false;

    $ret = '';

    if (!$load) {
        $ret = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/yomi/style.css" type="text/css" media="screen" charset="shift_jis">' . "\n";
    }

    return $ret;
}
