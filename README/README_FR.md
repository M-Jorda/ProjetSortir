# ProjetSortir

## Liens
- [English version](README.md)
- [Versión en Español](README/README_ES.md)

## Description
La société ENI souhaite développer pour ses stagiaires actifs ainsi que ses anciens stagiaires une plateforme web leur permettant d’organiser des sorties. La plateforme est privée et l’inscription sera gérée par le ou les administrateurs. Les sorties ainsi que les participants sont rattachés à un campus pour permettre une organisation géographique des événements.

## Problème
Le grand nombre de stagiaires et leur répartition sur différents campus ne facilite pas l'organisation d’événements ou de sorties. Les problèmes identifiés sont :
- Il n’existe pas de canal de communication officiel pour proposer ou consulter les sorties.
- Les outils actuels ne permettent pas de gérer les invitations en fonction de la situation géographique ou des intérêts des stagiaires, ni de gérer le nombre d’invités, ni la date limite d’inscription.

Une solution réussie consisterait à permettre l’organisation de ces sorties et à anticiper le nombre de participants, le lieu de la sortie et autres informations nécessaires pour le bon déroulement de l’activité.

## Objectif
Monter une plateforme web destinée aux stagiaires en formation ainsi qu’aux anciens stagiaires, permettant l’organisation de sorties sur le temps hors formation.

## Technologies utilisées
- Symfony
- PHP
- Gestion de BDD (MySQL)
- Wamp
- PhpMyAdmin
- HTML/CSS
- Bootstrap
- Javascript
- Twig
- GitHub
- PhpStorm

## Diagrammes
- **Diagramme de cas d'utilisation**: ![Use Case Diagram](README/UseCaseDiagram.png)
- **Diagramme de classe**: ![Class Diagram](README/ClassDiagram.png)

## Utilisation
Avec cette application, les utilisateurs peuvent :

- Créer une sortie
- Créer un lieu
- Modifier ou supprimer une sortie créée par l'utilisateur
- Voir et rejoindre une sortie créée par un autre utilisateur
- Faire une recherche avec plusieurs filtres fonctionnels
- Voir le profil des autres utilisateurs
- Voir et modifier son propre profil
- Modifier son mot de passe
- Se connecter
- Fonctionnalités

Pour les administrateurs :

- Toutes les fonctionnalités des utilisateurs
- Ajouter une nouvelle ville
- Ajouter un nouveau campus
- Ajouter de nouveaux membres
- Gérer les villes (supprimer)
- Gérer les campus (supprimer)
- Gérer les membres (modifier, supprimer, bloquer, accéder aux informations)
- Faire une recherche avec un filtre de nom pour chaque entité pouvant être gérée.

## Screenshots
Voici quelques captures d'écran pour montrer le résultat de l'application :
- **Ajouter un utilisateur**: ![Add User](README/AddUser.png)
- **Gérer les utilisateurs**: ![Manage Usem](README/ManageUser.png)
- **Panneau d'administrateur**: ![Admin Panel](README/AdminPanel.png)
- **Ajouter et gérer les campus**: ![Add & Manage Campus](README/ManageCampus.png)
- **Ajouter et gérer les villes**: ![Add & Manage City](README/ManageCity.png)

## Licences
Ce projet est sous la licence ENI-informatique
