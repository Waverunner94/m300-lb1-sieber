# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.

Vagrant.configure("2") do |config|

	
	config.vm.define "database" do |db|
		db.vm.box = "ubuntu/xenial64"
		db.vm.hostname = "m300-lb1-db"
		db.vm.network "private_network", ip: "192.168.69.50"
		# MySQL Port nur im Private Network sichtbar
		# db.vm.network "forwarded_port", guest:3306, host:3306, auto_correct: false
		# wird nicht benötigt, da ich die Datenbank nicht auf dem Host system brauche 
		# nur die Webschnittstelle wird weitergeleitet. 
			db.vm.provider "virtualbox" do |vb|
				vb.memory = "512"
			end	
		db.vm.provision "shell", inline: <<-SHELL
		
			#DEBUG ON
			set -o xtrace
			sudo apt-get update
			sudo apt-get -y install debconf-utils 
			# root Password setzen, damit kein Dialog erscheint und die Installation haengt!
			sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password admin'
			sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password admin'
			
			# Installation
			
			sudo apt-get -y install ufw

			#FIREWALL
			#SSH port 22 für host Ip erlauben
			#sudo ufw allow from 10.71.10.xxx to any port 22
			#Port 3306 für MySQL für den Webserver öffnen
			sudo ufw allow from 192.168.69.51 to any port 3306
			#mysql-server installieren
			sudo apt-get install -y mysql-server mysql-client
			
			# MySQL Port oeffnen
			sudo sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
			
			# User für Remote Zugriff erlauben aber nur für den Webserver 192.168.69.51
			mysql -u root -p admin 
				CREATE USER 'root'@'192.168.69.51' IDENTIFIED BY 'admin';
				GRANT ALL PRIVILEGES ON *.* TO 'root'@'192.168.69.51';
			
			
			# Restart fuer Konfigurationsaenderung
			sudo service mysql restart
			
			# Test ob MySQL Server laueft - ansonsten Abbruch!!!
			curl -f http://localhost:3306 >/dev/null 2>&1 && { echo "MySQL up"; } || { echo "Error: MySQL down"; exit 1; }
		SHELL
	end
		
	config.vm.define "web" do |web|
		web.vm.box = "ubuntu/xenial64"
		web.vm.hostname = "m300-lb1-web"
		web.vm.network "private_network", ip: "192.168.69.51"
			web.vm.network "forwarded_port", guest:80, host:8080, auto_correct: true
			web.vm.provider "virtualbox" do |vb|
				vb.memory = "512"
			end
		web.vm.synced_folder ".", "/var/www/html"	
		web.vm.provision "shell", inline: <<-SHELL
			
		# Debug ON!!!
			set -o xtrace	
			sudo apt-get update
			sudo apt-get -y install ufw
			sudo ufw enable
			#SSH port 22 für host Ip erlauben
			#sudo ufw allow from 10.71.10.xxx to any port 22
			
			#Reverse Proxy installieren
			sudo apt-get -y install libapache2-mod-proxy-html
			sudo apt-get -y install libxml2-dev
			
			#Reverse Proxy module unter Apache aktivieren
			sudo a2enmod proxy
			sudo a2enmod proxy_html
			sudo a2enmod proxy_http 
			
			#DB schnittstelle installieren
			sudo apt-get -y install debconf-utils apache2 nmap
			sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password admin'
			sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password admin'
			sudo apt-get -y install php libapache2-mod-php php-curl php-cli php-mysql php-gd mysql-client  
			# Admininer SQL UI 
			sudo mkdir /usr/share/adminer
			sudo wget "http://www.adminer.org/latest.php" -O /usr/share/adminer/latest.php
			sudo ln -s /usr/share/adminer/latest.php /usr/share/adminer/adminer.php
			echo "Alias /adminer.php /usr/share/adminer/adminer.php" | sudo tee /etc/apache2/conf-available/adminer.conf
			sudo a2enconf adminer.conf 
			sudo service apache2 restart 
			# Test ob Apache Server laueft - ansonsten Abbruch!!!
			curl -f http://localhost >/dev/null 2>&1 && { echo "Apache up"; } || { echo "Error: Apache down"; exit 1; }
			echo '127.0.0.1 localhost m300_lb1_web\ 192.168.69.50 m300_lb1_db' > /etc/hosts
		SHELL
	end
end