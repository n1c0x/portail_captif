# portail_captif
Un portail captif crée dans le cadre de mes cours

## To Do
Ajout d'un nouveau client

- [x]	ajout dans le LDAP

Connexion d'un client

- [x]	ajout de @ IP et @ MAC dans un fichier
- [x] création d'une règle iptables permettant la connexion vers internet => at supprime la règle après 24h

Suppression d'un client après la date fixée

- [ ] suppression du LDAP

Supervision

- [x] installation ntopng

Redirection après connexion

- [x]	vers google.fr


## Utilisation
### Création des utilisateurs
Interface d'administration afin de créer les utilisateurs autorisés à accéder au réseau
![Portail d'administration](htt://docs.edenpulse.com/portail_admin.png)

Interface d'authentification des utilisateurs
![Portail d'authentification](htt://docs.edenpulse.com/portail_authent.png)

Ce portail captif utilise le programme hostapd pour créer un point d'accès libre d'accès (sans mot de passe). Suite à la connexion au point d'accès, dnsmasq attribue une adresse IPv4 et IPv6 aux clients connectés. Une règle iptables redirige les clients connectés mais pas encore authentifiés vers une page de connexion. Avant d'avoir un accès, les utilisateurs doivent d'abord être ajoutés à la base d'utilisateurs par un administrateur. 
