-- mysql group by 无数据补0 方法 摘自 http://www.ghugo.com/mysql-group-by-no-data-0/
-- 原sql (出现某个require_type_id 没有 require_id 和 require_final_cost) 的情况 需要补充零

SELECT  `require_type_id` , COUNT(  `require_id` ) AS count,
SUM(  `require_final_cost` ) AS price
FROM  `tb_require`
WHERE DATE_FORMAT(  `require_start_date` ,  '%Y-%m' ) = DATE_FORMAT(  '2012-10-1',  '%Y-%m' )
GROUP BY  `require_type_id`

-- 改造后 sql ,使用子查询和右连接查询

SELECT tb_type.`type_id` , IF(count IS NULL , 0, count) as count  ,
IF(price IS NULL , 0, price) as price
FROM
(select `require_type_id`,count(`require_id`) as count  ,
sum(`require_final_cost`) as price
FROM tb_require
where date_format(`require_start_date`,'%Y-%m')=  date_format('2012-10-1','%Y-%m')
GROUP BY `require_type_id`) indTable
RIGHT JOIN `tb_type` on indTable.`require_type_id` = `tb_type`.`type_id`


-- mysql 生成两个日期之间的所有日期列表的sql 摘自 http://www.dewen.net.cn/q/5730/mysql+%E7%94%9F%E6%88%90%E4%B8%A4%E4%B8%AA%E6%97%A5%E6%9C%9F%E4%B9%8B%E9%97%B4%E7%9A%84%E6%89%80%E6%9C%89%E6%97%A5%E6%9C%9F%E5%88%97%E8%A1%A8%E7%9A%84sql

--创建只存数字的表
CREATE TABLE num (i int);
INSERT INTO num (i) VALUES (0), (1), (2), (3), (4), (5), (6), (7), (8), (9);
select adddate('2012-09-01', numlist.id) as `date` from (SELECT n1.i + n10.i*10 + n100.i*100 AS id FROM num n1 cross join num as n10 cross join num as n100) as numlist where adddate('2012-09-01', numlist.id) <= '2012-09-10';



--创建临时表 + 存储过程
CREATE TEMPORARY TABLE `tmpdate` (date varchar(20));
DELIMITER $$
DROP PROCEDURE IF EXISTS zj$$
CREATE PROCEDURE zj(i DATE,j DATE)
BEGIN
WHILE i<=j DO
INSERT INTO `tmpdate` VALUES(i);
SET i=i+INTERVAL 1 DAY;
END WHILE;
END$$
DELIMITER ;

call zj('2011-01-01','2011-01-05');
select * from `tmpdate`;


--使用存储过程
DELIMITER $$

DROP PROCEDURE IF EXISTS DayRangeProc$$

CREATE PROCEDURE DayRangeProc(IN start_date datetime , IN end_date datetime )
      BEGIN
              DECLARE temp  INT;
              DECLARE range_day  INT;

              SET temp = 0;
              SET range_day = (SELECT DATEDIFF(end_date,start_date));

              WHILE temp  <= range_day DO
                    select ADDDATE(start_date, temp);
                    SET temp = temp + 1;
              END WHILE;
      END$$

  DELIMITER ;

Call DayRangeProc('2010-9-1','2010-9-10');


--1.在你系统里随表找一个足够多数据的表（行数能超过你的时间跨度就可以），假设为：pt_analyticslog_201229
--2.sql ：
SET @days =  TIMESTAMPDIFF(DAY,'2012-09-01','2012-09-10');
SET @d = -1;
SELECT @d :=@d+1,ADDDATE('2012-09-01',@d)
FROM pt_analyticslog_201229
WHERE @d<@days