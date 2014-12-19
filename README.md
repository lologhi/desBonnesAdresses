desBonnesAdresses
==============

Code source de http://desBonnesAdresses.fr

Un bête json qui me permet de fuir Google Maps (parce que je ne peux ajouter de commentaire à mes adresses préférées)

Et puis comme le système de GitHub est très lent, et qu'il repose complètement sur Leaflet.js et Mapbox, je le refait sans passer par chez eux.

Pour le moment j'ai préféré OpenLayers à Mapbox.

```
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```
