源码目录说明

1、docs存放文档包括:需求、概要设计书、详细设计、用例图、数据字典等。

2、src存放开发的源码(具体目录待定)

3、sql存放数据表结构、视图、存储过程、函数、触发器等。
注意：sql目录的下一级目录是数据库名，数据库名文件夹之下是具体的sql语句

数据库结构文件命名规则如下：
数据表:xxxx.table.sql
视图:xxxx.view.sql
函数:xxxx.func.sql
触发器:xxxx.trig.sql

4、libs存放类库，包括PHP类库、javascript类库