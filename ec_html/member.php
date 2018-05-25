<?php
	session_start();

	// データベースに接続する処理。
	// 環境に応じて以下の変数を書き換えます。
	$host = "localhost";	// 接続先ホスト名
	$user = "root";			// 接続ユーザ名
	$pass = "";				// 接続パスワード
	$dbname = "ec";			// データベース名
	if( !$conn = mysql_connect( $host, $user, $pass ) ) 
	{
		die("MySQL 接続エラー");
	}
	mysql_select_db( $dbname );
	mysql_set_charset("utf8");		// 文字コードを指定します。

	/**-----------------------------------------------------------
	 *
	 * 会員登録画面で「更新」ボタンがクリックされた時の処理。
	 * ログイン状態に応じて、UPDATE または INSERT を実行する。
	 *
	 ------------------------------------------------------------*/
	if( $_REQUEST["cmd"] == "regist_member" )
	{
		if( $_SESSION["customer_code"] != "" )
		{
			$sql  = " update m_customers set ";
$sql .= " customer_code = '" . $_REQUEST["customer_code"] . "',";
$sql .= " pass = '" . $_REQUEST["pass"] . "',";
$sql .= " name = '" . $_REQUEST["name"] . "',";
$sql .= " address = '" . $_REQUEST["address"] . "',";
$sql .= " tel = '" . $_REQUEST["tel"] . "',";
$sql .= " mail = '" . $_REQUEST["mail"] . "'";
$sql .= " where customer_code =  '" . $_SESSION["customer_code"] . "'";
$res = mysql_query( $sql );
		}
		else
		{
$sql = "insert into m_customers( customer_code, pass, name, address, tel, mail, del_flag, reg_date ) ";
	$sql .= "values( ";
	$sql .= " '".$_REQUEST["customer_code"]."', ";
	$sql .= " '".$_REQUEST["pass"]."', ";
	$sql .= " '".$_REQUEST["name"]."', ";
	$sql .= " '".$_REQUEST["address"]."', ";
	$sql .= " '".$_REQUEST["tel"]."', ";
	$sql .= " '".$_REQUEST["mail"]."', ";
	$sql .= " '0', ";
	$sql .= " now() ) ";
	$res = mysql_query( $sql );
		}
	}

	// ログイン済であれば、お客様の情報をデータベースより取得。
	if( $_SESSION["customer_code"] != "" )
	{
		$sql = " select * from m_customers ";
		$sql.= " where customer_code= '" . $_SESSION["customer_code"] . "'";
		$res = mysql_query( $sql );
		$count = 0;
		$info = mysql_fetch_array( $res ) ;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>登録情報｜楽器の通販サイト  oh yeah !!</title>
<link href="common/css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="common/js/base.js"></script>
</head>
<body onload="MM_preloadImages('common/img/bt1_f2.gif','common/img/bt2_f2.gif','common/img/bt3_f2.gif','common/img/bt3_2_f2.gif','common/img/bt_login_f2.gif')">
<div id="wrap">
  <div id="contents">
    <!-- 右コンテンツ -->
    <div id="rightbox">
      <div id="main">
        <div id="main2">
          <!-- ↑↑タイトル以外共通部分↑↑ -->

          <!-- メイン部分 各ページごとに作成-->
          <div id="mainbox" class="clearfix">
            <h2>登録情報</h2>

            <form name="member_form" action="member.php" method="post">
            <input type="hidden" name="cmd" value="regist_member"/>
            <div class="info clearfix">
            <dl>
            <dt>ID：</dt>
            <dd><input type="text" name="customer_code" <?php if( $info["customer_code"] != "" ){ print( "readonly" ); } ?> value="<?php print( $info["customer_code"] ); ?>"/></dd>
            <dt>パスワード：</dt>
            <dd><input type="password" name="pass" value="<?php print( $info["pass"] ); ?>"/></dd>
            <dt>氏名：</dt>
            <dd><input type="text" name="name" value="<?php print( $info["name"] ); ?>"/></dd>
            <dt>住所：</dt>
            <dd><input type="text" name="address" value="<?php print( $info["address"] ); ?>"/></dd>
            <dt>電話：</dt>
            <dd><input type="text" name="tel" value="<?php print( $info["tel"] ); ?>"/></dd>
            <dt>アドレス：</dt>
            <dd><input type="text" name="mail" value="<?php print( $info["mail"] ); ?>" size="30"/>
            </dd>
            </dl>
            <input type="submit" class="update" value="登録"/>
            </div>
            </form>
          </div>
          <!-- /メイン部分 各ページごとに作成-->

          <!-- ↓↓共通部分↓↓ -->
          <!-- フッター -->
          <div id="footer">
            <p class="copy">Copyright &copy; 2008 oh yeah !! All Rights Reserved.</p>
          </div>
          <!-- /フッター -->
        </div>
        <!-- /メイン部分 -->
      </div>
    </div>

    <!-- 左メニュー -->
    <div id="leftbox">
      <h1><img src="common/img/title.gif" alt="oh yeah!!" /></h1>
      <div id="menu">

<?php
	// ログインしていない時は、以下の if 文に入ります。
	if( $_SESSION["customer_code"] == "" )
	{
?>
        <!-- ログインフォーム（非ログイン時） -->
        <form name="login_form" action="item_list.php" method="post">
        <input type="hidden" name="cmd" value="do_login"/>
        <div class="box">
          <div class="top"><img src="common/img/t1.gif" alt="ログイン" /></div>
          <dl class="clearfix">
<?php
		// ログインに失敗した時のエラー表示。
		if( $is_login == 0 and $_REQUEST["cmd"] == "do_login" )
		{
			print("ログインに失敗しました。");
		}
?>
            <dt><img src="common/img/t4.gif" alt="ID" /></dt>
            <dd>
              <input name="login_id" type="text" class="text" />
            </dd>
            <dt><img src="common/img/t5.gif" alt="PASS" /></dt>
            <dd>
              <input name="login_pass" type="password" class="text" />
            </dd>
          </dl>
          <div class="bottom">
            <input name="id3" type="submit" value="ログイン" />
          </div>
        </div>
        </form>
<?php 
	// ログイン済の時は、以下の if 文に入ります。
	} else {
?>
        <form name="login_form" action="item_list.php" method="post">
        <input type="hidden" name="cmd" value="do_logout"/>

        <!-- /ログインフォーム -->
        <!-- ウェルカム（ログイン時） -->
        <div class="box">
          <div class="top">ようこそ<span class="person"><?php print($_SESSION["name"])?></span>さん！</div>
          <div class="bottom">
            <input name="id3" type="submit" value="ログアウト" />
          </div>
        </div>
        </form>
<?php
	}
?>
        <!-- /ウェルカム -->
        <!-- 商品検索 -->
        <form name="login_form" action="item_list.php" method="post">
        <input type="hidden" name="cmd" value="do_search"/>
        <div class="box" id="search">
          <div class="top"><img src="common/img/t2.gif" alt="商品検索" /></div>
          <dl class="clearfix">
            <dt><img src="common/img/t6.gif" alt="商品名" width="32" height="18" /></dt>
            <dd>
              <input type="text" name="item_name" class="text" value="<?php print( $_REQUEST["item_name"] );?>"/>
            </dd>
          </dl>
          <dl class="clearfix cat">
            <dt><img src="common/img/t7.gif" alt="カテゴリ" /></dt>
            <dd>
              <input type="checkbox" name="cat_kan" value="1" <?php if( $_REQUEST["cat_kan"] == "1" ){ print( "checked" ); } ?>/>
              管楽器<br />
              <input type="checkbox" name="cat_gen" value="1" <?php if( $_REQUEST["cat_gen"] == "1" ){ print( "checked" ); } ?>/>
              弦楽器<br />
              <input type="checkbox" name="cat_da" value="1" <?php if( $_REQUEST["cat_da"] == "1" ){ print( "checked" ); } ?>/>
              打楽器 </dd>
          </dl>
          <div class="bottom">
            <input name="id3" type="submit" value="検索" />
          </div>
        </div>
        </form>
        <!-- 商品検索 -->
        
        <!-- 共通メニュー -->
        <ul class="menu">
          <li><a href="item_list.php"><img src="common/img/bt1.gif" alt="商品一覧" name="Image1" width="172" height="38" id="Image1" onmouseover="MM_swapImage('Image1','','common/img/bt1_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
          <li><a href="cart.php"><img src="common/img/bt2.gif" alt="カートの中" name="Image2" width="172" height="38" id="Image2" onmouseover="MM_swapImage('Image2','','common/img/bt2_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
<?php
	// ログイン未の場合
	if( $_SESSION["customer_code"] == "")
	{
?>
          <li><a href="member.php"><img src="common/img/bt3_2.gif" alt="会員登録" name="Image4" width="172" height="38" id="Image4" onmouseover="MM_swapImage('Image4','','common/img/bt3_2_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
<?php
	// ログイン済の場合
	} else { 
?>
          <li><a href="member.php"><img src="common/img/bt3.gif" alt="登録情報" name="Image3" width="172" height="38" id="Image3" onmouseover="MM_swapImage('Image3','','common/img/bt3_f2.gif',1)" onmouseout="MM_swapImgRestore()" /></a></li>
<?php
	} 
?>
        </ul>
        <!-- /共通メニュー -->
      </div>
    </div>
    <!-- /左メニュー -->
   

  </div>
</div>
</body>
</html>
