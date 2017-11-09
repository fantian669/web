<?php
/**
 * 用某个字段作为数组的键
 *
 * @Author   shengxia
 * @Datetime 2015-12-29
 * @param    [type]     $arr       源数组
 * @param    [type]     $fieldName 指定的字段名
 * @param    boolean    $type      是否合并
 * @return   [type]                重构后的数组
 */
function array_key_assoc($arr, $fieldName, $type=true) {
    if (empty($arr) || empty($fieldName)) {
        return array();
    }
    $new_arr = array();
    foreach ($arr as $value) {
        if (isset($value[$fieldName])) {
            if ($type) {
                $new_arr[$value[$fieldName]] = $value;
            } else {
                $new_arr[$value[$fieldName]][] = $value;
            }
        }
    }
    return $new_arr;
}

/**用于生成无极分类链
 * @Author   nada
 * @Datetime 2015-12-29
 * [getCategoryChain description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function getCategoryChain($data){
    	$root[] = ['0'];
    	$stop = false;
    	/**
    	 * 遍历生成分类链
    	 */
    	while(!$stop){
    		$stop = true;
    		$length = count($root);
    		for($i=0;$i<$length;$i++){
	    		$head = $root[$i][0];
	    		$tail = $root[$i][count($root[$i])-1];
	    		$chain = $root[$i];
	    		$hasGet = false;
	    		foreach($data as $key=>$v){
	    			if($v['parent_id'] == $tail){
	    				if($hasGet == false){
	    					array_push($root[$i],$v['category_id']);
	    					$data[$key]['chain'] = implode(',',$root[$i]);
	    					$stop = false;
	    					$hasGet = true;
	    				}else{
	    					$stop = false;
	    					$temp = $chain;
	    					array_push($temp,$v['category_id']);
	    					$data[$key]['chain'] = implode(',',$temp);
	    					$root[] = $temp;
	    				}
	    			}
	    			// $times++;
	    		}
	    	}
	    	
    	}
    	/**
    	 * 根据分类链遍历子孙
    	 * @var [type]
    	 */
    	foreach($data as $key =>$v){
    		$temp = [];
    		foreach($data as $c){
    			if(false!==strpos($c['chain'],$v['category_id'])&&$v['category_id']!=$c['category_id']){
    				$temp[] = $c['category_id'];
    			}
    			// $times++;
    		}
    		$arr[$v['category_id']] = $v;
    		$arr[$v['category_id']]['child'] = implode(',',$temp);
    	}
    	// echo $times;
    	// die;
    	return $arr;
}
/**
 * 用于生成无极分类链
 * @Author   nada
 * @Datetime 2015-12-29
 * [getCategoryTree description]
 * @param  [type]  $categories [分类链]
 * @param  integer $limit      [目录树深度]
 * @return [type]              [description]
 */
function getCategoryTree($categories,$limit = 10){
        // **生成树
        $root_tree = [];
        // 生成深度
        $i = 0;
        foreach($categories as $k=>$v){
           $temp_tree = array_slice(explode(',',$v['chain']),0,$limit);
           $temp = &$root_tree;
           $hash = implode('_',$temp_tree);
           if(empty($chain[$hash])){
                foreach($temp_tree as $v){
                    if($deep>$limit) continue;
                    if(!isset($temp[$v])){
                        $temp[$v] = [
                            'label'=> $categories[$v]['name'],
                            'sort' =>(int)$categories[$v]['sort'],
                            'value' => (int)$v,
                            'data' =>[]
                        ];
                    }
                    $temp = &$temp[$v]['data'];
                    $i ++;
               }
               $chain[$hash] = true;
           }
        }
        $root_tree[0]['label'] = '顶级分类';
        return $root_tree;
}

/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果
 */
function encrypt($txt, $key = ''){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(MD5_KEY);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $nh1 = rand(0,64);
    $nh2 = rand(0,64);
    $nh3 = rand(0,64);
    $ch1 = $chars{$nh1};
    $ch2 = $chars{$nh2};
    $ch3 = $chars{$nh3};
    $nhnum = $nh1 + $nh2 + $nh3;
    $knum = 0;$i = 0;
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
    $txt = base64_encode(time().'_'.$txt);
    $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
    $tmp = '';
    $j=0;$k = 0;
    $tlen = strlen($txt);
    $klen = strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
        $tmp .= $chars{$j};
    }
    $tmplen = strlen($tmp);
    $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
    return $tmp;
}

/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果
 */
function decrypt($txt, $key = '', $ttl = 0){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(MD5_KEY);

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $knum = 0;$i = 0;
    $tlen = @strlen($txt);
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $ch1 = @$txt{$knum % $tlen};
    $nh1 = strpos($chars,$ch1);
    $txt = @substr_replace($txt,'',$knum % $tlen--,1);
    $ch2 = @$txt{$nh1 % $tlen};
    $nh2 = @strpos($chars,$ch2);
    $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
    $ch3 = @$txt{$nh2 % $tlen};
    $nh3 = @strpos($chars,$ch3);
    $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
    $nhnum = $nh1 + $nh2 + $nh3;
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
    $tmp = '';
    $j=0; $k = 0;
    $tlen = @strlen($txt);
    $klen = @strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
        while ($j<0) $j+=64;
        $tmp .= $chars{$j};
    }
    $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
    $tmp = trim(base64_decode($tmp));

    if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
        if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
            $tmp = null;
        }else{
            $tmp = substr($tmp,11);
        }
    }
    return $tmp;
}


/**
* function：计算两个日期相隔多少年，多少月，多少天
* @param string $date1[格式如：2011-11-5]
* @param string $date2[格式如：2012-12-01]
* @return array array('年','月','日');
* remark : 该方法来源:http://www.phpernote.com/php-function/536.html
*/
function diffDate($date1,$date2){
    if(strtotime($date1)>strtotime($date2)){
        $tmp=$date2;
        $date2=$date1;
        $date1=$tmp;
    }
    list($Y1,$m1,$d1)=explode('-',$date1);
    list($Y2,$m2,$d2)=explode('-',$date2);
    $Y=$Y2-$Y1;
    $m=$m2-$m1;
    $d=$d2-$d1;
    if($d<0){
        $d+=(int)date('t',strtotime("-1 month $date2"));
        $m--;
    }
    if($m<0){
        $m+=12;
        $y--;
    }
    return array('year'=>$Y,'month'=>$m,'day'=>$d);
}