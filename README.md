# Conciergerie76

*Note : La dernière version du projet est sur la branche `dev``.*

Conciergerie76 est un projet Symfony fonctionnant avec WAMP pour le moment et avec un serveur LAMP sous Debian par la suite.
La derniere vertio du prjet est sut la branch dev
Pour apporter des modifications, vous devez d'abord forker ce repository.


## Fonctionnalités
- Page d'accueil avec barre de navigation
- Intégration de Leaflet et OpenStreetMap pour une carte interactive
- Localisation de l'utilisateur sur la carte



## Bibliothèque de cartographie
Le projet utilise **Leaflet.js**, une bibliothèque légère et gratuite, recommandée pour OpenStreetMap.

## Fonctionnalités implémentées 🎯
✅ Localisation de l'utilisateur via la géolocalisation (bouton "Trouver ma position")
✅ Possibilité de cliquer sur la carte pour définir une destination
✅ Calcul et affichage de la distance en mètres entre deux points
✅ Affichage de la distance dans une popup
✅ Traçage d'une ligne bleue entre l'utilisateur et la destination

## Parcours utilisateur 🎨
1️⃣ L'utilisateur clique sur "Trouver ma position" → Un marqueur apparaît sur sa position actuelle.
2️⃣ L'utilisateur clique sur un emplacement de la carte → Un marqueur apparaît à cet endroit.
3️⃣ Une ligne est tracée entre les deux points 📍➡️📍
4️⃣ Une popup apparaît sur le marqueur de destination affichant la distance en mètres.


