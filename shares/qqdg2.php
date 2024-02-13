<?php
header('content-type:application/json');
/* Start */
// header('content-type: text/html; charset="utf-8";');
// die('跑路了，江湖再见');
// error_reporting(false);//关闭报错 防止MySQL被打自闭api不能运行
header('Access-Control-Allow-Origin: *'); // *代表允许任何网址请求
header('Access-Control-Allow-Methods: POST,GET'); // 允许请求的类型
header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin'); // 设置允许自定义请求头的字段

//require __DIR__ . '/Api_Lock_CC.php';
// Header('Content-type: Application/json');
/*
$host = '127.0.0.1';//Redis ip,一般127.0.0.1即可
$port = 6379;//Redis端口
$auth = '123456789';//Redis密码 如果有就填 没有就空着
$Redis = new Redis();
$Redis->connect($host, $port);
try{
	$Redis->auth($auth);
}catch (\Exception $e){
}
$Redis->select(11);//选择数据库0-15
// 获取访客真实IP
$ip = false;
if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
	$ip = $_SERVER["HTTP_CLIENT_IP"];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	if ($ip) {
		array_unshift($ips, $ip);
		$ip = FALSE;
	}
	for ($i = 0; $i < count($ips); $i++) {
		if (!mb_eregi("^(10|172.16|192.168|0)", $ips[$i])) {
			$ip = $ips[$i];
			break;
		}
	}
}
if (!$ip) {
	$ip = $_SERVER['REMOTE_ADDR'];
}
$ip_array = ["103.41.65.209","43.249.207.211","221.192.179.0","27.221.79.214","221.192.179.246","8.131.94.70","49.235.124.117","43.250.201.1","61.242.130.161","101.206.110.224","27.151.28.66","127.0.0.1"];
$time = (20);//拉黑时间 秒
$Runtime = time(); //获取时间戳
$keyouttime = 1; //key过期时间
if(!in_array($ip, $ip_array))
{
	if(!$Redis->keys($ip.'\+*')){
		$key = $ip .'+'. $Runtime;
	} else {
		$key = $Redis->keys($ip.'\+*')[0];
	}
	if($Redis->ttl($key) == -2)
	{
		$key = $ip .'+'. $Runtime;
	}
	$Redis->expire($key, $keyouttime);
	$qps = $Redis->incr($key); //使用一次加1
	$qpsout = 5; //过期时间内访问次数
	if($qpsout < $qps || $Redis->ttl($ip) > 0 && !in_array($ip, $ip_array))
	{
	// 访问者触发拉黑
		$Redis->setex($ip, $time, $Runtime); //此为一直拉黑直到访问者停下访问 $time 秒
		$Redis->close();
		header('HTTP/1.0 514');
		header('Content-Type:application/json');
		$type = isset($_REQUEST['type']) ? @$_REQUEST['type'] : 'json';
		Switch($type)
		{
			case 'text':
			exit('触发QPS限制，请勿频繁请求本站! 看到此提示请等待'.$time.'秒后再次访问。');
			break;
			default:
			exit(json_encode(array('code' => 514, 'text' => '触发QPS限制，请勿频繁请求本站! 看到此提示请等待'.$time.'秒后再次访问。'), 460));//达到QPS限制，这里的操作可以自行修改
			break;
		}
	}
}
$keys = $Redis->keys('*\+*');//获取所有携带时间戳的key
foreach($keys as $v)
{
	$explode = explode('+', $v); //将key分割为数组
	if(($Runtime - end($explode)) > $keyouttime)
	{
		// 判断非访问者时间大于过期时间
		$Redis->del($v); //删除
	}
}
$Redis->close();
*/

