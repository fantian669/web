I方法是ThinkPHP用于更加方便和安全的获取系统输入变量，可以用于任何地方，用法格式如下：
I('变量类型.变量名/修饰符',['默认值'],['过滤方法'],['额外数据源'])
<?php 
	$name = I('get.name');//相当于  $_GET['name']
	echo I('param.id'); // get或post，param.可以省略
	echo I('path.1'); // 获取重写的url变量
	I('get.id/d'); // 强制转换成整数，有以下几种可选择：
	//参数  含义
	//s	    强制转换为字符串类型
	//d	    强制转换为整形类型
	//b	    强制转换为布尔类型
	//a	    强制转换为数组类型
	//f	    强制转换为浮点类型
	I('data.file1','','',$_FILES); //读取文件
	I('get.'); // 获取整个$_GET 数组
	I('post.name','','htmlspecialchars'); // 采用htmlspecialchars方法对$_POST['name'] 进行过滤，如果不存在则返回空字符串。这是默认过滤，可以省略
	I('session.user_id',0); // 获取$_SESSION['user_id'] 如果不存在则默认为0
	I('cookie.'); // 获取整个 $_COOKIE 数组
	I('server.REQUEST_METHOD'); // 获取 $_SERVER['REQUEST_METHOD']
?>

Thinkphp 3.2.3 A()和R()方法都是对控制器类的实例化，R()方法比A()写法更简便，R()方法能直接返回类中方法返回的信息。R()方法里面封装了A()方法。
<?php 
	A('模块/控制器');
	    $a = A('Home/Index');
	    echo $a->test();
	R('模块/控制器/方法');
	    echo R('Home/Index/test');
?> 