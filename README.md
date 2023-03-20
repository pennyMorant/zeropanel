# ZeroPanel基于SSPanel魔改
## 新功能
    1. 重构商品购买逻辑
    2. 重构订单系统
    3. 支持自定义货币单位
    4. 大量系统设置数据库化
    5. 多语言功能
    6. 夜间模式
    7. 自定义landing页面
## 演示网站
http://zeroboard.top
## 安装教程（基于debian11）
#### 安装环境(如何安装最新环境请google)
1.nginx最新版  
2.php8.1  
3.mariadb最新版
#### 第一步，创建网站文件目录
    cd /var/www
    mkdir zeropanel
    cd zeropanel
#### 第二步，下载源码
    git clone https://github.com/zeropanel/zeropanel.git ${PWD}
#### 第三步
    wget https://getcomposer.org/installer -O composer.phar
    php composer.phar
    php composer.phar install
#### 第四步
    chmod -R 755 ${PWD}
    chown -R www-data:www-data ${PWD}
#### 第五步，创建数据库
    mysql -u root -p
    CREATE DATABASE zeropanel;
    use zeropanel;
    CREATE USER 'zeropanel'@'localhost' IDENTIFIED BY 'shezhizijidemima';
    GRANT ALL PRIVILEGES ON *.* TO 'zeropanel'@'localhost';
    FLUSH PRIVILEGES;
    source /var/www/zeropanel/sql/zero.sql;
#### 第六步，配置Nginx
    cd /etc/nginx
    vim enabled-sites/zeropanel.conf
##### 复制以下文件到nginx配置文件中
    server {
        listen 80;
        listen [::]:80;
        root /var/www/zeropanel/public;
        index index.php index.html;
        server_name 你的域名;
        location / {
            try_files $uri /index.php$is_args$args;
        }   
    
        location ~ \.php$ {
            include fastcgi.conf;
            fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        }
    }

##### 重启nginx和php-fpm
    systemctl restart nginx
    systemctl restart php8.1-fpm
#### 第六步，回到网站根目录执行下面命令
    cp config/.config.example.php .config.php
    cp config/.zeroconfig.example.php .zeroconfig.php
    cp config/appprofile.example.php appprofile.php
    vim config/.config.php 填入数据库连接信息
    php xcat Tool importAllSettings
    php xcat Tool initQQWry
    php xcat User createAdmin
#### 第七步，配置定时任务
    crontab -e
##### 复制以下文件到定时任务中
    * * * * * php /var/www/zeropanel/xcat Job CheckJob
    0 0 * * * php /var/www/zeropanel/xcat Job DailyJob
    0 * * * * php /var/www/zeropanel/xcat Job UserJob
    * * * * * php /var/www/zeropanel/xcat Job CheckUserExpire
    * * * * * php /var/www/zeropanel/xcat Job CheckUserClassExpire
    * * * * * php /var/www/zeropanel/xcat Job SendMail
    * * * * * php /var/www/zeropanel/xcat Job CheckOrderStatus
## 交流
https://t.me/zero_panel_group
    欢迎各位大佬PR，以及参与测试提交问题