//Curl请求，参数：地址，方法，头，参数
function curl($url, $method, $headers, $params){
	if (is_array($params)) {
		$requestString = http_build_query($params);
	} else {
		$requestString = $params ? : '';
	}
	if (empty($headers)) {
		$headers = array('Content-type: text/json'); 
	} elseif (!is_array($headers)) {
		parse_str($headers,$headers);
	}
	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	// setting the POST FIELD to curl
	switch ($method){  
		case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, 1);break;  
		case "POST": curl_setopt($ch, CURLOPT_POST, 1);
					 curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
		case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
					 curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
		case "DELETE":  curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");   
						curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
	}
	// getting response from server
	$response = curl_exec($ch);
	
	//close the connection
	curl_close($ch);
	
	//return the response
	if (stristr($response, 'HTTP 404') || $response == '') {
		return array('Error' => '请求错误');
	}
	return $response;
} 
class need{
	public static $info = [];
	/*
	* 获取域名
	*/
	public static function getHost()
	{
		return (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : false);
	}
	/*
	* emoji转换为UTF-32编码
	* @parameter $emoji -> String emoji表情
	*/
	public static function emoji2utf($emoji) {
		$hex = bin2hex(mb_convert_encoding($emoji, 'UTF-32', 'UTF-8'));
		return 'u'.substr($hex, 3);
	}
	/*
	* Skey或pskey进行转码
	*/
	public static function GTK($skey) {
		$len = strlen((String)$skey);
		$hash = 5381;
		for ($i = 0; $i < $len; $i++) {
			$hash += ($hash << 5 & 2147483647) + ord($skey[$i]) & 2147483647;
			$hash &= 2147483647;
		}
		return $hash & 2147483647;
	}
	/*
	* json格式化输出
	*/
	public static function json($arr) {
		header('Content-type: application/json; charset=utf-8;');
		return json_encode($arr,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
	/*
	* 输出
	*/
	public static function send($Msg, $Type = 'jsonp') {
		//header('Content-Type:application/json; charset=utf-8;');
		if($Type == 'text') {
			echo $Msg;
			exit();
		}else if($Type == 'location') {
			header('location:'.$Msg);
			exit();
		}else if($Type == 'image') {
			header('Content-type:image/png;image/jpeg;image/gif;');
			//header('Content-type:image/jpeg');
			$curl = New need;
			echo $curl->teacher_curl($Msg);
			exit();
		}else if($Type == 'url') {
			echo $Msg;
			exit();
		}else if($Type == 'tion') {
			echo $Msg;
			exit();
		}else if($Type == 'jsonp') {
			echo stripslashes(json_encode($Msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
			exit();
		}else{
			echo json_encode($Msg,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			exit();
		}
	}
	/*
	* 获取访问者IP
	*/
	public static function userip() {
		$unknown = 'unknown';
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['HTTP_X_FORWARDED_FOR']&&strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],$unknown)) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],$unknown)) {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	/*
	* qqvip的一种gtk加密，但是已经被腾讯弃用了
	*/
	public static function vipgtk($skey) {
		$salt=5381;
		$md5key='tencentQQVIP123443safde&!%^%1282';
		$hash=array();
		$hash[]=$salt<<5;
		for ($i=0; $i<strlen($skey); ++$i) {
			$acode=ord(substr($skey,$i,1));
			$hash[]=($salt<<5)+$acode;
			$salt=$acode;
		}
		$md5str=md5(join('',$hash).$md5key);
		return $md5str;
	}
	/*
	* 判断访问者是不是get
	*/
	public static function get_post() {
		if( $_SERVER['REQUEST_METHOD'] === 'GET') {
			 return true;
		}else{
			return false;
		}
	}
	/*
	* 获取毫秒时间戳
	*/
	public static function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}
	/*
	* 忘了
	*/
	public static function run_encode($msg) {
		$msg = idn_to_ascii($msg,IDNA_NONTRANSITIONAL_TO_ASCII,INTL_IDNA_VARIANT_UTS46);
		return $msg;
	}
	/*
	* 忘了
	*/
	public static function run_decode($msg) {
		$msg = idn_to_utf8($msg);
		return $msg;
	}
	/*
	* 16进制转码，不过没什么用
	*/
	public static function hex_encode($str) {
		$hex="";
		for($i=0;$i<strlen($str);$i++)
			$hex .= '\\u4E'.dechex(ord($str[$i]));
		$hex=$hex;
		return $hex;
	}
	/*
	* 16进制解码，也没什么用
	*/
	public static function hex_decode($hex) {
		$str="";
		for($i=0;$i<strlen($hex)-1;$i+=2)
			$str.=chr(hexdec($hex[$i].$hex[$i+1]));
		return $str;
	}
	/*
	* unicode 解码
	*/
	public static function decodeUnicode($str) {
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
			@create_function(
				'$matches',
				'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
			),
		$str);
	}
	/*
	* unicode转码
	*/
	public static function encodeUnicodes($str) {
		$decode = json_decode('{"text":"'.$str.'"}',true);
		if(!$decode) {
			return $str;
		}else{
			$encode = json_encode($decode);
			preg_match_all('/text":"(.*?)"/',$encode,$encode);
			$encode = str_replace('\\u4e','',$encode[1][0]);
			$encode = str_replace('\\u4E','',$encode);
			return $encode;
		}
	}
	/*
	* 转码加密
	*/
	public static function jiami($string) {
		$str = self::hex_encode($string);
		$str = self::decodeUnicode($str);
		return ($str);
	}
	/*
	* 转码解密
	*/
	public static function jiemi($string) {
		$str = self::encodeUnicodes($string);
		$str = self::hex_decode($str);
		return $str;
	}
	/*
	* 获取时间戳毫秒
	*/
	public static function time_sss() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}
	/*
	* 获取http状态码
	*/
	public static function http($url) {
		$ch = curl_init();
		$timeout = 3;
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_exec($ch);
		return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
	}
	/*
	* 判断是不是qq或者群
	*/
	public static function is_num($num) {
		if(preg_match('/^[1-9][0-9]{4,11}$/', (String)$num)) {
			return true;
		}else{
			return false;
		}
	}
	/*
	* Cookie
	*/
	public static function Robot($dir,$key) {
		return self::cookie($key, true);
	}
	/*
	* Cookie
	*/
	public static function cookie($key,$Value = false) {
		return '获取Cookie的方法';
	}
	/*
	* 没用
	*/
	public static function emoji($text) {
		$array = array(
			'🐶'=>'狗',
			'🐱'=>'猫',
			'🐭'=>'鼠',
			'🐹'=>'仓鼠',
			'🐰'=>'兔',
			'🦊'=>'狐狸',
			'🐻'=>'熊',
			'🐼'=>'熊猫',
			'🐨'=>'考拉',
			'🐯'=>'虎',
			'🦁'=>'狮',
			'🐮'=>'牛',
			'🐷'=>'猪',
			'🐽'=>'猪鼻子',
			'🐸'=>'青蛙',
			'🐵'=>'猴',
			'🐔'=>'鸡',
			'🐕'=>'小狗',
			'🐂'=>'小牛',
			'🐴'=>'马',
			'🐎'=>'小马',
			'🐖'=>'小猪',
			'🦆'=>'鸭',
			'🐥'=>'小鸡',
			'🐓'=>'公鸡',
			'🦅'=>'鹰',
			'🦉'=>'猫头鹰',
			'🦇'=>'蝙蝠',
			'🐺'=>'狼',
			'🐗'=>'野猪',
			'🦄'=>'独角兽',
			'🐝'=>'蜜蜂',
			'🐛'=>'虫',
			'🦋'=>'蝴蝶',
			'🐌'=>'蜗牛',
			'🐉'=>'龙',
			'🐟'=>'鱼',
			'🦐'=>'虾',
			'🦞'=>'龙虾',
			'🌶️'=>'辣椒',
			'🦀'=>'螃蟹',
			'🦈'=>'鲨鱼',
			'🌿'=>'草',
			'🌸'=>'花',
			'🍉'=>'瓜',
			'💦'=>'汗',
			'☀️'=>'太阳',
			'🌤'=>'晴转多云',
			'⛅'=>'阴',
			'🌦️'=>'晴转雨',
			'🌧️'=>'小雨',
			'⛈️'=>'雷阵雨',
			'🌩️'=>'打雷',
			'🌧️'=>'大雨',
			'❄️'=>'雪花',
			'🌨️'=>'雪',
			'🌟'=>'闪光星星',
			'⚡'=>'电',
			'💧'=>'水滴',
			'☔'=>'雨伞',
			'🌈'=>'彩虹',
			'🌊'=>'海浪',
			'🌫️'=>'雾',
			'🌪️'=>'龙卷风',
			'☄️'=>'彗星',
			'🪐'=>'有环行星',
			'⭐'=>'星',
			'✨'=>'闪光',
			'👀'=>'看',
			'🌝'=>'微笑月亮',
			'🌞'=>'微笑太阳',
			'🌚'=>'微笑朔月',
			'🌙'=>'月亮',
			'🌛'=>'微笑上弦月',
			'🌜'=>'微笑下弦月',
			'🌕'=>'满月',
			'🌖'=>'亏凸月',
			'🌗'=>'下弦月',
			'🌘'=>'残月',
			'🌔'=>'盈凸月',
			'🌓'=>'上弦月',
			'🌒'=>'娥眉月',
			'🌑'=>'朔月',
			'🚗'=>'汽车',
			'🚌'=>'公交车',
			'🚞'=>'火车',
			'🚚'=>'货车',
			'✈️'=>'飞机',
			'🚕'=>'出租车',
			'🍜'=>'面',
			'🐦'=>'鸟',
			'🚓'=>'警车',
			'🚢'=>'船',
			'☃️'=>'雪人',
			'㊗️'=>'祝',
			'🈷️'=>'月',
			'👍🏻'=>'赞',
			'🍺'=>'啤酒',
			'🎁'=>'礼物',
			'🎆'=>'烟花',
			'🎉'=>'恭喜',
			'🎄'=>'圣诞',
			'🍎'=>'苹果',
			'🍐'=>'梨',
			'🍌'=>'香蕉',
			'🍇'=>'葡萄',
			'🍓'=>'草莓',
			'🍅'=>'西红柿',
			'🍊'=>'橘子',
			'🥚'=>'蛋',
			'🍚'=>'米饭',
			'🦴'=>'骨',
			'🥁'=>'鼓',
			'📖'=>'书',
			'🌲'=>'树',
			'🍋'=>'柠檬',
			'🍟'=>'薯条',
			'🍔'=>'汉堡',
			'🍠'=>'地瓜',
			'🥩'=>'肉',
			'🌹'=>'玫瑰',
			'❤️'=>'心',
			'🍳'=>'煎蛋',
			'✂️'=>'剪刀',
			'🍙'=>'饭团',
			'🦍'=>'猩猩',
			'❤'=>'心',
			'💩'=>'💩',
			'☂️'=>'伞',
			'💰'=>'钱',
			'💵'=>'美元',
			'👄'=>'嘴',
			'💄'=>'口红',
			'🍼'=>'奶瓶',
			'👍🏻'=>'赞',
			'🦟'=>'蚊子',
			'👻'=>'鬼',
			'🐢'=>'乌龟',
			'🐧'=>'企鹅',
			'🐍'=>'蛇',
			'🈲'=>'禁',
			'🔞'=>'十八禁',
			'🐁'=>'小白鼠',
			'✍🏻'=>'写',
			'👟'=>'鞋',
			'⭕'=>'圈',
			'🛠️'=>'工具',
			'🛣️'=>'公路',
			'🚥'=>'路灯',
			'🌀'=>'飓风',
			'👑'=>'皇冠',
			'🥒'=>'黄瓜',
			'🌼'=>'花',
			'💊'=>'药',
			'👨🏻'=>'男',
			'👩🏻'=>'女',
			'👴🏻'=>'爷',
			'👵🏻'=>'奶',
			'✌🏻'=>'耶',
			'🉐'=>'得',
			'㊙️'=>'秘',
			'👅'=>'舔',
			'🉑'=>'可',
			'🈚'=>'无',
			'💃🏻'=>'舞',
			'😭'=>'哭',
			'🙂'=>'微笑',
			'🧵'=>'线',
			'🤪'=>'滑稽',
			'😆'=>'笑',
			'😓'=>'汗',
			'👌🏻'=>'好',
			'🕰️'=>'钟',
			'🀄'=>'中',
			'🚿'=>'洗',
			'🈶'=>'有',
			'🆙'=>'升',
			'🍑'=>'桃',
			'🍵'=>'茶',
			'🍬'=>'糖',
			'🍭'=>'糖',
			'🈯'=>'指',
			'🌰'=>'栗子',
			'😁'=>'嘻',
			'😃'=>'哈',
			'🈳'=>'空',
			'😍'=>'色',
			'🥵'=>'热',
			'🥶'=>'冷',
			'🕳️'=>'洞',
			'👿'=>'恶魔',
			'👏🏻'=>'鼓掌',
			'🤮'=>'吐',
			'😏'=>'坏笑'
		);
		foreach($array as $k=>$v) {
			$text = str_replace($k,$v,$text);
		}
		return $text;
	}
	/*
	* curl
	*/
	public static function teacher_curl($url, $paras = array()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if (@$paras['Header']) {
			$Header = $paras['Header'];
		} else {
			$Header[] = "Accept:*/*";
			$Header[] = "Accept-Encoding:gzip,deflate,sdch";
			$Header[] = "Accept-Language:zh-CN,zh;q=0.8";
			$Header[] = "Connection:close";
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $Header);
		if (@$paras['ctime']) { // 连接超时
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $paras['ctime']);
		} else {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		}
		if (@$paras['rtime']) { // 读取超时
			curl_setopt($ch, CURLOPT_TIMEOUT, $paras['rtime']);
		}
		if (@$paras['post']) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $paras['post']);
		}else{
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}
		if (@$paras['header']) {
			curl_setopt($ch, CURLOPT_HEADER, true);
		}
		if (@$paras['cookie']) {
			if(@$paras['Cookie']) {
				foreach(explode('; ', $paras['cookie']) as $v)
				{
					curl_setopt($ch, CURLOPT_COOKIE, $v);
				}
			} else {
				curl_setopt($ch, CURLOPT_COOKIE, $paras['cookie']);
			}
		}
		if (@$paras['refer']) {
			if ($paras['refer'] == 1) {
				curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
			} else {
				curl_setopt($ch, CURLOPT_REFERER, $paras['refer']);
			}
		}
		if (@$paras['ua']) {
			curl_setopt($ch, CURLOPT_USERAGENT, $paras['ua']);
		} else {
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
		}
		if (@$paras['nobody']) {
			curl_setopt($ch, CURLOPT_NOBODY, 1);
		}
		if(@$paras['resolve']) {
			curl_setopt($ch, CURLOPT_IPRESOLVE, 1);
		}
		if(@$paras['jump']) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		}
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/*
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, "114.114.114.114"); //代理服务器地址
		//curl_setopt($ch, CURLOPT_PROXYPORT, 12635); //代理服务器端口
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		*/
		if (@$paras['GetCookie']) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			$result = curl_exec($ch);
			preg_match_all("/Set-Cookie: (.*?);/m", $result, $matches);
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($result, 0, $headerSize); //状态码
			$body = substr($result, $headerSize);
			$ret = [
				"Cookie" => $matches, "body" => $body, "header" => $header, 'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
			];
			curl_close($ch);
			return $ret;
		}
		$ret = curl_exec($ch);
		if(curl_errno($ch))
		{
			curl_close($ch);
			return false;
		}
		if (@$paras['loadurl']) {
			$Headers = curl_getinfo($ch);
			$ret = $Headers['redirect_url'];
		}
		self::$info = curl_getinfo($ch);
		curl_close($ch);
		return $ret;
	}
	/*
	* 随机获取一个IP
	*/
	public static function Rand_IP() {
		#第一种方法，直接生成
		$ip2id= round(rand(600000, 2550000) / 10000);
		$ip3id= round(rand(600000, 2550000) / 10000);
		$ip4id= round(rand(600000, 2550000) / 10000);
		#第二种方法，随机抽取
		$arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
		$randarr= mt_rand(0,count($arr_1)-1);
		$ip1id = $arr_1[$randarr];
		return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
	}
	/*
	* get访问获取数据
	*/
	public static function getResponseBody($url) {
		$ch = curl_init();
		#5秒超时
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
		#设置默认ua  这里经常测试，尽量用手机的ua,电脑的ua获取不到数据
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36');//'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; vivo X9 Plus Build/LMY48Z) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36');
		#把随机ip添加进请求头 
		$httpheader = [];
		$httpheader[] = 'X-FORWARDED-FOR:'.self::Rand_IP();
		$httpheader[] = 'CLIENT-IP:'.self::Rand_IP();
		#请求头中添加cookie
		$httpheader[] = 'cookie:did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		#返回数据不直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		#设置请求地址
		curl_setopt($ch, CURLOPT_URL, $url);
		#关闭ssl验证
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		#设置默认referer
		curl_setopt($ch, CURLOPT_REFERER, 'https://www.moestack.com');
		#get方式请求
		curl_setopt($ch, CURLOPT_POST, false);
		$contents = curl_exec($ch);
		curl_close($ch);
		return $contents;
	}
	/*
	* get访问获取头部
	*/
	public static function getResponseHeader($url) {
		$ch  = curl_init($url);
		$httpheader = [];
		$httpheader[] = 'X-FORWARDED-FOR:'.self::Rand_IP();
		$httpheader[] = 'CLIENT-IP:'.self::Rand_IP();
		#请求头中添加cookie
		$httpheader[] = 'cookie:did=web_'.md5(time() . mt_rand(1,1000000)).'; didv='.time().'000;clientid=3; client_key=6589'.rand(1000, 9999);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$httpheader);
		#以下两句设置返回响应头不返回响应体
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		#返回数据不直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	/*
	* 获取get post请求，可以get+号
	*/
	public static function request() {
		$explode = explode('&', $_SERVER['QUERY_STRING']);
		// print_r($explode);
		$Request = [];
		foreach($explode as $value) {
			$explod = explode('=', $value, 2);
			$Request[@$explod[0]] = @$explod[1] ?: null;
			//print_r($explod);
			unset($explod);
		}
		foreach($Request as $k=>$v) {
			if(!empty($v)) {
				$fileType = mb_detect_encoding($v, array('UTF-8','GBK','LATIN1','BIG5','GB2312')) ;
				if($fileType != 'UTF-8') {
					$data = mb_convert_encoding($v, 'utf-8', $fileType);
				}else{
					$data = $v;
				}
			}else{
				$data = $v;
			}
			$array[$k] = urldecode((String) $data);
			unset($data, $fileType);
		}
		// print_r($array);
		return array_merge($array, $_POST);
	}
	/*
	* 读取文件夹内某些文件的绝对路径
	*/
	public static function read_all($dir, ...$type) {
		if (!is_dir($dir)) {
			return array();
		}
		$dir = preg_replace('/\/$/', '', $dir);
		$textarray = [];
		$handle = opendir($dir);
		if ($handle) {
			while (($fl = readdir($handle)) !== false) {
				$temp = iconv('utf-8', 'utf-8', $dir . DIRECTORY_SEPARATOR . $fl);
				//转换成utf-8格式
				//如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
				if (!(is_dir($temp) && $fl != '.' && $fl != '..')) {
					if ($fl != '.' && $fl != '..') {
						$suffix = substr(strrchr($fl, '.'), 1);
						if($type)
						{
							foreach($type as $v) {
								if ($suffix == $v) {
									$textarray[] = array("path" => $dir . DIRECTORY_SEPARATOR, "name" => $fl, 'file'=>$dir.DIRECTORY_SEPARATOR.$fl, 'suffix'=>$suffix);
								}
							}
						} else {
							$textarray[] = array("path" => $dir . DIRECTORY_SEPARATOR, "name" => $fl, 'file'=>$dir.DIRECTORY_SEPARATOR.$fl, 'suffix'=>$suffix);
						}
					}
				}
			}
		}
		return $textarray;
	}
	/*
	* 读取某些文件夹的所有子文件夹
	*/
	public static function read_all_dir($dir) {
		if(!is_dir($dir)) {
			return false;
		}
		$dir = preg_replace('/\/$/', '', $dir);
		$array = scandir($dir);
	   // print_r($array);
		foreach($array as $k=>$v) {
			$temp = iconv('utf-8', 'utf-8', $dir . DIRECTORY_SEPARATOR . $v);
			if(is_dir($temp) && $v != '.' && $v != '..') {
				$dirarray[] = ['name'=>$v, 'path'=>$temp];
			}
		}
		return $dirarray;
	}
	/*
	* 获取跳转
	*/
	public static function loadurl($url, $Array = []) {
		if(!isset($Array['loadurl']) || $Array['loadurl'] != 1) {
			$Array['loadurl'] = 1;
		}
		$Array['nobody'] = 1;
		$urls = self::teacher_curl($url, $Array);
		if(stristr($urls, '//')) {
			return self::loadurl($urls);
		}
		return $url;
	}
	/*
	* ASCII转utf8
	*/
	public static function ASCII_UTF8($string) {
		preg_match_all('/&#([0-9]+);/', $string, $int);
		if(empty($int[1])) {
			return $string;
		}
		foreach($int[1] as $k=>$v) {
			$string = str_replace('&#'.$v.';', chr($v), $string);
		}
		return $string;
	}
	/*
	* 一种加密
	*/
	public static function encrypt($string, $operation, $key='ovooa') {
		$key=md5($key);
		$key_length=strlen($key);
		$string = $operation == 'D' ? str_replace(' ', '+', $string) : $string;
		$string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
		$string_length=strlen($string);
		$rndkey=$box=array();
		$result='';
		for($i=0;$i<=255;$i++) {
			$rndkey[$i]=ord($key[$i%$key_length]);
			$box[$i]=$i;
		}
		for($j=$i=0;$i<256;$i++) {
			$j=($j+$box[$i]+$rndkey[$i])%256;
			$tmp=$box[$i];
				$box[$i]=$box[$j];
				$box[$j]=$tmp;
		}
		for($a=$j=$i=0;$i<$string_length;$i++) {
			$a=($a+1)%256;
			$j=($j+$box[$a])%256;
			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;
			$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
		}
		if($operation=='D') {
			if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)) {
				return substr($result,8);
			}else{
				return 'key错误';
			}
		}else{
			return str_replace('=','',base64_encode($result));
		}
	}
	/*
	* 去除空占位符
	*/
	public static function nate($String) {
		return str_replace(Array("\r", "\n", "\r\n", ' '), '', (String) $String);
	}
	public static function is_Skey($Skey) {
		if(strlen(str_replace(' ', '', $Skey)) == 10) {
			return true;
		}else{
			return false;
		}
	}
	/*
	* 判断是否是pskey
	*/
	public static function is_Pskey($Pskey) {
		if(strlen((String) $Pskey) == 44) {
			 //preg_match('/^.{38,46}$/', $Pskey)
			return true;
		}else{
			return false;
		}
	}
	/*
	* 判断是否是手机号
	*/
	public static function is_phone($number) {
		if(preg_match('/^1[1-9][0-9]{9,10}$/', $number))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	* 忘了
	*/
	public static function strtouni($str)
	{
		return preg_replace('/^"|"$/', '', Json_encode((string)$str));
	}
	/*
	* 忘了
	*/
	public static function unitostr($uni)
	{
		return preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($r) {
			return iconv('UCS-2BE', 'UTF-8', pack('H4', $r[1]));
		},
		$uni);
	}
	/*
	* mb库的split
	*/
	public static function mb_split($string, $split_length = 1, $encoding = null)
	{
		if (null !== $string && !\is_scalar($string) && !(\is_object($string) && \method_exists($string, '__toString'))) {
			trigger_error('mb_str_split(): expects parameter 1 to be string, '.\gettype($string).' given', E_USER_WARNING);
			return null;
		}
		if (null !== $split_length && !\is_bool($split_length) && !\is_numeric($split_length)) {
			trigger_error('mb_str_split(): expects parameter 2 to be int, '.\gettype($split_length).' given', E_USER_WARNING);
			return null;
		}
		$split_length = (int) $split_length;
		if (1 > $split_length) {
			trigger_error('mb_str_split(): The length of each segment must be greater than zero', E_USER_WARNING);
			return false;
		}
		if (null === $encoding) {
			$encoding = mb_internal_encoding();
		} else {
			$encoding = (string) $encoding;
		}
   
		if (! in_array($encoding, mb_list_encodings(), true)) {
			static $aliases;
			if ($aliases === null) {
				$aliases = [];
				foreach (mb_list_encodings() as $encoding) {
					$encoding_aliases = mb_encoding_aliases($encoding);
					if ($encoding_aliases) {
						foreach ($encoding_aliases as $alias) {
							$aliases[] = $alias;
						}
					}
				}
			}
			if (! in_array($encoding, $aliases, true)) {
				trigger_error('mb_str_split(): Unknown encoding "'.$encoding.'"', E_USER_WARNING);
				return null;
			}
		}
		$result = [];
		$length = mb_strlen($string, $encoding);
		for ($i = 0; $i < $length; $i += $split_length) {
				$result[] = mb_substr($string, $i, $split_length, $encoding);
		}
		return $result;
	}
	/*
	* 删除过期文件
	*/
	public static function delfile($dir, $time)
	{
		if(is_dir($dir)) {
			if($dh=opendir($dir)) {
				while (false !== ($file = readdir($dh))) {
					// $count = strstr($file,'duodu-')||strstr($file,'dduo-')||strstr($file,'duod-');
					if($file!='.' && $file!='..') {
						$fullpath=$dir.'/'.$file;
						if(!is_dir($fullpath)) {
							$filedate=filemtime($fullpath);
							$minutes=round((time()-$filedate)/60);
							if($minutes>$time) unlink($fullpath);
							//删除文件
						}
					}
				}
			}
		}
		closedir($dh);
		return true;
	}
	/*
	* 阿拉伯数字转大写数字，有bug
	*/
	public static function chinanum($num)
	{
		$char = array("零","一","二","三","四","五","六","七","八","九");
		$dw = array("","十","百","千","万","亿","兆");
		$retval = "";
		$proZero = false;
		for($i = 0;$i < strlen($num);$i++) {
			if($i > 0)
			{
				$temp = (int)(($num % pow (10,$i+1)) / pow (10,$i));
			}
			else {
				$temp = (int)($num % pow (10,1));
			}
			if($proZero == true && $temp == 0)
			{
				continue;
			}
			if($temp == 0) 
			{
				$proZero = true;
			} else {
				$proZero = false;
			}
			if($proZero)
			{
				if($retval == "")
				{
					continue;
				}
				$retval = $char[$temp].$retval;
			} else {
				$retval = $char[$temp].$dw[$i].$retval;
			}
		}
		if($retval == "一十")
		{
			$retval = "十";
		}
		$retval = str_replace('一十','十',$retval);
		return $retval;
	}
	/*
	* 判断是否是邮箱
	*/
	public static function is_email($email)
	{
		$pattern_test = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i"; 
		return preg_match($pattern_test, $email); 
	}
	/*
	* 忘了
	*/
	public static function getRandomHex($length)
	{
		if (function_exists('random_bytes')) {
			return bin2hex(random_bytes($length / 2));
		}
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($length / 2, MCRYPT_DEV_URANDOM));
		}
		if (function_exists('openssl_random_pseudo_bytes')) {
			return bin2hex(openssl_random_pseudo_bytes($length / 2));
		}
	}
	/*
	* 转码，忘了转什么
	*/
	public static function bchexdec($hex)
	{
		$dec = 0;
		$len = strlen($hex);
		for ($i = 1; $i <= $len; $i++) {
			$dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
		}

		return $dec;
	}
	/*
	* 同上
	*/
	public static function bcdechex($dec)
	{
		$hex = '';
		do {
			$last = bcmod($dec, 16);
			$hex = dechex($last).$hex;
			$dec = bcdiv(bcsub($dec, $last), 16);
		} while ($dec > 0);

		return $hex;
	}
	/*
	* str转16进制
	*/
	public static function str2hex($string)
	{
		$hex = '';
		for ($i = 0; $i < strlen($string); $i++) {
			$ord = ord($string[$i]);
			$hexCode = dechex($ord);
			$hex .= substr('0'.$hexCode, -2);
		}

		return $hex;
	}
}
/* End */
$request = need::request();
$msg = isset($request['msg']) ? $request['msg'] : false;
$songid = isset($request['songid']) ? $request['songid'] : false;
$mid = isset($request['mid']) ? $request['mid'] : false;
$page = isset($request['page']) ? $request['page'] : false;
$limit = isset($request['limit']) ? $request['limit'] : false;
$tail = isset($request['tail']) ? $request['tail'] : false;
$br = isset($request['br']) ? $request['br'] : 4;
$Skey = isset($request['Skey']) ? $request['Skey'] : false;
$Cookie = isset($request['Cookie']) ? $request['Cookie'] : false;
$uin = isset($request['uin']) ? $request['uin'] : false;
$n = isset($request['n']) ? $request['n'] : false;
$type = isset($request['type']) ? $request['type'] : 'j';
new QQ_Music(['msg'=>$msg, 'songid'=>$songid, 'mid'=>$mid, 'page'=>$page, 'limit'=>$limit, 'tail'=>$tail, 'br'=>$br, 'n'=> $n, 'type'=>$type, 'Skey'=>$Skey, 'uin'=>$uin, 'Cookie'=>$Cookie]);
class QQ_Music
{
	public $info = [];
	public $array = [];
	public $message, $data, $raw, $error, $curlinfo;
	public $header = array(
				'Referer'		 => 'https://y.qq.com/',
				'Cookie'		  => null,
				'User-Agent'	  => 'QQ%E9%9F%B3%E4%B9%90/54409 CFNetwork/901.1 Darwin/17.6.0 (x86_64)',
				'Accept'		  => '*/*',
				'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
				'Connection'	  => 'keep-alive',
				'Content-Type'	=> 'application/x-www-form-urlencoded',
	);
	public $guid = 1559616839293;
	public function __construct($array)
	{
		$this->info = $array;
		$this->parameterException();
	}
	public function parameterException()
	{
		$info = $this->info;
		if(isset($this->info['uin']) && need::is_num($this->info['uin']) && isset($this->info['Skey']) && need::is_Skey($this->info['Skey']))
		{
			$this->header['Cookie'] = "uin=o{$this->info['uin']}; skey={$this->info['Skey']}";
			// print_r($this->header);exit;
		} else if(isset($this->info['Cookie']) && $this->info['Cookie']) {
			$this->header['Cookie'] = $this->info['Cookie'];
		} else {
			$this->header['Cookie'] = need::cookie('Skey');
		}
		if(!$info['msg'])
		{
			if((!$info['songid'] || !is_numeric($info['songid'])) && !$info['mid']) return $this->result(['code'=>-1, 'text'=>'请输入需要搜索的歌名或Id']);
			if($info['songid'] && is_numeric($info['songid']))
			{
				return $this->songid();
			}
			if($info['mid']) return $this->getMusic();
			return $this->result(['code'=>-1, 'text'=>'请输入需要搜索的歌名或Id']);
		}
		if(!$info['br'] || !is_numeric($info['br'])) $info['br'] = 4;
		if(!$info['n'] || !is_numeric($info['n']) || $info['n'] < 1) $info['n'] = 0;
		if(!$info['page'] || !is_numeric($info['page']) || $info['page'] < 1) $info['page'] = 1;
		if(!$info['limit'] || !is_numeric($info['limit']) || $info['limit'] < 1 || $info['limit'] > 50) $info['limit'] = 10;
		$this->info = $info;
		return $this->getName();
	}
	public function exec($api)
	{
		if (isset($api['encode'])) {
			$api = call_user_func_array(array($this, $api['encode']), array($api));
		}
		if ($api['method'] == 'GET') {
			if (isset($api['body'])) {
				$api['url'] .= '?'.http_build_query($api['body']);
				$api['body'] = null;
			}
		}
		if (isset($api['headers']) && $api['headers'])
		{
			$this->curl($api['url'], $api['body'], 0, (isset($api['post']) ? $api['post'] : false), $api['headers']);
		} else {
			$this->curl($api['url'], $api['body'], 0, (isset($api['post']) ? $api['post'] : false));
		}
		$this->data = $this->raw;
		// echo 'exec';
		// print_r($this->curl($api['url'], $api['body'], 0, (isset($api['post']) ? $api['post'] : false), $api['headers']));
		// print_r($api);
		if (isset($api['decode'])) {
			$this->data = call_user_func_array(array($this, $api['decode']), array($this->data));
		}
		if (isset($api['format'])) {
			$this->data = $this->clean($this->data, $api['format']);
		}
		return $this->data;
	}
	public function curl($url, $payload = null, $headerOnly = 0, $post = false, $headers = null)
	{
		$headers = $headers ? $headers : $this->header;
		$header = array_map(function ($k, $v) {
			return $k.': '.$v;
		}, array_keys($headers), $headers);
		// print_r($header);
		$curl = curl_init();
		if (!is_null($payload)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			if($post === false)
			{
				curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($payload) ? http_build_query($payload) : $payload);
			} else {
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload,320));
			}
			// $header[] = 'Content-Length: '.strlen((is_array($payload) ? http_build_query($payload) : $payload));
		}
		curl_setopt($curl, CURLOPT_HEADER, $headerOnly);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
		curl_setopt($curl, CURLOPT_IPRESOLVE, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if(isset($headers['Referer']) && $headers['Referer'])
		{
			// echo 123;
			curl_setopt($curl, CURLOPT_REFERER, $headers['Referer']);
		}
		curl_setopt($curl, CURLOPT_COOKIE, $this->header['Cookie']);
		/*if ($this->proxy) {
			curl_setopt($curl, CURLOPT_PROXY, $this->proxy);
		}*/
		// print_r(curl_exec($curl));
		for ($i = 0; $i < 3; $i++) {
			$this->raw = curl_exec($curl);
			$this->error = curl_errno($curl);
			$this->curlinfo = curl_getinfo($curl);
			if (!$this->error) {
				break;
			}
		}
		// print_r(($this));
		curl_close($curl);
		return $this;
	}
	public function pickup($array, $rule)
	{
		$t = explode('.', $rule);
		foreach ($t as $vo) {
			if (!isset($array[$vo])) {
				return array();
			}
			$array = $array[$vo];
		}

		return $array;
	}
	public function clean($raw, $rule)
	{
		$raw = json_decode($raw, true);
		// print_r($raw);
		if (!empty($rule)) {
			$raw = $this->pickup($raw, $rule);
		}
		if (!isset($raw[0]) && count($raw)) {
			$raw = array($raw);
		}
		$result = array_map(array($this, 'format'), $raw);
		return ($result);
	}
	protected function format($data)
	{
		if (isset($data['musicData'])) {
			$data = $data['musicData'];
		}
		// print_r($data);exit;
		/*
		$result = array(
			'songid'	   => $data['songid'],
			'song'	 => $data['songname'],
			'singers'   => array(),
			'album'	=> trim($data['albumname']),
			'mid'   => $data['songmid'],
			'picture' => 'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['albummid'].'.jpg?max_age=2592000'
			// 'source'   => 'tencent',
		);
		foreach ($data['singer'] as $vo) {
			$result['singers'][] = $vo['name'];
		}
		$result['singer'] = join('、', $result['singers']);
		*/
		
		$result = array(
			'songid'=>$data['id'],
			'song'=>$data['name'],
			'singers'=>array(),
			'album'=>$data['album']['name'],
			'mid'=>$data['mid'],
			'picture'=>'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['album']['mid'].'.jpg?max_age=2592000'
		);
		foreach ($data['singer'] as $vo) {
			$result['singers'][] = $vo['name'];
		}
		$result['singer'] = join('、', $result['singers']);
		
		return $result;
	}
	/*
	* 通过名字获取列表以及音乐信息
	*/
	public function getName()
	{
	/*
		$api = array(
			'method' => 'POST',
			'url' => 'https://shc6.y.qq.com/soso/fcgi-bin/search_for_qq_cp',//https://c.y.qq.com/soso/fcgi-bin/client_search_cp',
			'body' => array(
				'format' => 'json',
				'p' => isset($this->info['page']) ? $this->info['page'] : 1,
				'n' => isset($this->info['limit']) ? $this->info['limit'] : 10,
				'w' => $this->info['msg'],
				'aggr' => 1,
				'lossless' => 1,
				'cr' => 1,
				'new_json' => 1,
			),
			'format' => 'data.song.list',
		);
		*/
		$guid = $this->guid;
		/*
		for($i = 0 ; $i < $this->info['page'] ; $i++)
		{
		*/
		
			$api = [
				'method'=>'POST',
				'url'=>'https://u.y.qq.com/cgi-bin/musicu.fcg?_webcgikey=DoSearchForQQMusicDesktop&_='.need::time_sss(),
				'body'=>[
					"comm"=>[
						"g_tk"=>1617175180,
						"uin"=>2354452553,
						"format"=>"json",
						"inCharset"=>"utf-8",
						"outCharset"=>"utf-8",
						"notice"=>0,
						"platform"=>"h5",
						"needNewCode"=>1,
						"ct"=>23,
						"cv"=>0
					],
					"req_0"=>[
						"method"=>"DoSearchForQQMusicDesktop",
						"module"=>"music.search.SearchCgiService",
						"param"=>[
							"remoteplace"=>"txt.mqq.all",
							"searchid"=>$guid,
							"search_type"=>0,
							"query"=> (String) $this->info['msg'],
							"page_num"=>(int) isset($this->info['page']) ? $this->info['page'] : 1,
							"num_per_page"=> (int) isset($this->info['limit']) ? $this->info['limit'] : 10
						]
					]
				],
				'post'=>true,
				'format'=>'req_0.data.body.song.list',
				'headers'=>[
					'Origin'=>'https://i.y.qq.com',
					'Host'=>'u.y.qq.com',
					'User-Agent'=>'Mozilla/5.0 (Linux; Android 13; 22127RK46C Build/TKQ1.220905.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.97 Mobile Safari/537.36',
					'Accept'		  => 'application/json',
					'Accept-Language' => 'zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
					'Connection'	  => 'keep-alive',
					'Cookie'=>$this->header['Cookie'],
					'Content-Type'	=> 'application/x-www-form-urlencoded',
					'X-Requested-With: mark.via',
					'Sec-Fetch-Site: same-site',
					'Sec-Fetch-Mode: cors',
					'Sec-Fetch-Dest: empty',
					'Referer: https://i.y.qq.com/',
					'Accept-Encoding: gzip, deflate'
				]
			];
			// print_r($this->info);
			// print_r($api);exit;
		/*
		$api = array(
			'method' => 'GET',
			'url' => 'https://c.y.qq.com/soso/fcgi-bin/search_for_qq_cp',
			'body' => array(
				'format' => 'json',
				'p' => isset($this->info['page']) ? $this->info['page'] : 1,
				'n' => isset($this->info['limit']) ? $this->info['limit'] : 10,
				'w' => $this->info['msg'],
				'platform' => 'h5'
			),
			// 'format' => 'data.song.list',
			'headers'=>[
				'Host'=>'c.y.qq.com',
				'Connection'=>'keep-alive',
				'Cache-Control'=>'max-age=0',
				'Upgrade-Insecure-Requests'=>'1',
				'User-Agent'=>'Mozilla/5.0 (Linux; Android 13; 22127RK46C Build/TKQ1.220905.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.97 Mobile Safari/537.36',
				'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
				'dnt'=>'1',
				'X-Requested-With'=>'mark.via',
				'Sec-Fetch-Site'=>'none',
				'Sec-Fetch-Mode'=>'navigate',
				'Sec-Fetch-User'=>'?1',
				'Sec-Fetch-Dest'=>'document',
				'Referer'=>'https://c.y.qq.com/soso/fcgi-bin/search_for_qq_cp?g_tk=5381&uin=0&format=jsonp&inCharset=utf-8&outCharset=utf-8&notice=0&platform=h5&needNewCode=1&w=%E4%BD%A0%E5%A5%BD%E4%B8%8D%E5%A5%BD&zhidaqu=1&catZhida=1&t=0&flag=1&ie=utf-8&sem=1&aggr=0&perpage=20&n=20&p=1&remoteplace=txt.mqq.all&_=1520833663464',
				'Accept-Encoding'=>'gzip, deflate',
				'Accept-Language'=>'zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7'
			]
		);*/
		// print_r($api);
		// $api['headers']['Content-Length'] = strlen(json_encode($api['body'], 320));
		
		$data = ($this->exec($api));
		/*
		if(isset($data['req_0'])) $data = $data['req_0'];
		// print_r($data['data']['meta']);
		if(isset($data['data']) && $data['data'])
		{
			if(isset($data['data']['meta']['next_page_start']['searchid']) && $data['data']['meta']['next_page_start']['searchid'])
			{
				$this->guid = $data['data']['meta']['next_page_start']['searchid'];
				continue;
			} else {
				$data = $data['data']['body']['song']['list'];
				break;
			}
		} else {
			break;
		}
	}*/
		// print_r($data);exit;
		// print_r($api['url'].http_build_query($api['body']));exit;
		if($this->info['n'] < 1)
		{
			$message = [];
			foreach($data as $k=>$v)
			{
				$message[] = ($k + 1).'、'.$v['song'] . '  —  '. $v['singer'];
			}
			return $this->result(['code'=>1, 'text'=>'请选择歌曲', 'data'=>$data], join("\n", $message));
		} else {
			$n = ($this->info['n'] - 1);
			if(isset($data[$n]))
			{
				// print_r($this->header);
				// print_r($data);exit;
				$this->info['mid'] = $data[$n]['mid'];
				$this->info['songid'] = $data[$n]['songid'];
				$this->array = $data[$n];
				return $this->getMusic();
			} else {
				$this->info['n'] = 0;
				return $this->getName();
			}
		}
	}
	public function songid()
	{
		$api = [
			'method'=>'GET',
			'url'=>'https://i.y.qq.com/v8/playsong.html',
			'body'=>[
				'platform'=>11,
				'appshare'=>'android_qq',
				'appversion'=>'10170509',
				'hosteuin'=>'owok7evkow4koz**',
				'songid'=>$this->info['songid'],
				'type'=>'0',
				'appsongtype'=>'1',
				'_wv'=>'1',
				'source'=>'qq',
				'ADTAG'=>'qfshare'
			]
		];
		$data = $this->exec($api);
		preg_match("/__ssrFirstPageData__ =([\s\S]*?)<\/script>/",$data, $Music);
		if($Music[1])
		{
			$data = json_decode($Music[1], true);
			if(isset($data['songList'][0]['mid']) && $data['songList'][0]['mid'])
			{
				$this->info['mid'] = $data['songList'][0]['mid'];
				$this->array = $data['songList'][0];
				// print_r($data);
				return $this->getMusic();
			} else {
				return $this->result(['code'=>-2, 'text'=>'获取失败，这首歌可能不行，换首试试？']);
			}
		} else {
			return $this->result(['code'=>-2, 'text'=>'获取失败，这首歌可能不行，换首试试？']);
		}
	}
	public function getMusic()
	{
		$Id = $this->info['mid'];
		if(!$Id)
		{
			if(!$this->info['songid'])
			{
				return $this->result(['code'=>-3, 'text'=>'出现了意想不到的错误，请重试']);
			} else {
				return $this->songid();
			}
		} else {
			$api = array(
				'method' => 'GET',
				'url'	=> 'https://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg',
				'body'   => array(
					'songmid'  => $Id,
					'platform' => 'yqq',
					'format'   => 'json',
				)
			);
			$guid = uniqid();
			$data = json_decode($this->exec($api), true);
			// print_r($data);
			$cover = 'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.@$data['data'][0]['album']['mid'].'.jpg?max_age=2592000';
			$type = array(
				// array('try_end', 102200, 'Q0M0', 'flac'),
				// array('try_begin', 76592, 'QN00', 'ogg'),
				array('size_try', 960887, 'AI00', 'flac'),
				// array('size_hires', 0, 'AI00', 'flac'),
				array('size_flac', 999, 'F000', 'flac'),
				array('size_320mp3', 320, 'M800', 'mp3'),
				array('size_192aac', 192, 'C600', 'm4a'),
				array('size_128mp3', 128, 'M500', 'mp3'),
				array('size_96aac', 96, 'C400', 'm4a'),
				array('size_48aac', 48, 'C200', 'm4a'),
				array('size_24aac', 24, 'C100', 'm4a')
			);
			$uin = need::cookie('Robot', true);
			$payload = array(
				'req_0' => array(
					'module' => 'vkey.GetVkeyServer',
					'method' => 'CgiGetVkey',
					'param'  => array(
						'guid'	  => (string) $guid,
						'songmid'   => array(),
						'filename'  => array(),
						'songtype'  => array(),
						'uin'	   => $uin,
						'loginflag' => 1,
						'platform'  => '20',
					),
				),
			);
			foreach ($type as $vo) {
				$payload['req_0']['param']['songmid'][] = $data['data'][0]['mid'];
				$payload['req_0']['param']['filename'][] = $vo[2].$data['data'][0]['file']['media_mid'].'.'.$vo[3];
				$payload['req_0']['param']['songtype'][] = $data['data'][0]['type'];
			}
			$api = array(
				'method' => 'GET',
				'url'	=> 'https://u.y.qq.com/cgi-bin/musicu.fcg',
				'body'   => array(
					'format'	  => 'json',
					'platform'	=> 'yqq.json',
					'needNewCode' => 0,
					'data'		=> json_encode($payload),
				),
			);
			$response = json_decode($this->exec($api), true);
			// print_r($payload);
			// print_r($response);
			$vkeys = isset($response['req_0']['data']['midurlinfo']) ? $response['req_0']['data']['midurlinfo'] : null;
			$index = isset($type[($this->info['br'] - 1)]) ? ($this->info['br'] - 1) : 4;
			foreach(range($index, 7) as $i)
			{
				$vo = $type[$i];
				$url = null;
				if (isset($data['data'][0]['file'][$vo[0]]))
				{
					if (!empty($vkeys[$i]['vkey']))
					{
						$url = array(
							'url' => "http://y.qq.com/n/yqq/song/{$this->info['mid']}.html",
							'music'  => $response['req_0']['data']['sip'][0].$vkeys[$i]['purl'],
							'size' => $data['data'][0]['file'][$vo[0]],
							'br'   => $vo[1],
						);
						break;
					}
				}
			}
			if($url)
			{
				if(isset($this->array['vs']))
				{
					$singer = [];
					foreach($this->array['singer'] as $v)
					{
						$singer[] = $v['name'];
					}
					$result = [
						'code'=>1,
						'text'=>'获取成功',
						'data'=>[
							'song'=>$this->array['name'],
							'mid'=>$this->info['mid'],
							'songid'=>$this->info['songid'],
							'album'	=> trim($this->array['album']['name']),
							'url'=>"http://y.qq.com/n/yqq/song/{$this->info['mid']}.html",
							'picture'=>$cover,
							'singer'=>join('、', $singer),
							'singers'=>$singer,
							'music'=>$url['music'],
							'size'=>$url['size'],
							'br'=>$url['br']
						]
					];
				} else {
					$singer = [];
					foreach($data['data'][0]['singer'] as $v)
					{
						$singer[] = $v['name'];
					}
					$result = [
						'code'=>1,
						'text'=>'获取成功',
						'data'=>($this->array && $url) ? array_merge($this->array, $url) : [
							'songid'	   => $data['data'][0]['id'],
							'song'	 => $data['data'][0]['name'],
							'singers'   => $singer,
							'singer'   => join('、', $singer),
							'album'	=> trim($data['data'][0]['album']['name']),
							'mid'   => $data['data'][0]['mid'],
							'picture' => 'https://y.gtimg.cn/music/photo_new/T002R800x800M000'.$data['data'][0]['album']['mid'].'.jpg?max_age=2592000',
							'music'=>$url['music'],
							'size'=>$url['size'],
							'br'=>$url['br']
						]
					];
				}
				
				$message = "±img={$cover}±\n歌曲名：{$result['data']['song']}\n歌手：{$result['data']['singer']}\n播放链接：{$result['data']['music']}";
				return $this->result($result, $message);
			} else {
			/*
				if(isset($type[$this->info['br']]) && $type[$this->info['br']])
				{
					$this->info['br'] = ($this->info['br'] + 1);
					return $this->getMusic();
				} else {
				*/
					return $this->result(['code'=>-4, 'text'=>"1、Cookie可能失效了，建议使用自己的Cookie\n2、不支持这种音质\n3、母带不能听是因为没钱买超级会员。"]);
				// }
			}
		}
	}
	public function result($array, $message = null)
	{
		if(!$message) $message = $array['text'];
		switch($this->info['type'])
		{
			case 'text':
				need::send($message, 'text');
			break;
			case 'json':
				if(isset($array['data']['music']) && $array['data']['music'])
				{
					need::send('json:{"app":"com.tencent.qzone.structmsg","desc":"QQ音乐","view":"music","ver":"0.0.0.1","prompt":"[分享]'.$array['data']['song'].'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"ctime":1646799816,"desc":"'.$array['data']['singer'].'","jumpUrl":"https://y.qq.com/n/ryqq/songDetail/'.$this->info['mid'].'","musicUrl":"'.$array['data']['music'].'","preview":"'.$array['data']['picture'].'","sourceMsgId":"0","source_icon":"https://p.qpic.cn/qqconnect/0/app_100497308_1626060999/100?max-age=2592000&t=0","source_url":"http://127.0.0.1:5000/","tag":"'.$this->info['tail'].'","title":"'.$array['data']['song'].'","uin":2354452553}},"config":{"ctime":'.Time().',"forward":true,"token":"549b5afa08722eace91fdf1334a0a8c3","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":2354452553}"}', 'text');
				} else {
					need::send($array, 'json');
				}
			break;
			default:
				need::send($array, 'json');
			break;
		}
	}
}