<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>caozha-pinyin：使用演示</title>
<?php
include_once("../src/caozha_pinyin.class.php");
$caozha=new caozha_pinyin();
$word="嗨，你好！我是草札，我有一只小毛驴。";
?>
</head>
<body>
<b>您转换的语句是：</b><br><br><?=$word?><br><br><b>返回的拼音数据如下：</b><br><br>
	<b>json：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"json"));
?><br><br>
	<b>js：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"js"));
?><br><br>
	<b>jsonp：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"jsonp"));
?><br><br>
	<b>xml：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"xml"));
?><br><br>
	<b>text：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"text"));
?><br><br>
	<b>不带声调：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"text","latin"));
?><br><br>
	<b>不带声调，把ü替换为v：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"text","latin","v"));
?><br><br>
	<b>不带声调，把ü替换为v，不带标点符号：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"text","latin","v",2));
?><br><br>
	<b>不带声调，把ü替换为v，不带标点符号，用-连接符（可任意符号）：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"text","latin","v",2,"-"));
?><br><br>
	<b>不带声调，把ü替换为v，不带标点符号，不用连接符：</b><br>
<?php
	echo htmlspecialchars($caozha->convert($word,"text","latin","v",2,""));
?><br><br>
</body>
</html>