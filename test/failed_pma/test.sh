/bin/zcat -f /var/log/nginx/access.log* | /bin/grep "pma_username" | /usr/bin/awk -F"pma_username=" '{print $2}' | /usr/bin/cut -d'&' -f1,2 | /usr/bin/cut -d' ' -f1 | /usr/bin/awk -F"&pma_password=" '{print $1 "," $2}'

#count=1
#while [ $count -lt 2000 ]
#do
#  filename="node${count}.shtml"
#  echo "$count"
#  count=`expr $count + 1`
#done