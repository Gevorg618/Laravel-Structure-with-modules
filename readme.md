# Landscape 2.0

## Requirements

- PHP 7.1+
- Node 8.6+
- NPM 5+
- MySQL 5.7
- Apache 2.4
- Redis 4+
- Yarn (Preferred but optional)

## Installation

*Virtual Host & /etc/hosts file entries *

### /etc/hosts

```
127.0.0.1   beta.landmarkdev.com docs.beta.landmarkdev.com
```

### Apache VirtualHost Entry

```
<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host.example.com
    DocumentRoot "<DOCUMENT ROOT OF APP>/public"
    ServerName landmarkdev.com
    ErrorLog "logs/landmarkdev-error_log"
    CustomLog "logs/landmarkdev-access_log" common
    SetEnv ENVIRONMENT 'dev'
</VirtualHost>

<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host.example.com
    DocumentRoot "<DOCUMENT ROOT OF APP>/public/build/legacyapi/docs"
    ServerName docs.landmarkdev.com
    ErrorLog "logs/landmarkdev-error_log"
    CustomLog "logs/landmarkdev-access_log" common
    SetEnv ENVIRONMENT 'dev'
</VirtualHost>
```

### Dependencies

```
apt-get install -y git redis-server php7.2 php-igbinary php-redis php-mongodb php-imagick php7.2-cli php7.2-curl php7.2-gd php7.2-imap php7.2-intl php7.2-mysql php7.2-opcache php-memcached php-memcache php7.2-bcmath php7.2-mbstring php7.2-soap php7.2-xml php7.2-zip apache2 openssl wget unzip whois composer htop supervisor mysql-client-core-5.7

curl -sL https://deb.nodesource.com/setup_9.x | sudo -E bash -
sudo apt-get install -y nodejs

curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
sudo apt-get update && sudo apt-get install yarn

```

## Deployment

