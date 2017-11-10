## 环境配置

### LAMP
```shell
sudo apt-get install apache2
sudo apt-get install php libapache2-mod-php
sudo apt-get install mariadb-client mariadb-server
sudo a2enmod rewrite
sudo service apache2 restart
```

### 数据库设置

- 设置管理员账户

  `sudo mysql`

  在mariadb中运行命令

  ```mysql
  GRANT ALL PRIVILEGES ON *.* TO 'manager'@'localhost' IDENTIFIED BY 'password' WITH GRANT OPTION;
  FLUSH PRIVILEGES;
  create database Phylab_db;
  use Phylab_db;
  source backup.sql;
  ```


- 设置laravel和wecenter数据库配置

  - laravel

    配置文件为`/Phylab/config/database.php`，将其中对应部分更改为

    ```
    'mysql' => [
          'driver'    => 'mysql',
          'host'      => '127.0.0.1',
          'database'  => 'Phylab_db',
          'username'  => 'manager',
          'password'  => 'password',
          'charset'   => 'utf8',
          'collation' => 'utf8_unicode_ci',
          'prefix'    => '',
          'strict'    => false,
    ]
    ```

  - wecenter

    配置文件为`/wecenter/system/config/database.php`，将其中对应部分更改为

    ```
    $config['charset'] = 'utf8';
    $config['prefix'] = 'wc_';
    $config['driver'] = 'MySQLi';
    $config['master'] = array (
      'charset' => 'utf8',
      'host' => '127.0.0.1',
      'username' => 'manager',
      'password' => 'password',
      'dbname' => 'Phylab_db'
    );
    $config['slave'] = false;
    ```

    ​

### www根目录 .htaccess 文件配置

```apache
RewriteEngine on
RewriteCond $1 !^(robots\.txt|wecenter\/)
RewriteRule ^(.*)$ /Phylab/public/$1 [L]
```

### Phylab 根目录 .htaccess 文件配置

```apache
RewriteEngine on
RewriteCond $1 !^(robots\.txt|static)
RewriteRule ^(.*)$ /Phylab/public/$1 [L]
```

### /etc/apache2/apache2.conf 配置

对应部分修改为
```
<Directory /var/www/>
        Options Indexes FollowSymLinks
		AllowOverride ALL
        Require all granted
</Directory>
```

### /etc/apache2/sites-available/000-default.conf 配置
对应部分修改为
```
DocumentRoot /var/www/
	<Directory /var/www/Phylab>
		Options Indexes FollowSymlinks
		AllowOverride all
		Require all granted
	</Directory>
```

### latex pdf生成环境配置

安装texlive 2017
```shell
wget https://mirrors.tuna.tsinghua.edu.cn/CTAN/systems/texlive/Images/texlive2017.iso
sudo mount -o loop texlive2017.iso /mnt
cd /mnt
sudo apt-get install perl-tk
sudo ./install-tl -gui text
sudo su
sudo pip install jinja2
```

## 部署

复制Phylab和wecenter文件夹到`/var/www/`目录下，修改权限为777 。

