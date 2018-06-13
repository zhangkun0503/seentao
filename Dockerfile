# 使用官方 PHP-Apache 镜像
FROM daocloud.io/php:5.6-apache

# docker-php-ext-install 为官方 PHP 镜像内置命令，用于安装 PHP 扩展依赖
RUN apt-get update --fix-missing && apt-get install -y \
    g++ autoconf bash git apt-utils libxml2-dev libcurl3-dev pkg-config \
    && ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo "Asia/Shanghai" > /etc/timezone \
    && docker-php-ext-install iconv curl mbstring \
        xml json mcrypt mysqli pdo pdo_mysql zip \
    && docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-enable gd \
    && pecl install /pecl/redis-3.0.0.tgz \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/cache/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /pecl

# 开启 URL 重写模块
# 配置默认放置 App 的目录
RUN a2enmod rewrite \
    && mkdir -p /app \
    && rm -fr /var/www/html \
    && ln -s /app/public /var/www/html && chmod -R 0777 /app
	
# 复制代码到 App 目录
COPY . /app