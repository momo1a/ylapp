#########################################################################
# File Name: tatal.sh
# Author: Moshiyou
# mail: momo1a@qq.com
#Created Time:Tue 02 Aug 2016 05:43:36 PM CST
#########################################################################
#!/bin/bash
TATAL=$(ls -l)
for i in `ls`
do
 cat $i >> my.sql
done
