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
+---------------------------------------------------------------+ 
! Notebook - Schulnetz 10.x.x.x und Privates Netz 192.168.69.1  ! 
! Port: 8080 (192.158.69.51:80)                                 ! 
!                                                               ! 
!    +--------------------+          +---------------------+    ! 
!    ! Web Server         !          ! Datenbank Server    !    ! 
!    ! Host: web01        !          ! Host: db01          !    ! 
!    ! IP: 192.168.69.51  ! <------> ! IP: 192.168.69.50   !    ! 
!    ! Port: 80           !          ! Port 3306           !    ! 
!    ! Nat: 8080          !          ! Nat: -              !    ! 
!    +--------------------+          +---------------------+    ! 
!                                                               ! 
+---------------------------------------------------------------+

```
**Vagrant-Befehle**  
*vagrant init*              - Erstellt ein Vagrantfile im aktuellen Verzeichnis.  
*vagrant up*                - Setzt die VMs basierend auf dem Vagrantfile auf.  
*vagrant ssh [vmname]*      - Stellt eine sichere Verbindung mit der entsprechenden VM her.  
*vagrant global-status*     - Zeigt alle bekannten vorhandenen Vagrant-Umgebungen an.  
*vagrant halt*              - Stoppt alle laufenden VMs.  
*vagrant destroy*           - Löscht alle vorhandenen VMs.  

**Einrichten des Webservers mit bestehender VM aus Vagrant-Cloud** 
1. Mit dem Befehl *vagrant init* ein Vagrantfile im gewünschten Verzeichnis erzeugen. Ich habe dies direkt im LocalRepository gemacht. Dies ermöglicht mir bei Visual Studio Code unkompliziert zwischen Doku und Vagrantfile zu switchen.  
2. Gewünschtes OS von der [Vagrant-Cloud](https://app.vagrantup.com/boxes/search?provider=virtualbox) wählen, zum Beispiel [Ubuntu 16.04 LTS](https://app.vagrantup.com/ubuntu/boxes/xenial64).