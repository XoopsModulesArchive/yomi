<?php

global $xoopsConfig;
if (!$xoopsConfig['dbhost']) {
    $xoopsConfig['dbhost'] = XOOPS_DB_HOST;
}
if (!$xoopsConfig['dbuname']) {
    $xoopsConfig['dbuname'] = XOOPS_DB_USER;
}
if (!$xoopsConfig['dbname']) {
    $xoopsConfig['dbname'] = XOOPS_DB_NAME;
}
if (!$xoopsConfig['dbpass']) {
    $xoopsConfig['dbpass'] = XOOPS_DB_PASS;
}
if (!$xoopsConfig['prefix']) {
    $xoopsConfig['prefix'] = XOOPS_DB_PREFIX;
}
#一般の共通設定
$EST = [
    'host' => $xoopsConfig['dbhost'], #MySQLのホスト名
    'sqlid' => $xoopsConfig['dbuname'], #MySQLのユーザ名
    'sqldb' => $xoopsConfig['dbname'], #MySQLのデータベース名
    'sqlpass' => $xoopsConfig['dbpass'], #MySQLのパスワード
    'sqltb' => $xoopsConfig['prefix'] . '_yomi_', #テーブルのプレフィックス
    'pass' => '', #管理用パスワード(WEB上で設定後は暗号化されます)
    'home' => XOOPS_URL . '/modules/yomi/', #サーチエンジンのトップへのパスorURL
    'script' => 'index.php', #yomi.phpのファイル名
    'search' => 'search.php', #検索用のPHPファイル名
    'rank' => 'rank.php', #ランキング用のPHPファイル名
    'admin' => 'admin.php', #管理処理用のPHPファイル名

    'login_ip' => '', #管理メニューを実行できるIPアドレスの設定リスト

    'top' => '0', #サーチエンジンのトップ(CGI=0/HTML=1)

    ##ユーザ権限設定
    'user_change_kt' => '0', #カテゴリ変更(可能=0/不可=1)
    'user_check' => '1', #仮登録モードに(しない=0/する=1)

    'hyouji' => '10', #カテゴリの表示数

    'log_path' => 'log/', #ログディレクトリのパス(URLは不可)
    'temp_path' => 'template/', #テンプレートディレクトリのパス(URLは不可)
    'html_path' => 'html/', #HTMLファイル用ディレクトリのパス(URLは不可)

    'html_path_url' => 'html/', #HTMLファイルへのURL

    'cgi_path_url' => XOOPS_URL . '/modules/yomi/', #PHPファイルを置くディレクトリのURL

    'img_path_url' => 'img/', #画像ファイルを置くディレクトリのURL

    'temp_logfile' => 'ys4_temp.cgi', #仮登録用ファイル

    'search_name' => 'Yomi-Search [ XOOPS ]', #サーチエンジンの名称
    'admin_name' => 'Web Master', #管理人の名前
    'admin_email' => $xoopsConfig['adminmail'], #管理人のE-Mail
    'admin_hp' => XOOPS_URL . '/', #管理人のホームページのURL

    'new_time' => '14', #新着・更新期間の日数

    ##名称設定
    'name_new' => '新着サイト',
    'name_renew' => '更新サイト',
    'name_m1' => 'おすすめサイト',
    'name_m2' => '相互リンクサイト',
    'name_rank' => '人気ランキング',
    'name_rank_bf' => '前回のランキング',
    'name_rank_rui' => '累計人気ランキング',
    'name_rev' => 'アクセスランキング',
    'name_rev_bf' => '前回のアクセスランキング',
    'name_rev_rui' => '累計アクセスランキング',

    ##メールの設定
    'mail_to_admin' => '1', #管理人にメールを送信(しない=0/する=1)
    'mail_to_register' => '1', #登録者にメールを送信(しない=0/する=1)
    'mail_new' => '1', #新規登録完了メールを送信(しない=0/する=1)
    'mail_ch' => '1', #登録内容変更完了メールを送信(しない=0/する=1)
    'mail_temp' => '1', #仮登録完了メールを送信(しない=0/する=1)
    'mail_pass' => '1', #パスワード変更メールを送信(しない=0/する=1)

    're_pass_fl' => '0', #パスワード再発行を(しない=0/する=1)

    'syoukai_br' => '1', #紹介文の改行(無効=0/有効=1)

    #標準のログ表示順(mark/id_new/id_old/time_new/time_old/ac_new/ac_old)
    'defo_hyouji' => 'mark',

    ##ジャンプ処理
    'location' => '1', #(Locationを使う=1/メタタグを使う=0)

    ##キーワードランキングの設定
    'keyrank' => '1', #(実施しない=0/実施する=1)
    'keyrank_min' => '1', #管理室で表示する最低数
    'keyrank_kikan' => '14', #集計期間(日数)
    'keyrank_hyouji' => '30', #表示数
    'keyrank_cut' => '1', #一日ごとに指定数以下のデータは削除

    #人気(OUT)ランキングの設定
    'rank_fl' => '1', #(実施しない=0/実施する=1)
    'rank_min' => '1', #ランクインさせる最低アクセス数
    'rank_kikan' => '14', #集計期間(日数)
    'rank_time' => '6', #ランキング更新頻度(時間)
    'rank_best' => '100', #ランキングデータの最大保持件数
    'rank_ref' => '', #集計対象のURL(rank.phpやyomi.phpを置くディレクトリのURL)の一部(指定しない場合は未記入)

    #アクセス(IN)ランキングの設定
    'rev_fl' => '1', #(実施しない=0/実施する=1)
    'rev_min' => '1', #ランクインさせる最低アクセス数
    'rev_kikan' => '14', #集計期間(日数)
    'rev_best' => '100', #ランキングデータの最大保持件数
    'rev_url' => XOOPS_URL . '/modules/yomi/', #アクセスランキング時のリンクジャンプ先URL

    ##管理人への通知フォームの設定
    'no_link_min' => '0', #報告する最低値
    'no_link_ip' => '', #通知を拒否するIPアドレス(の一部)リスト

    ##アクセスカウンタを(使用しない=0/使用する=1)
    'count' => '0',
];
function EST_reg()
{
    #登録処理関係の設定(regist_ys.php)

    #記入必須はFxxx=☆/制限文字数はMxxx=文字数

    #カテゴリ選択上限=kt_max/選択下限=kt_min/二重URL登録=nijyu_url(可能=0/不可=1)

    #禁止ワード=kt_no_word/管理人のみが登録できるモード=no_regist(ON=1/OFF=0)

    #管理人のみが修正・削除できるモード=no_mente(ON=1/OFF=0)

    #バナーURL登録項目=bana_url(ON=1/OFF=0)

    #追加希望カテゴリ項目=add_kt(ON=1/OFF=0)/管理人へのコメント項目=to_admin(ON=1/OFF=0)

    #新規登録時の相互リンク連絡項目=sougo(ON=1/OFF=0)

    #登録者のメッセージ=look_mes(見る=1/見ない=0)

    global $EST_reg;

    $EST_reg = [
        'Fadd_kt' => '',
        'Fbana_url' => '',
        'Femail' => '☆',
        'Fkey' => '',
        'Fname' => '☆',
        'Fsyoukai' => '☆',
        'Ftitle' => '☆',
        'Fto_admin' => '',
        'Furl' => '☆',
        'Madd_kt' => '50',
        'Mbana_h' => '31',
        'Mbana_url' => '130',
        'Mbana_w' => '88',
        'Memail' => '40',
        'Mkey' => '30',
        'Mname' => '25',
        'Msyoukai' => '100',
        'Mtitle' => '25',
        'Mto_admin' => '200',
        'Murl' => '130',
        'add_kt' => '1',
        'bana_url' => '1',
        'kt_max' => '2',
        'kt_min' => '1',
        'kt_no_word' => '',
        'look_mes' => '30m',
        'nijyu_url' => '1',
        'no_mente' => '0',
        'no_regist' => '0',
        'sougo' => '0',
        'to_admin' => '1',
    ];
}

