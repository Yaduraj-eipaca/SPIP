<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2006                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

// pour compatibilite. Remplacer par spip_action.php?action=redirect + args

define ('_SPIP_REDIRECT', 1);
include ("ecrire/inc_version.php3");
include_ecrire("inc_spip_action_redirect.php");
spip_action_redirect_dist();
?>
