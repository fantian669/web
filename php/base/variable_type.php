<?php
/*php数据类型 整型 interger   浮点型 float   字符串型 string   布尔类型(boolean)  (常规类型)

 数组 array    对象  object  (复合数据类型)

 资源 resoource         空 null      (特殊类型)

*/ 

 $number=12; //整型
if(is_integer($number)){
	echo "\$number值为 $number 是整型";  
}

    


 $float_number=1.2356;

if(is_float($float_number)){
  echo "\$float_number值为$float_number是浮点型";#可以使用 is_float() is_double() is_real()函数判断 
}


$str1='string1 使用单引号';

$str2="string2 使用双引号";

$str3=<<<EOF
string3使用 引用定义  
EOF;

if(is_string($str1)&&is_string($str2)&&is_string($str3)){
	echo  '变量$str1$,str2,$str3是字符串';
}


$arr1=array(1,2,3,"hello"); //索引数组  调用数字中元素使用 $arr[0]值为1

$arr2=array('name'=>'jack','age'=>'23'); //关联数组  $arr2['name']值为jack


//特殊类型 null 表示什么都没有 即 没有为变量设置任何值 
//被赋予空的情况有以下三种:1.没有赋任何值   2.被赋值为null 3.被uset函数处理的变量  
$null= null;

//对象:在面向对象编程的使用   资源:在php使用资源时后自动启动垃圾回收机制,释放资源 


































