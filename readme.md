# 拷贝.evn.example到.evn
cp .evn.example .evn
# 生成KEY
php artisan key:generate
# 安装依赖
composer install
# 安装admin
php artisan admin:install

# 数据库安装
php artisan migrate
php artisan db:seed
# 存储目录链接
php artisan storage:link