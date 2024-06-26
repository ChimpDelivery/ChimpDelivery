# update packages
sudo apt-get update -y

# locale
sudo locale-gen en_US.UTF-8
sudo update-locale LANG=en_US.UTF-8

# install common packages
sudo apt-get install software-properties-common -y

# install nginx
sudo apt-get install nginx -y

# install mariadb
sudo apt-get install mariadb-server -y

# install node (https://github.com/nodesource/distributions)
sudo apt-get install -y ca-certificates curl gnupg
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | sudo gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
NODE_MAJOR=20
echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | sudo tee /etc/apt/sources.list.d/nodesource.list
sudo apt-get update
sudo apt-get install nodejs -y

# install zip utility for composer
sudo apt-get install zip unzip -y

# add repo for >= php8.2
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update -y

# install php8.2 and extensions
sudo apt-get install --no-install-recommends php8.2 -y
sudo apt-get install php8.2-{cli,fpm,curl,mysql,mbstring,xml,zip,redis,bcmath} -y

# for deployer/deployer
sudo apt-get install acl -y

# for spatie/image-optimizer (https://github.com/spatie/image-optimizer)
sudo apt-get install jpegoptim -y
sudo apt-get install optipng -y
sudo apt-get install pngquant -y
sudo npm install -g svgo
sudo apt-get install gifsicle -y
sudo apt-get install webp -y
sudo apt-get install libavif-bin -y # minimum 0.9.3

# install redis (https://redis.io/docs/latest/operate/oss_and_stack/install/install-redis/install-redis-on-windows/)
curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list
sudo apt-get update -y
sudo apt-get install redis -y

# for laravel/horizon
sudo apt-get install supervisor -y

# install composer (https://getcomposer.org/download/)
cd ~
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php composer-setup.php --install-dir=/usr/bin --filename=composer
sudo php -r "unlink('composer-setup.php');"
