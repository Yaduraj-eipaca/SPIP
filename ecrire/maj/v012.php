<?php

/***************************************************************************\
 *  SPIP, Système de publication pour l'internet                           *
 *                                                                         *
 *  Copyright © avec tendresse depuis 2001                                 *
 *  Arnaud Martin, Antoine Pitrou, Philippe Rivière, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribué sous licence GNU/GPL.     *
 *  Pour plus de détails voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

/**
 * Gestion des mises à jour de SPIP, versions 1.2*
 *
 * @package SPIP\Core\SQL\Upgrade
 **/
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Mises à jour de SPIP n°012
 *
 * @param float $version_installee Version actuelle
 * @param float $version_cible Version de destination
 **/
function maj_v012_dist($version_installee, $version_cible) {
	// Correction de l'oubli des modifs creations depuis 1.04
	if (upgrade_vers(1.204, $version_installee, $version_cible)) {
		sql_query("ALTER TABLE spip_articles ADD accepter_forum VARCHAR(3) NOT NULL");
		sql_query("ALTER TABLE spip_forum ADD id_message bigint(21) NOT NULL");
		sql_query("ALTER TABLE spip_forum ADD INDEX id_message (id_message)");
		sql_query("ALTER TABLE spip_auteurs ADD en_ligne datetime DEFAULT '0000-00-00 00:00:00' NOT NULL");
		sql_query("ALTER TABLE spip_auteurs ADD imessage VARCHAR(3) not null");
		sql_query("ALTER TABLE spip_auteurs ADD messagerie VARCHAR(3) not null");
		maj_version(1.204);
	}

	if (upgrade_vers(1.207, $version_installee, $version_cible)) {
		sql_query("ALTER TABLE spip_rubriques DROP INDEX id_rubrique");
		sql_query("ALTER TABLE spip_rubriques ADD INDEX id_parent (id_parent)");
		sql_query("ALTER TABLE spip_rubriques ADD statut VARCHAR(10) NOT NULL");
		// Declencher le calcul des rubriques publiques
		include_spip('inc/rubriques');
		calculer_rubriques();
		maj_version(1.207);
	}

	if (upgrade_vers(1.208, $version_installee, $version_cible)) {
		sql_query("ALTER TABLE spip_auteurs_messages CHANGE forum vu CHAR(3) NOT NULL");
		sql_query("UPDATE spip_auteurs_messages SET vu='oui'");
		sql_query("UPDATE spip_auteurs_messages SET vu='non' WHERE statut='a'");

		sql_query("ALTER TABLE spip_messages ADD id_auteur bigint(21) NOT NULL");
		sql_query("ALTER TABLE spip_messages ADD INDEX id_auteur (id_auteur)");
		$result = sql_query("SELECT id_auteur, id_message FROM spip_auteurs_messages WHERE statut='de'");
		while ($row = sql_fetch($result)) {
			$id_auteur = $row['id_auteur'];
			$id_message = $row['id_message'];
			sql_query("UPDATE spip_messages SET id_auteur=$id_auteur WHERE id_message=$id_message");
		}

		sql_query("ALTER TABLE spip_auteurs_messages DROP statut");
		maj_version(1.208);
	}

	if (upgrade_vers(1.209, $version_installee, $version_cible)) {
		sql_query("ALTER TABLE spip_syndic ADD maj TIMESTAMP");
		sql_query("ALTER TABLE spip_syndic_articles ADD maj TIMESTAMP");
		sql_query("ALTER TABLE spip_messages ADD maj TIMESTAMP");
		maj_version(1.209);
	}

	if (upgrade_vers(1.210, $version_installee, $version_cible)) {
		sql_query("ALTER TABLE spip_messages DROP page");

		stripslashes_base('spip_articles', array('surtitre', 'titre', 'soustitre', 'descriptif', 'chapo', 'texte', 'ps'));
		stripslashes_base('spip_auteurs', array('nom', 'bio', 'nom_site'));
		stripslashes_base('spip_breves', array('titre', 'texte', 'lien_titre'));
		stripslashes_base('spip_forum', array('titre', 'texte', 'auteur', 'nom_site'));
		stripslashes_base('spip_messages', array('titre', 'texte'));
		stripslashes_base('spip_mots', array('type', 'titre', 'descriptif', 'texte'));
		stripslashes_base('spip_petitions', array('texte'));
		stripslashes_base('spip_rubriques', array('titre', 'descriptif', 'texte'));
		stripslashes_base('spip_signatures', array('nom_email', 'nom_site', 'message'));
		stripslashes_base('spip_syndic', array('nom_site', 'descriptif'));
		stripslashes_base('spip_syndic_articles', array('titre', 'lesauteurs'));
		maj_version(1.210);
	}
}