$ganes = [
    '01' => 'ホームページ作成',
    '01_01' => 'CGI',
    '01_01_01' => 'CGIデータベース',
    '01_01_02' => 'CGIの解説',
    '01_03' => 'HTML',
    '01_04' => 'JavaScript',
    '01_05' => 'アクセス向上',
    '01_05_01' => '宣伝できるサイト',
    '01_06' => 'フリー素材',
    '01_07' => 'ホームページ作成関連リンク集',
    '02' => 'サーチエンジン',
    '02_01' => '総合',
    '02_02' => 'Yomi-Search系',
    '02_03' => 'エンターテイメント',
    '02_04' => '音楽',
    '02_05' => 'ゲーム',
    '02_06' => 'ショッピング',
    '02_07' => '辞書・地図',
    '02_08' => '地域情報',
    '02_09' => 'その他',
    '03' => '無料サービス',
    '03_01' => 'アクセスカウンタ',
    '03_02' => '掲示板',
    '03_03' => 'チャット',
    '03_04' => 'プロバイダ',
    '03_05' => 'ホームページ',
    '03_05_01' => 'ホームページ(有料)',
    '03_06' => 'メール',
    '03_07' => 'その他の無料サービス',
    '04' => 'コンピュータ',
    '04_01' => 'ソフトウェア',
    '04_01_01' => 'XOOPS',
    '04_01_02' => 'サーバー',
    '04_02' => 'ハードウェア',
    '04_02_01' => 'Macintosh',
    '04_02_02' => 'Windows',
    '04_02_03' => 'その他',
    '05' => 'ライフスタイル',
    '05_01' => '占い',
    '05_02' => 'グルメ',
    '05_02_01' => 'レストラン',
    '05_03' => '掲示板',
    '05_04' => '懸賞',
    '05_05' => '個人',
    '05_06' => 'チャット',
    '05_07' => '出会い',
    '05_08' => 'その他',
    '06' => 'エンターテイメント',
    '06_01' => 'アート・ＣＧ',
    '06_02' => 'アニメ',
    '06_03' => '映画',
    '06_04' => 'オンライン小説',
    '06_05' => 'ギャンブル',
    '06_05_01' => '競馬',
    '06_06' => '芸能人・タレント',
    '06_07' => 'ユーモア',
    '06_08' => 'その他',
    '07' => 'ゲーム',
    '07_01' => '総合情報',
    '07_02' => 'TVゲーム',
    '07_03' => 'アーケードゲーム',
    '07_04' => 'オンラインゲーム',
    '07_05' => 'パソコンゲーム',
    '07_06' => 'その他',
    '08' => '音楽',
    '08_01' => '総合情報',
    '08_02' => 'MIDI',
    '08_03' => 'アーティスト',
    '08_04' => '音楽配信',
    '08_05' => 'その他',
    '09' => 'スポーツ',
    '09_01' => '総合情報',
    '09_02' => 'サッカー',
    '09_03' => '野球',
    '09_04' => 'その他',
    '09_05' => 'フィットネス',
    '09_06' => '水泳',
    '10' => 'ショッピング',
    '10_01' => 'ショッピングモール',
    '10_02' => 'オークション',
    '10_03' => '家電製品',
    '10_04' => '雑貨',
    '10_05' => 'フード・ドリンク',
    '10_06' => 'ファッション',
    '10_07' => 'フリーマーケット',
    '10_08' => 'ホビー',
    '10_09' => 'その他',
    '10_10' => '本・CD・ビデオ',
    '11' => 'ニュース・マスコミ',
    '11_01' => 'TV',
    '11_02' => 'インターネット',
    '11_03' => 'ラジオ',
    '11_04' => 'その他',
    '12' => '健康・医療',
    '12_01' => '総合情報',
    '12_02' => '治療法',
    '12_03' => 'メンタルヘルス',
    '12_04' => 'その他',
    '13' => '旅行・地域情報',
    '13_01' => '海外',
    '13_02' => '北海道',
    '13_03' => '東北',
    '13_04' => '信越',
    '13_05' => '関東',
    '13_06' => '東海',
    '13_07' => '北陸',
    '13_08' => '近畿',
    '13_09' => '中国',
    '13_10' => '四国',
    '13_11' => '九州',
    '13_12' => '沖縄',
    '14' => '教育',
    '14_01' => '総合情報',
    '14_02' => '学校',
    '14_03' => '資格',
    '14_04' => 'その他',
];

