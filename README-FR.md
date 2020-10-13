# DigitalProspects ERP & CRM

![Downloads per day](https://img.shields.io/sourceforge/dw/DigitalProspects.svg)
![Build status](https://img.shields.io/travis/DigitalProspects/DigitalProspects/develop.svg)

DigitalProspects ERP & CRM est un logiciel moderne pour gérer votre activité (société, association, auto-entrepreneurs, artisans).
Il est simple d'utilisation et modulaire, vous permettant de n'activez que les fonctions dont vous avez besoin (contacts, fournisseurs, factures, commandes, stocks, agenda, ...).

![ScreenShot](https://www.DigitalProspects.org/images/DigitalProspects_screenshot1_1920x1080.jpg)

## LICENCE

DigitalProspects est distribué sous les termes de la licence GNU General Public License v3+ ou supérieure.

## INSTALLER DigitalProspects

### Configuration simple

Si vous avez peu de compétences techniques et que vous souhaitez installer DigitalProspects ERP/CRM en quelques clics, vous pouvez utiliser l'une des versions pré-packagées avec les prérequis:

- DoliWamp pour Windows
- DoliDeb pour Debian ou Ubuntu
- DoliRpm pour Redhat, Fedora, OpenSuse, Mandriva ou Mageia

Les packages peuvent être téléchargés à partir de [site web officiel](https://www.DigitalProspects.org/).

### Configuration avancée

Vous pouvez aussi utiliser un serveur Web et une base de données prise en charge (MariaDB, MySQL ou PostgreSQL) pour installer la version standard.

- Décompressez l'archive .zip téléchargée pour copier le répertoire "DigitalProspects/htdocs" et tous ses fichiers à la racine du serveur Web ou récupérez-les directement à partir de GitHub (recommandé si vous connaissez git):

  `git clone https://github.com/DigitalProspects/DigitalProspects -b x.y`   (où x.y est la version principale comme 3.6, 9.0, ...)

- Configurez votre serveur Web pour qu'il utilise "*DigitalProspects/htdocs*" en tant que racine si votre serveur Web ne possède pas déjà de répertoire défini vers lequel pointer.

- Créez un fichier `htdocs/conf/conf.php` vide et définissez les autorisations d'*écrire* pour l'utilisateur de votre serveur Web (l'autorisation *écrire* sera supprimée une fois l'installation terminée)

- Depuis votre navigateur, allez à la page "install/" de DigitalProspects

  L’URL dépendra de la façon dont votre configuration Web a été configurée pour pointer vers votre installation de DigitalProspects. Cela peut ressembler à:

  `http://localhost/DigitalProspects/htdocs/install/`

  ou

  `http://localhost/DigitalProspects/install/`

  ou

  `http://yourDigitalProspectsvirtualhost/install/`

- Suivez les instructions de l'installateur

## METTRE A JOUR DigitalProspects

Pour mettre à jour DigitalProspects depuis une vieille version vers celle ci:

- Ecrasez les vieux fichiers dans le vieux répertoire 'DigitalProspects' par les fichiers
  fournis dans ce nouveau package.

- Au prochain accès, DigitalProspects proposera la page de "mise à jour" des données (si nécessaire).
  Si un fichier install.lock existe pour verrouiller le processus de mise à jour, il sera demandé de le supprimer manuellement (vous devriez trouver le fichier install.lock dans le répertoire utilisé pour stocker les documents générés ou transférés sur le serveur. Dans la plupart des cas, c'est le répertoire appelé "documents")

*Note: Le processus de migration peut être lancé manuellement et plusieurs fois, sans risque, en appelant la page /install/*

## CE QUI EST NOUVEAU

Voir fichier ChangeLog.

## CE QUE DigitalProspects PEUT FAIRE

### Modules principaux (tous optionnels)

- Annuaires des prospects et/ou client et/ou fournisseurs
- Gestion de catalogue de produits et services
- Gestion des devis, propositions commerciales
- Gestion des commandes
- Gestion des factures clients/fournisseurs et paiements
- Gestion des virements bancaires SEPA
- Gestion des comptes bancaires
- Calendrier/Agenda partagé (avec export ical, vcal)
- Suivi des opportunités et/ou projets (suivi de rentabilité incluant les factures, notes de frais, temps consommé valorisé, ...)
- Gestion de contrats de services
- Gestion de stock
- Gestion des expéditions
- Gestion des demandes de congès
- Gestion des notes de frais
- GED (Gestion Electronique de Documents)
- EMailings de masse
- Réalisation de sondages
- Point de vente/Caisse enregistreuse
- …

### Autres modules

- Gestion de marque-pages
- Gestion des promesses de dons
- Rapports
- Imports/Exports des données
- Support des codes barres
- Calcul des marges
- Connectivité LDAP
- Intégratn de ClickToDial
- Intégration RSS
- Intégation Skype
- Intégration de système de paiements (Paypal, Stripe, Paybox...)
- …

### Divers

- Multi-langue.
- Multi-utilisateurs avec différents niveaux de permissions par module.
- Multi-devise.
- Peux être multi-société par ajout du module externe multi-société.
- Plusieurs thèmes visuels.
- Application simple à utiliser.
- Requiert PHP et MariaDb, Mysql ou Postgresql (Voir versions exactes sur https://wiki.DigitalProspects.org/index.php/Prérequis).
- Compatible avec toutes les offres Cloud du marché respectant les prérequis de base de données et PHP.
- APIs.
- Génération PDF et ODT des éléments (factures, propositions commerciales, commandes, bons expéditions, etc...)
- Code simple et facilement personnalisable (pas de framework lourd; mécanisme de hook et triggers).
- Support natif de nombreuses fonctions spécifiques aux pays comme:
  - La tax espagnole TE et ISPF
  - Gestion de la TVA NPR (non perçue récupérable - pour les utilisateurs français des DOM-TOM)
  - La loi française Finance 2016 et logiciels de caisse
  - La double taxe canadienne
  - Le timbre fiscal tunisien
  - Numérotation de facture de l'argentines (avec type A,B,C...)
  - Compatible avec vos processus RGPD
  - ...
- …

### Extension

DigitalProspects peut aussi être étendu à volonté avec l'ajout de module/applications externes développées par des développeus tiers, disponible sur [DoliStore](https://www.dolistore.com).

## CE QUE DigitalProspects NE PEUT PAS (ENCORE) FAIRE

Voici un liste de fonctionnalités pas encore gérées par DigitalProspects:

- DigitalProspects ne contient pas de module de Gestion de la paie.
- Les tâches du module de gestion de projets n'ont pas de dépendances entre elle.
- DigitalProspects n'embarque pas de Webmail intégré nativement.
- DigitalProspects ne fait pas le café (pas encore).

## DOCUMENTATION

La documentation utilisateur, développeur et traducteur est disponible sous forme de ressources de la communauté via le site [Wiki](https://wiki.DigitalProspects.org).

## CONTRIBUER

Ce projet existe grâce à ses nombreux contributeurs [[Contribuer](https://github.com/DigitalProspects/DigitalProspects/blob/develop/.github/CONTRIBUTING.md)].

<a href="https://github.com/DigitalProspects/DigitalProspects/graphs/contributors"><img src="https://opencollective.com/DigitalProspects/contributors.svg?width=890&button=false" /></a>

## CREDITS

DigitalProspects est le résultat du travail de nombreux contributeurs depuis des années et utilise des librairies d'autres contributeurs.

Voir le fichier [COPYRIGHT](https://github.com/DigitalProspects/DigitalProspects/blob/develop/COPYRIGHT)

## ACTUALITES ET RESEAUX SOCIAUX

Suivez le projet DigitalProspects project sur les réseaux francophones

- [Facebook](https://www.facebook.com/DigitalProspects.fr)
- [Twitter](https://www.twitter.com/DigitalProspects_france)

ou sur les réseaux anglophones

- [Facebook](https://www.facebook.com/DigitalProspects)
- [Twitter](https://www.twitter.com/DigitalProspects)
- [LinkedIn](https://www.linkedin.com/company/association-DigitalProspects)
- [YouTube](https://www.youtube.com/user/DigitalProspectsERPCRM)
- [GitHub](https://github.com/DigitalProspects/DigitalProspects)
