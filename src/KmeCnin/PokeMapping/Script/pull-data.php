<?php

/*
 * TODO
 * 
 * sudo sqlite3 /var/python/PokemonGo-Map/pogom.db .dump > /var/www/pokemap.kmecnin.net/dump/pogom.sql
 * vim /var/www/pokemap.kmecnin.net/dump/pogom.sql
 * // Remove first line
 * // Remove TRANSACTION
 * :%s/"//g
 * :wq
 * mysql -p -u root -h 127.0.0.1 poke-mapping < /var/www/pokemap.kmecnin.net/dump/pogom.sql
 */