function gane_st()
{
    ##カテゴリ属性

    #トップページに表示する(サブカテゴリの場合のみ)

    global $gane_top, $gane_ref, $gane_UR, $gane_other;

    $gane_top = [
        '01_01' => '1',
        '01_01_01' => '',
        '01_01_02' => '',
        '01_03' => '',
        '01_04' => '',
        '01_05' => '1',
        '01_05_01' => '',
        '01_06' => '1',
        '01_07' => '',
        '02_01' => '1',
        '02_02' => '',
        '02_03' => '',
        '02_04' => '',
        '02_05' => '',
        '02_06' => '',
        '02_07' => '',
        '02_08' => '1',
        '02_09' => '1',
        '03_01' => '',
        '03_02' => '1',
        '03_03' => '',
        '03_04' => '',
        '03_05' => '1',
        '03_05_01' => '',
        '03_06' => '1',
        '03_07' => '',
        '04_01' => '1',
        '04_02' => '',
        '04_02_01' => '1',
        '04_02_02' => '1',
        '04_02_03' => '',
        '05_01' => '1',
        '05_02' => '',
        '05_02_01' => '',
        '05_03' => '',
        '05_04' => '1',
        '05_05' => '',
        '05_06' => '',
        '05_07' => '1',
        '05_08' => '',
        '06_01' => '',
        '06_02' => '',
        '06_03' => '1',
        '06_04' => '',
        '06_05' => '',
        '06_05_01' => '',
        '06_06' => '1',
        '06_07' => '1',
        '06_08' => '',
        '07_01' => '1',
        '07_02' => '1',
        '07_03' => '',
        '07_04' => '',
        '07_05' => '1',
        '07_06' => '',
        '08_01' => '1',
        '08_02' => '',
        '08_03' => '1',
        '08_04' => '1',
        '08_05' => '',
        '09_01' => '1',
        '09_02' => '1',
        '09_03' => '1',
        '09_04' => '',
        '10_01' => '1',
        '10_02' => '1',
        '10_03' => '',
        '10_04' => '',
        '10_05' => '',
        '10_06' => '',
        '10_07' => '1',
        '10_08' => '',
        '10_09' => '',
        '10_10' => '',
        '11_01' => '1',
        '11_02' => '1',
        '11_03' => '1',
        '11_04' => '',
        '12_01' => '1',
        '12_02' => '1',
        '12_03' => '1',
        '12_04' => '',
        '13_01' => '1',
        '13_02' => '1',
        '13_03' => '1',
        '13_04' => '',
        '13_05' => '',
        '13_06' => '',
        '13_07' => '',
        '13_08' => '',
        '13_09' => '',
        '13_10' => '',
        '13_11' => '',
        '13_12' => '',
        '14_01' => '1',
        '14_02' => '1',
        '14_03' => '1',
        '14_04' => '',
        '04_01_01' => '1',
    ];

    #関連カテゴリ設定

    #([例]'Aのカテゴリ番号'=>'Bのカテゴリ番号&Cのカテゴリ番号')

    #の場合にはAのカテゴリを表示した際にBとCのカテゴリが関連カテゴリとして表示される

    $gane_ref = [
        '01' => '03&03_01',
        '01_01' => '',
        '01_01_01' => '',
        '01_01_02' => '',
        '01_03' => '',
        '01_04' => '',
        '01_05' => '',
        '01_05_01' => '',
        '01_06' => '',
        '01_07' => '',
        '02_01' => '',
        '02_02' => '',
        '02_03' => '',
        '02_04' => '',
        '02_05' => '',
        '02_06' => '',
        '02_07' => '',
        '02_08' => '',
        '02_09' => '',
        '03_01' => '',
        '03_02' => '',
        '03_03' => '',
        '03_04' => '',
        '03_05' => '',
        '03_05_01' => '',
        '03_06' => '',
        '03_07' => '',
        '04_01' => '',
        '04_02' => '',
        '04_02_01' => '',
        '04_02_02' => '',
        '04_02_03' => '',
        '05_01' => '',
        '05_02' => '',
        '05_02_01' => '',
        '05_03' => '',
        '05_04' => '',
        '05_05' => '',
        '05_06' => '',
        '05_07' => '',
        '05_08' => '',
        '06_01' => '',
        '06_02' => '',
        '06_03' => '',
        '06_04' => '',
        '06_05' => '',
        '06_05_01' => '',
        '06_06' => '',
        '06_07' => '',
        '06_08' => '',
        '07_01' => '',
        '07_02' => '',
        '07_03' => '',
        '07_04' => '',
        '07_05' => '',
        '07_06' => '',
        '08' => '10_04&02_03',
        '08_01' => '',
        '08_02' => '',
        '08_03' => '',
        '08_04' => '',
        '08_05' => '',
        '09_01' => '',
        '09_02' => '',
        '09_03' => '',
        '09_04' => '',
        '10_01' => '',
        '10_02' => '',
        '10_03' => '',
        '10_04' => '',
        '10_05' => '',
        '10_06' => '',
        '10_07' => '',
        '10_08' => '',
        '10_09' => '',
        '10_10' => '',
        '11_01' => '',
        '11_02' => '',
        '11_03' => '',
        '11_04' => '',
        '12_01' => '',
        '12_02' => '',
        '12_03' => '',
        '12_04' => '',
        '13_01' => '',
        '13_02' => '',
        '13_03' => '',
        '13_04' => '',
        '13_05' => '',
        '13_06' => '',
        '13_07' => '',
        '13_08' => '',
        '13_09' => '',
        '13_10' => '',
        '13_11' => '',
        '13_12' => '',
        '14_01' => '',
        '14_02' => '',
        '14_03' => '',
        '14_04' => '',
    ];

    #訪問者が登録不可のカテゴリ

    $gane_UR = [
        '01_01' => '',
        '01_01_01' => '',
        '01_01_02' => '',
        '01_03' => '',
        '01_04' => '',
        '01_05' => '',
        '01_05_01' => '',
        '01_06' => '',
        '01_07' => '',
        '02_01' => '',
        '02_02' => '',
        '02_03' => '',
        '02_04' => '',
        '02_05' => '',
        '02_06' => '',
        '02_07' => '',
        '02_08' => '',
        '02_09' => '',
        '03' => '1',
        '03_01' => '',
        '03_02' => '',
        '03_03' => '',
        '03_04' => '',
        '03_05' => '',
        '03_05_01' => '',
        '03_06' => '',
        '03_07' => '',
        '04' => '1',
        '04_01' => '',
        '04_02' => '',
        '04_02_01' => '',
        '04_02_02' => '',
        '04_02_03' => '',
        '05_01' => '',
        '05_02' => '',
        '05_02_01' => '',
        '05_03' => '',
        '05_04' => '',
        '05_05' => '',
        '05_06' => '',
        '05_07' => '',
        '05_08' => '',
        '06_01' => '',
        '06_02' => '',
        '06_03' => '',
        '06_04' => '',
        '06_05' => '',
        '06_05_01' => '',
        '06_06' => '',
        '06_07' => '',
        '06_08' => '',
        '07_01' => '',
        '07_02' => '',
        '07_03' => '',
        '07_04' => '',
        '07_05' => '',
        '07_06' => '',
        '08_01' => '',
        '08_02' => '',
        '08_03' => '',
        '08_04' => '',
        '08_05' => '',
        '09_01' => '',
        '09_02' => '',
        '09_03' => '',
        '09_04' => '',
        '10' => '1',
        '10_01' => '',
        '10_02' => '',
        '10_03' => '',
        '10_04' => '',
        '10_05' => '',
        '10_06' => '',
        '10_07' => '',
        '10_08' => '',
        '10_09' => '',
        '10_10' => '',
        '11_01' => '',
        '11_02' => '',
        '11_03' => '',
        '11_04' => '',
        '12' => '1',
        '12_01' => '',
        '12_02' => '',
        '12_03' => '',
        '12_04' => '',
        '13_01' => '',
        '13_02' => '',
        '13_03' => '',
        '13_04' => '',
        '13_05' => '',
        '13_06' => '',
        '13_07' => '',
        '13_08' => '',
        '13_09' => '',
        '13_10' => '',
        '13_11' => '',
        '13_12' => '',
        '14_01' => '',
        '14_02' => '',
        '14_03' => '',
        '14_04' => '',
    ];

    #その他のカテゴリに表示するカテゴリ

    $gane_other = [
        '01',
        '02',
        '03',
        '04',
        '05',
        '06',
        '07',
        '08',
        '10',
        '11',
        '12',
        '13',
        '14',
    ];
}

