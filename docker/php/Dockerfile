FROM php:7.2.3-fpm

# Install Packages

RUN apt-get update

RUN apt-get install -y vim autoconf unzip zsh git zlib1g-dev libicu-dev libpq-dev libexif-dev xfonts-base xfonts-75dpi fontconfig \
    libfontconfig1 libxrender1 libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev libxml2-dev libxslt1-dev openssl libcurl4-openssl-dev libssh2-1-dev \
    && docker-php-ext-install iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

#Intall ruby
RUN apt-get install -y ruby-full bundler

RUN apt-get install jpegoptim optipng

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mysqli zip mbstring intl opcache calendar exif bcmath soap

RUN apt-get install -y libssl-dev && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install ftp


# Instal Oh my Zsh
RUN bash -c "$(curl -fsSL https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh)"
RUN sed -i -- 's/robbyrussell/kafeitu/g' /root/.zshrc # Other awesome theme: random kafeitu sonicradish wezm+
RUN mkdir -p /root/.oh-my-zsh/custom/themes
ADD kafeitu.zsh-theme /root/.oh-my-zsh/custom/themes

# Right access
RUN usermod -u 1000 www-data

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Install the Symfony Installer
RUN curl -LsS http://symfony.com/installer -o /usr/local/bin/symfony
RUN chmod a+x /usr/local/bin/symfony

# Add Config
ADD .zshrc /root
ADD vim/.vimrc /root
ADD command.sh /root/
ADD ssh /root/.ssh
RUN if [ -f ~/.ssh/id_rsa ]; then chmod 600 ~/.ssh/id_rsa; fi
RUN if [ -f ~/.ssh/id_rsa.pub ]; then chmod 600 ~/.ssh/id_rsa.pub; fi
ADD php.ini /usr/local/etc/php/
ADD php-composer.ini /usr/local/etc/php/

# Install wkhtml
RUN apt-get install -y dpkg

RUN echo "deb http://ftp.fr.debian.org/debian/ jessie main" > /etc/apt/sources.list && apt-get update

#Xdebug
RUN touch /usr/local/etc/php/conf.d/xdebug.ini; \
	echo xdebug.remote_enable=1 >> /usr/local/etc/php/conf.d/xdebug.ini; \
  	echo xdebug.remote_autostart=0 >> /usr/local/etc/php/conf.d/xdebug.ini; \
  	echo xdebug.remote_connect_back=1 >> /usr/local/etc/php/conf.d/xdebug.ini; \
  	echo xdebug.remote_port=9000 >> /usr/local/etc/php/conf.d/xdebug.ini; \
  	echo xdebug.remote_log=/tmp/php5-xdebug.log >> /usr/local/etc/php/conf.d/xdebug.ini;
RUN	mkdir /root/software

WORKDIR /root/software

RUN curl -Lo xdebug-2.4.0.tgz http://xdebug.org/files/xdebug-2.4.0.tgz && \
	tar -xvzf xdebug-2.4.0.tgz

#ssh2 plugin
RUN curl -Lo master.zip https://github.com/php/pecl-networking-ssh2/archive/master.zip && \
  unzip master.zip

RUN cd pecl-networking-ssh2-master && \
  phpize && \
  ./configure && \
  make && \
  cp ./modules/ssh2.so /usr/local/lib/php/extensions/ssh2.so

      
 # Fix time shift problem
RUN rm /etc/localtime && \
  ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
  && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
  && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
  && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
  && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini
