<?php
/*
☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
☆                                                                         ☆
☆  源 码：caozha-pinyin                                                    ☆
☆  日 期：2020-05-08                                                       ☆
☆  开 发：草札(www.caozha.com)                                              ☆
☆  鸣 谢：穷店(www.qiongdian.com) 品络(www.pinluo.com)                       ☆
☆  Git仓库: https://gitee.com/caozha/caozha-pinyin                         ☆
☆  Copyright ©2020 www.caozha.com All Rights Reserved.                    ☆
☆                                                                         ☆
☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆☆
*/

class caozha_pinyin {

	protected $dict;

	function __construct() {

	}

	function __destruct() {

	}


	/*
	函数：convert
	$word：待转换的字
	$separator：转换后的字之间分隔符，默认空格
	$format：输出格式，可选值：js、json、jsonp、text、xml
	$callback：jsonp的回调函数
	$dict_data：拼音字典数据源，tone为带声调输出，latin为不带声调
	$u_tone：ü的替代字符，可以为：v，u等
	$punctuation：1为保留标点符号并转为英文标点，2为去除标点符号
	*/
	function convert( $word, $format = "text", $dict_data = "tone", $u_tone = "ü", $punctuation = 1, $separator = " ", $callback = "" ) {
		if ( !$word ) {
			return $this->out_format( array( "word" => "" ), $format, $callback );
		}

		if ( $dict_data == "tone" ) {
			$this->dict = file_get_contents( "dict/dict.tone.php", 1 );
		} else {
			$this->dict = file_get_contents( "dict/dict.latin.php", 1 );
		}


		if ( $punctuation == 2 ) {
			// Filter 英文标点符号
			$word = preg_replace( "/[[:punct:]]/i", "", $word );
			// Filter 中文标点符号
			mb_regex_encoding( 'UTF-8' );
			$char = "，。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";
			$word = mb_ereg_replace( "[" . $char . "]", "", $word, "UTF-8" ); //mb_ereg_replace用于中文字符替换，正则的时候不需要加/ /
			// Filter 连续空格
			//$word = preg_replace( "/\s+/", " ", $word );
		}

		$str = "";

		for ( $i = 0; $i < iconv_strlen( $word ); $i++ ) {

			$word_current = mb_substr( $word, $i, 1, "UTF-8" );

			if ( mb_strpos( $this->dict, $word_current ) !== FALSE ) { //如存在
				$str_arr = explode( $word_current, $this->dict );
				$str_arr = explode( "|", $str_arr[ 1 ] );
				$str .= $str_arr[ 0 ] . $separator;
			} else {
				$str .= $word_current . $separator;
			}
		}

		unset( $this->dict );

		$str = trim( mb_substr( $str, 0, iconv_strlen( $str ) - iconv_strlen( $separator ) ) ); //删除最后一个分隔符

		if ( $u_tone != "ü" ) {
			$str = str_replace( "ü", $u_tone, $str );
		}

		$out_arr = array( "word" => $str );

		return $this->out_format( $out_arr, $format, $callback );

	}


	function out_format( $list, $format, $callback ) { //按格式输出数据
		switch ( $format ) {
			case "js":
				return "var word = " . json_encode( $list ) . ";";
				break;
			case "json":
				return json_encode( $list );
				break;
			case "jsonp":
				return $callback . "(" . json_encode( $list ) . ");";
				break;
			case "text":
				return $list[ "word" ];
				break;
			case "xml":
				return $this->arrayToXml( $list );
				break;
			default:
				return json_encode( $list );
		}
	}


	function arrayToXml( $arr ) { //数组转XML
		$xml = "<root>";
		foreach ( $arr as $key => $val ) {
			if ( is_array( $val ) ) {
				$xml .= "<" . $key . ">" . $this->arrayToXml( $val ) . "</" . $key . ">";
			} else {
				$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
			}
		}
		$xml .= "</root>";
		return $xml;
	}


}