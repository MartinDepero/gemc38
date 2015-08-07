<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'u338969039_gemc');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'u338969039_gemc');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '2310014014');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ',VQxQLnKJ8[>-~+Dt7c$EkC>Lq*%Q+n7+Zw.Eqh.@AxoOP+&H<c-CGH Bn?;avV+');
define('SECURE_AUTH_KEY',  '>]q3z_Cc]U^K)R%2g4`y_{%0Al^+EjG_#{U&0luj/uD!T+&p6K#I)Lq<D9+|8)|1');
define('LOGGED_IN_KEY',    'T?y96{naUX0<9[y3CrfRoe+c|QMRHVGIF>beK<p9SLo;TKXG2!Vt:B1:-X`/L:GN');
define('NONCE_KEY',        '8/;ct1D8b}QUw^D!bzJtQ)$._?a%Y:f*SxShJ!t*gWaQP1#H{eeP:!KYG$KsGG|k');
define('AUTH_SALT',        'Z 3f[tt-)2p83//05}lH-KxAb!y|_mr3?&Fs?,de[C0u#*(-7dm Id~tLOmvK-4C');
define('SECURE_AUTH_SALT', '%Wlqn7+YGtZc1)*ys}RQvoOKCs+{>1poo224*D[7#N=|o],kq<L71Y4ID%EBJ_.2');
define('LOGGED_IN_SALT',   'hRamz/m6ng+q]h~e{cB==gSDqu<DRFwd+$>0S{@Zf;H46hVKeQDqdm++[+YZGXVC');
define('NONCE_SALT',       'hLqBQ+:x <6v.J7fp%b`Milu~_VB*,ovcQa%+r4D6Ao.,CxxTRE6jxA @.>.K%lC');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');