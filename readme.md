##############################################
# CentOS 7
##############################################
# 源
$ rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm

##############################################
# Nginx
##############################################
# 安装
$ yum -y install nginx
# 启动
$ systemctl start nginx
# 设置开机启动
$ systemctl enable nginx

##############################################
# Mariadb
##############################################
# 安装
$ yum -y install mariadb-server
# 启动
$ systemctl start mariadb
# 设置开机启动
$ systemctl enable mariadb
# 初始密码
$ mysqladmin -u root password '123456'

##############################################
# PHP 7.2
##############################################
# 源
$ rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
# 安装
$ yum -y install php72w php72w-fpm php72w-devel 
# 安装扩展
$ yum -y install php72w-pdo php72w-mbstring php72w-xml php72w-ctype php72w-bcmath php72w-gd php72w-mysql
# 启动
$ systemctl start php-fpm
# 设置开机启动
$ systemctl enable php-fpm

##############################################
# Composer
##############################################
# 安装
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
# 使用国内镜像
$ composer config -g repo.packagist composer https://packagist.phpcomposer.com

##############################################
# 部署
##############################################
# 安装依赖
$ composer install
# 拷贝.env.example到.env并配置
$ cp .env.example .env
# 生成KEY
$ php artisan key:generate
# 拷贝资源
$ php artisan aetherupload:publish

# 创建数据库
$ MySql> CREATE DATABASE IF NOT EXISTS mog default charset utf8 COLLATE utf8_general_ci;
# 数据库安装
$ php artisan migrate
$ php artisan db:seed
# 目录权限
$ chown -R :www /var/www/mog
$ chmod -R 775 /var/www/mog/storage
# 链接存储目录
$ php artisan storage:link
# 链接区服目录
$ ln -s /data/ProjectPok/group storage/app/game
# 链接SALT目录
$ ln -s /data/salt storage/app/salt

# 启动计划程序
# * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
# 启动任务队列（延时）
$ php artisan queue:work -daemon

# 配置站点
$ cp nginx.conf /etc/nginx/conf.d/mog.conf
# 重启NGINX
$ systemctl restart nginx

# 优化
$ composer install --optimize-autoloader --no-dev
$ php artisan config:cache
$ php artisan route:cache

##############################################
# 开发环境
##############################################
$ composer install
$ vagrant up