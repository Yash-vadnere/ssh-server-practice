# Linux Practice Log

## 1. SSH Connection
- Installed openssh-server on Kubuntu (Server)
- Connected from Xubuntu (Client) to Server
- Command: ssh yash@192.168.1.26

## 2. User Management
- Created user: sudo useradd -m rahul
- Set password: sudo passwd rahul
- Deleted user: sudo userdel -r rahul

## 3. Group Management
- Created group: sudo groupadd developers
- Added user to group: sudo useradd -m -G developers rahul

## 4. File Permissions
- Changed permissions: chmod 755 file.txt
- Changed owner: sudo chown rahul file.txt
- Changed owner+group: sudo chown rahul:developers file.txt

## 5. SCP File Transfer
- Transferred file: scp testfile.txt yash@192.168.1.26:/home/yash/ 
