#########################################################################
# File Name: my.sh
# Author: Moshiyou
# mail: momo1a@qq.com
#Created Time:Fri 05 Aug 2016 08:54:32 AM CST
#########################################################################
#!/bin/bash
rm -rf /mnt/myweb/my.sql
for i in `ls | grep -v "my.sh"`
do
	cat $i >> /mnt/myweb/my.sql
done
