DATE=`date +%Y_%m_%d`
cd /home/phylab/backups
mkdir -p $DATE
cat ../password | sudo -S cp -r /var/www/ ./$DATE
DBP=`cat ../dbpwd`
mysqldump -umanager -p$DBP -hlocalhost Phylab_db > ./$DATE/backup.sql
