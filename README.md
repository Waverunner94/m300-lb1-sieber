# Modul 300 – LB1

## Inhaltsverzeichnis
- [Modul 300 – LB1](#modul-300-%E2%80%93-lb1)
  - [Inhaltsverzeichnis](#inhaltsverzeichnis)
  - [01 - Verwendete Tools](#01---verwendete-tools)
  - [02 - Wissenstand](#02---wissenstand)
    - [02.1 - Linux](#021---linux)
    - [02.2 - Virtualisierung](#022---virtualisierung)
    - [02.3 - Vagrant](#023---vagrant)
    - [02.4 - Versionsverwaltung](#024---versionsverwaltung)
    - [02.5 Mark Down](#025-mark-down)
    - [02.6 Systemsicherheit](#026-systemsicherheit)
  - [03 Lernschritte LB1](#03-lernschritte-lb1)


## 01 - Verwendete Tools
* Oracle VirtualBox 6.0
* Vagrant
* Visual Studio Code
* Git-Client (inkl. SSH-Key)
  
## 02 - Wissenstand

### 02.1 - Linux
Linux Grundkenntnisse sind vorhanden, war jedoch nie meine primäre Arbeitsumgebung.

### 02.2 - Virtualisierung
Erfahrung mit VMWare Workstation Player sowie VirtualBox.

### 02.3 - Vagrant
Keine Vorkenntnisse vorhanden.

### 02.4 - Versionsverwaltung
Cloud-spezifische Erfahrung mit Versionverwaltung vorhanden: Dokumente in Google Drive jeweils so versionisiert, dass alte Versionen jederzeit wiederhergestellt werden konnten.
Keine Vorkenntnisse mit Git vorhanden.

### 02.5 Mark Down
Keine Vorkenntnisse mit Mark Down vorhanden.

### 02.6 Systemsicherheit
Erfahrung mit der sicheren Aufbewahrung von sensitiven Daten, sicheres lagern von Passwörtern, Firewall, etc.

## 03 Lernschritte LB1
Die Lernschritte, die ich während der Durchführung von LB1 kontinuierlich aktualisieren werde.

**Netzwerkplan** 
```
+--------------------------------------------------------------------------------------------+
!   Notebook - Schulnetz 10.x.x.x und Privates Netz 192.168.69.1                             !   
!   Port: 8080 (192.158.69.50:80)                                                            !
!                                                                                            !
!   +--------------------+          +--------------------+          +---------------------+  !
!   ! Proxy Server       !          ! Web Server         !          ! Datenbank Server    !  !
!   ! Host: proxy        !          ! Host: web          !          ! Host: db            !  !
!   ! IP: 192.168.69.49  ! <------> ! IP: 192.168.69.50  ! <------> ! IP: 192.168.69.51   !  !
!   ! Port: 5000         !          ! Port: 80           !          ! Port 3306           !  !
!   ! Nat: 5000          !          ! Nat: -             !          ! Nat: -              !  !
!   +--------------------+          +--------------------+          +---------------------+  !
+--------------------------------------------------------------------------------------------+

```
**Vagrant-Befehle**  
*vagrant init*              - Erstellt ein Vagrantfile im aktuellen Verzeichnis.  
*vagrant up*                - Setzt die VMs basierend auf dem Vagrantfile auf / startet die vorhandenen VMs.  
*vagrant up --provision*    - Führt Änderungen basierend auf dem Vagrantfile auf die bestehende Umgebung aus.  
*vagrant ssh [vmname]*      - Stellt eine sichere Verbindung mit der entsprechenden VM her.  
*vagrant global-status*     - Zeigt alle bekannten vorhandenen Vagrant-Umgebungen an.  
*vagrant halt*              - Stoppt alle laufenden VMs.  
*vagrant destroy*           - Löscht alle vorhandenen VMs.  

**Erzeugen des Proxys im Vagrantfile**  
1. Folgende Zeilen werden in das Vagrantfile geschrieben:
```
   config.vm.define "proxy" do |proxy|
		proxy.vm.box = "ubuntu/xenial64"
		proxy.vm.hostname = "proxy"
		proxy.vm.network "private_network", ip: "192.168.69.49"
		proxy.vm.network "forwarded_port", guest:80, host:5000, auto_correct: true
		proxy.vm.provider "virtualbox" do |vb|
			vb.memory = "512"  
		end
		proxy.vm.synced_folder "proxy", "/vagrant"  
		proxy.vm.provision "shell", path: "proxy.sh"
  end
```
2. Das File proxy.sh im Verzeichnis erzeugen und folgenden Inhalt einfügen:  
```
#!/bin/bash
#
#	Proxy installieren und konfigurieren
#

ufw enable
ufw allow http
ufw allow from 192.168.69.1 to any port 22

apt-get update -y
apt-get -y install apache2


cp /vagrant/001-mysite.conf /etc/apache2/sites-available/
a2ensite 001-mysite.conf
a2enmod proxy
a2enmod proxy_http
service apache2 restart
```

**

**Erzeugen des Webservers im Vagrantfile** 
1. Mit dem Befehl *vagrant init* ein Vagrantfile im gewünschten Verzeichnis erzeugen. Ich habe dies direkt im LocalRepository (C:\Users\Severin Sieber\Desktop\m300-lb1-sieber) gemacht. Dies ermöglicht mir bei Visual Studio Code unkompliziert zwischen Doku und Vagrantfile zu switchen.  
2. Folgende Zeilen werden in das Vagrantfile geschrieben:  
  ```
   Vagrant.configure("2") do |config|
	config.vm.define "web" do |web|
		web.vm.box = "ubuntu/xenial64"
		web.vm.hostname = "web"
		web.vm.network "private_network", ip: "192.168.69.50"
		web.vm.provider "virtualbox" do |vb|
			vb.memory = "512"  
		end
		web.vm.synced_folder "src", "/var/www/html"  
		web.vm.provision "shell", path: "server.sh"
  end

  ```
3. Den Ordner "src" im Verzeichnis erstellen. Darin wird die index.html und index.php erzeugt.  
  
**Erzeugen des Datenbankservers im Vagrantfile**  
1. Folgende Zeilen werden in das Vagrantfile unterhalb der letzten geschrieben:
  ```
    config.vm.define "db" do |db|
	  	db.vm.box = "ubuntu/xenial64"
	  	db.vm.hostname = "db"
	  	db.vm.network "private_network", ip: "192.168.69.51"
	  	db.vm.provider "virtualbox" do |vb|
	  		vb.memory = "1024"  
	  	end
	  	db.vm.provision "shell", path: "db.sh"
   end
  end
  ```
2. Das File db.sh im Verzeichnis erzeugen und folgenden Inhalt einfügen:
  ```
  #!/bin/bash
#
#	Datenbank installieren und Konfigurieren
#


ufw enable
ufw allow from 192.168.69.50 to any port 3306
ufw allow from 192.168.69.1 to any port 22

apt update -y

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password secret_password'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password secret_password'

sudo apt-get install -y mysql-server

sudo sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

mysql -uroot -psecret_password <<%EOF%
	CREATE USER 'root'@'192.168.69.50' IDENTIFIED BY 'admin';
	GRANT ALL PRIVILEGES ON *.* TO 'root'@'192.168.69.50';
	FLUSH PRIVILEGES;
%EOF%

mysql -uroot -psecret_password <<%EOF%
	create database if not exists data_set;
	create user 'www-data'@'localhost' identified by 'pwd'; 
	grant usage on *.* to 'www-data'@'192.168.69.50' identified by 'pwd';
	grant all privileges on data_set.* to 'www-data'@'192.168.69.50';
	flush privileges;
	use data_set;
	create table data ( id INT PRIMARY KEY AUTO_INCREMENT, data FLOAT );
	insert into data(data) values ( 1.173 );
	insert into data(data) values ( 2.345 );
%EOF%


sudo service mysql restart
```