function gane_guide()
{
    ##カテゴリ別の説明表示(「★」は必要なければ削除しても構いません)

    global $KTEX;

    $KTEX = [
        '01' => '★ホームページ作成に関するサイト',
        '01_01' => '★CGIを配布しているサイト',
        '02' => '★サーチエンジンに関するサイト',
        'new_ys' => '★14日以内に登録されたサイト',
        'rank' => '★人気ランキングをベスト100位まで紹介しています',
        'rank_bf' => '★前回の人気ランキングをベスト100位まで紹介しています',
        'rank_rui' => '★累計人気ランキングをベスト100位まで紹介しています',
        'renew_ys' => '★14日以内に更新されたサイト',
        'rev' => '★アクセスランキングをベスト100位まで紹介しています',
        'rev_bf' => '★前回のアクセスランキングをベスト100位まで紹介しています',
        'rev_rui' => '★累計アクセスランキングをベスト100位まで紹介しています',
    ];
}

##検索フォームの設定
function search_form()
{
    global $EST; ?>
<option value="pre" selected><?= $EST['search_name'] ?>で
<option value="yahoo">Yahoo!で
<option value="google">Googleで
<option value="infoseek">Infoseekで
<option value="goo">gooで
<option value="lycos">Lycosで
<option value="inetguide">iNET Guideで
<option value="excite">Exciteで
<option value="joy">J.O.Y.で
<option value="csj">CSJ What's Best!で
<option value="FRESHEYE">フレッシュアイで
<option value="InfoNavigator">InfoNavigatorで
<option value="">-----------------
<option value="chance">Chance It!（懸賞）で
<option value="findx">Find'X（ＰＣ）で
<option value="vector">Vector（ソフトウェア）で
<option value="yomimono">よみものさーち（メルマガ）で
<option value="hihing">HiHing（競馬）で
<option value="ys-link">YS-Link（検索エンジン）で
<option value="">-----------------
<option value="yahoo_s">Yahoo!ショッピングで
<option value="rakuten">楽天市場で
<option value="hmv_a">HMV(アーティスト名検索)で
<option value="hmv_t">HMV(タイトル名検索)で
<option value="bk1">bk1で
<option value="bk1_i">bk1(ISBN検索)で
<option value="amazon_i">amazon.co.jp(ISBN検索)で
<option value="">-----------------
<option value="com">.com で
<option value="cojp">.co.jp で
    <?php
} #end of &search_form
    ##メニューバーの設定
    function menu_bar()
    {
        global $EST; ?><a href="<?= $EST[cgi_path_url] . $EST[script] ?>?mode=new">新着サイト</a> -
        <a href="<?= $EST[cgi_path_url] . $EST[script] ?>?mode=renew">更新サイト</a> -
        <a href="<?= $EST[cgi_path_url] ?>rank.php">人気ランキング</a> -
        <a href="<?= $EST[cgi_path_url] ?>rank.php?mode=keyrank">キーワードランキング</a> -
        <a href="<?= $EST[cgi_path_url] . $EST[script] ?>?mode=m1">おすすめサイト</a> -
        <a href="<?= $EST[cgi_path_url] . $EST[script] ?>?mode=m2">相互リンクサイト</a>
        <?php
    } #end of &menu_bar
    ##ヘッダスペース
    function head_sp()
    {
        ?>

        <?php
    } #end of &head_sp
    ##フッタスペース
    function foot_sp()
    {
        ?>

        <?php
    } #end of &foot_sp
    ?>
