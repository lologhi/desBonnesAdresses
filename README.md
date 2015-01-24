desBonnesAdresses
==============

Code source de http://desBonnesAdresses.fr

Un bête json qui me permet de fuir Google Maps (parce que je ne peux ajouter de commentaire à mes adresses préférées)

Et puis comme le système de GitHub est très lent, et qu'il repose complètement sur Leaflet.js et Mapbox, je le refait sans passer par chez eux.

Pour le moment j'ai préféré OpenLayers à Mapbox.


### Pour régler les problèmes de droit d'accès

```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```

### Pour l'import/export des données

```
mongo bonnesadresses --eval 'db.Adresse.remove({}) '
sudo mongoimport -d bonnesadresses -c Adresse --file StarredPlaces.geojson --jsonArray
```

### Update process

```
git pull
php app/console cache:clear --env=prod
```

### Installation de npm/bower/grunt

```
sudo apt-get install npm
ln -s /usr/bin/nodejs /usr/bin/node
sudo npm install
bower install --allow-root
grunt
```
