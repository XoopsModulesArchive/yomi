<?php
// Yomi-Serch[XOOPS版] 総登録件数出力スクリプト
//              by nao-pon (http://hypweb.net/)
//
// 使用方法
// HTMLからJavaScriptとして呼び出します。
//
// 使用例
// 総登録:<script language="JavaScript" src="http://XOOPSのルート/modules/yomi/count.js.php"></script>サイト

require dirname(__DIR__) . '/cfg.php';

$db = mysql_connect($EST['host'], $EST['sqlid'], $EST['sqlpass']);
mysqli_select_db($GLOBALS['xoopsDB']->conn, $EST['sqldb'], $db);
$query = 'SELECT COUNT(*) FROM ' . $EST['sqltb'] . 'log';
//echo $query;
$result = $GLOBALS['xoopsDB']->queryF($query) || die("Query failed $query");
[$count] = $GLOBALS['xoopsDB']->fetchRow($result); #総登録数

echo "document.open();
document.write(\"{$count}\");
document.close();";

$GLOBALS['xoopsDB']->close($db);
