<?php
$type = $_REQUEST['type'];
$version = $_REQUEST['version'] ? intval($_REQUEST['version']) : 3;
$message = [
    [
        'msg'    => '甄士隐梦幻识通灵　贾雨村风尘怀闺秀',
        'time'   => '2020-06-07 12:00:15',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '贾夫人仙逝扬州城　冷子兴演说荣国府',
        'time'   => '2020-06-07 12:05:11',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '贾雨村夤缘复旧职　林黛玉抛父进京都',
        'time'   => '2020-06-07 12:07:06',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '薄命女偏逢薄命郎　葫芦僧乱判葫芦案',
        'time'   => '2020-06-07 12:10:05',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'   => '游幻境指迷十二钗　饮仙醪曲演红楼梦',
        'time'   => '2020-06-07 12:20:23',
        'type'   => 'sender',
        'avatar' => 'images/users/avatar.jpg',
        'name'   => '笔下光年',
    ],
    [
        'msg'    => '贾宝玉初试云雨情　刘姥姥一进荣国府',
        'time'   => '2020-06-07 12:30:05',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '送宫花贾琏戏熙凤　宴宁府宝玉会秦钟',
        'time'   => '2020-06-07 13:00:28',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '比通灵金莺微露意　探宝钗黛玉半含酸',
        'time'   => '2020-06-07 22:00:22',
        'type'   => 'sender',
        'avatar' => 'images/users/avatar.jpg',
        'name'   => '笔下光年',
    ],
    [
        'msg'    => '恋风流情友入家塾　起嫌疑顽童闹学堂',
        'time'   => '2020-06-08 08:00:08',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '庆寿辰宁府排家宴　见熙凤贾瑞起淫心',
        'time'   => '2020-06-08 10:00:21',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '王熙凤毒设相思局　贾天祥正照风月鉴',
        'time'   => '2020-06-08 11:12:05',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '秦可卿死封龙禁尉　王熙凤协理宁国府',
        'time'   => '2020-06-08 12:33:10',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
    [
        'msg'    => '林如海捐馆扬州城　贾宝玉路谒北静王',
        'time'   => '2020-06-08 13:44:08',
        'type'   => 'sender',
        'avatar' => 'images/users/avatar.jpg',
        'name'   => '笔下光年',
    ],
    [
        'msg'    => '王凤姐弄权铁槛寺　秦鲸卿得趣馒头庵',
        'time'   => '2020-06-08 15:02:22',
        'type'   => 'recipient',
        'avatar' => 'images/users/avatar-1.png',
        'name'   => '大娃',
    ],
];

if ($type == 'history') {
    
    // 获取最近的五条聊天记录
    $temp = array_rand($message, 5);
    foreach($temp as $k => $v) {
        $data_last[] = $message[$v];
    }
    $timeDesc = array_column($data_last, 'time');
    array_multisort($timeDesc, SORT_ASC, $data_last);
    
    echo json_encode([
        'code' => 200,
        'msg'  => '成功',
        'data' => $data_last
    ]);exit;
    
} elseif ($type == 'history-all') {
    
    /**
     * 获取所有历史消息，这里假装分下页
     * @param $page     
     */
    $page = $_REQUEST['page'] ? intval($_REQUEST['page']) : 1;
    
    $redata = page_array(5, $page, $message, 0);
    $pages  = show_array($countpage, 'lyear_chat_message.php?type=history-all', $version);
    echo json_encode([
        'code' => 200,
        'msg'  => '成功',
        'data' => $redata,
        'pages' => $pages
    ]);exit;
    
}

/**
 * 基于数组分页 - 这里只是做演示用，实际使用请忽略
 * @param $count 每页多少条数据
 * @param $page 当前第几页
 * @param $array 查询出来的所有数组
 * @param $order 0 - 不变，1 - 反序
 */
function page_array($count, $page, $array, $order){  
    global $countpage; #定全局变量  
    $page  = (empty($page)) ? '1' : $page; #判断当前页面是否为空 如果为空就表示为第一页面   
    $start = ($page - 1) * $count; #计算每次分页的开始位置  
    if ($order == 1) {
        $array = array_reverse($array);
    }     
    $totals    = count($array);
    $countpage = ceil($totals/$count); #计算总页面数
    $pagedata  = array();
    $pagedata  = array_slice($array,$start,$count);
    return $pagedata; 
}

/** 
 * 分页及显示函数
 * @parm $countpage 全局变量，照写
 * @parm $url 当前url
 */ 
function show_array($countpage, $url, $version = 3){  
    $page = empty($_GET['page']) ? 1 : $_GET['page'];  
    if ($page > 1){  
        $uppage = $page - 1;
    } else {  
        $uppage = 1;
    }
    if($page < $countpage){  
        $nextpage = $page + 1;  
    
    } else {  
        $nextpage = $countpage;  
    }  
    
    if ($version == 3) {
    } else if ($version == 4) {
    } else {
        $str  = '<ul class="pagination no-border float-end">';
        if ($page == 1) {
            $str .= '<li class="page-item disabled"><a href="#!" class="page-link"><span><i class="mdi mdi-page-first"></i></span></a></li>';
            $str .= '<li class="page-item disabled"><a href="#!" class="page-link"><span><i class="mdi mdi-chevron-left"></i></span></a></li>';
        } else {
            $str .= '<li class="page-item"><a href="#!" class="page-link" data-url="'.$url.'&page=1&version=5"><span><i class="mdi mdi-page-first"></i></span></a></li>';
            $str .= '<li class="page-item"><a href="#!" class="page-link" data-url="'.$url.'&page='.$uppage.'&version=5"><span><i class="mdi mdi-chevron-left"></i></span></a></li>';
        }
        if ($page == $countpage) {
            $str .= '<li class="page-item disabled"><a href="#!" class="page-link"><span><i class="mdi mdi-chevron-right"></i></span></a></li>';
            $str .= '<li class="page-item disabled"><a href="#!" class="page-link"><span><i class="mdi mdi-page-last"></i></span></a></li>'; 
        } else {
            $str .= '<li class="page-item"><a href="#!" class="page-link" data-url="'.$url.'&page='.$nextpage.'&version=5"><span><i class="mdi mdi-chevron-right"></i></span></a></li>';
            $str .= '<li class="page-item"><a href="#!" class="page-link" data-url="'.$url.'&page='.$countpage.'&version=5"><span><i class="mdi mdi-page-last"></i></span></a></li>'; 
        }
    }
    
    $str .= '</ul>';
    
    return $str;  
} 