php拥有4种风格:

1.XML风格

<?php
 echo "这是XML风格的标记";
?>

XML风格的标记,推荐使用标记,服务器不能禁用.该风格在XML和XHTML中都可以使用


2.脚本风格
<script language="php">
	echo "这是脚本风格的标记";
</script>>


3.简短风格
<?
echo "这是简短风格的标记";
?>

4.ASP风格

<%
echo "这是ASP风格的标记";1
%>

其中简短风格和ASP风格 需要修改php配置文件php.ini中的 short_open_tag 和 asp_tags 都设置为on
