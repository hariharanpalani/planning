{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="span12">
			<div class="soplanning-box" style="font-size:17px">
				<table width="100%">
				<tr>
					<td><b>GESTION DES UTILISATEURS</b></td>
					<td align="right">
						<a href="../user_list.php" class="btn btn-sm btn-default">Gestion des utilisateurs</a>

						<a href="index.php" class="btn btn-sm btn-default">Retour</a>
					</td>
				</tr>
				</table>
				<b></b>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div class="soplanning-box margin-top-10">
				Les utilisateurs repr�sentent la liste des personnes affich�es dans l'outil, ainsi que les personnes pouvant se connecter � l'outil pour consulter le planning.
				<br>
				Vous pouvez cr�er autant d'utilisateurs que vous le souhaitez, chacun aura des droits sp�cifiques.
				<br>
				Une t�che est syst�matiquement reli�e � un projet et un utilisateur. Avant de cr�er une t�che, assurez-vous que l'utilisateur correspondant soit d�j� cr��.

				<h3>Mon profil</h3>
				Chaque utilisateur peut �diter son profil en cliquant sur son nom en haut � droite de la barre de menu. Cet interface lui permet de modifier son email, son mot de passe, l'activation des notifications par email, ainsi que ses pr�f�rences d'affichage du planning. Les autres informations ne sont modifiables que par les personnes ayant les droits de gestion des utilisateurs.

				<h3>Compte admin</h3>
				L'utilisateur "admin" existant par d�faut dans l'outil n'est pas obligatoire : ce compte n'est pas le seul qui peut avoir tous les droits, vous pouvez sans probl�me le supprimer et cr�er autant d'utilisateurs avec tous les droits que vous souhaitez. Ceci �vite d'avoir un seul compte qui peut effectuer toutes les op�rations.

				<h3>Liste des utilisateurs</h3>
				La liste des utilisateurs reprend l'ensemble des personnes enregistr�es dans l'outil. Cet interface vous permet de cr�er / modifier / supprimer un utilisateur, et consulter des infos basiques de chaque compte utilisateur.
				<br>
				Vous pouvez trier cette liste en cliquant sur les noms des colonnes, et filtrer la liste par �quipe ou par moteur de recherche.
				<br>
				<b>Important</b> : la suppression d'un utilisateur entrainera la suppression de toutes les t�ches de cet utilisateur.

				<h3>Les principales informations d'un utilisateur</h3>
				L'identifiant est une chaine de caract�res qui est unique pour chaque utilisateur. Cet identifiant est celui qui affich� dans le planning. Le but est de d�finir un identifiant repr�sentant facilement la personne concern�e (initiales, num�ro d'employ�, etc). Cet identifiant ne peut �tre chang� par la suite.
				<br>
				Le champ "nom" est le seul autre champ obligatoire pour la cr�ation d'un utilisateur, ce nom sera affich� dans le r�capitulatif complet sous le planning.
				<br>
				Le champ "email" est optionnel et servira uniquement si vous activez les notifications par email (voir plus bas).
				<br>
				Le "login" et "mot de passe" sont �galement facultatif, et ne seront � renseigner que si vous souhaitez donner acc�s au planning � l'utilisateur concern�. Chaque login est unique. Veuillez �galement � renseigner des mots de passe suffisamment complexe (8 caract�res, avec plusieurs casses).

				<h3>L'�quipe</h3>
				Vous pouvez rattacher un utilisateur � une et une seule �quipe. Ceci permettra ensuite de d�finir des droits en rapport avec l'�quipe � laquelle il appartient. Voir aide sur <a href="equipes.php">les �quipes</a> pour g�rer la liste des �quipes. Voir le module de <a href="../user_groupes.php.php">gestion des �quipes</a>

				<h3>D�finition des droits</h3>
				<b>Gestion des utilisateurs</b> : donne droit � l'ensemble des actions possibles sur les comptes utilisateurs. Il n'existe pas de droit interm�diaire permettant de modifier partiellement les comptes. Voir le module de <a href="../user_list.php">gestion des projets</a>.
				<br><br>
				<b>Gestion des projets</b> : donne droit � l'ensemble des actions possibles sur la fiche d'un projet. �a ne donne en revancha aucun droit sur les t�ches associ�es � ces projets. Il n'existe pas de droit interm�diaire permettant de modifier partiellement les projets. Voir le module de <a href="../projets.php">gestion des projets</a>.
				<br><br>
				<b>Gestion des groupes de projets</b> : permet de g�rer les groupes de projets, auxquels peuvent �tre rattach�s les projets.  Voir le module de <a href="../groupe_list.php">gestion des groupes de projets</a>.
				<br><br>
				<b>Modification du planning</b> : ce droit d�termine ce que l'utilisateur peut modifier dans le planning, ce n'est pas n�cessairement l'ensemble des t�ches qu'il voit (voir droit ci-dessous). L'acc�s en <i>lecture seule</i> ne lui permet aucune modification. L'option <i>Uniquement pour les projets dont il est le propri�taire</i> lui permet de modifier uniquement ces projets si il a �t� d�fini comme propri�taire dans la fiche d'un projet (voir <a href="../projets.php">gestion des projets</a>). L'option <i>T�ches sur lesquelles il est assign� ou ses propres projets</i> lui permet de modifier les t�ches o� il est propri�taire du projet ainsi que les t�ches qui lui sont assign�es sur d'autres projets. Enfin l'option <i>pour tous les projets</i> lui permet de modifier l'ensemble des t�ches des projets qu'il peut voir.
				<br><br>
				<b>Vue du planning</b> : l'option <i>tous les projets</i> permet � l'utilisateur de voir l'ensemble des projets pr�sents dans l'outil (sauf s'il a volontairement appliqu� un filtre au planning). L'option <i>Uniquement les projets de l'�quipe</i> lui permet de consulter uniquement les projets couverts par au moins une personne de la m�me �quipe que cet utilisateur. On ne peut pas d�finir une liste pr�cise de projets pour ce droit, il suffit d'ajouter au moins une t�che sur un nouveau projet pour que toutes les personnes de la m�me �quipe puisse acc�der � ce projet. L'option <i>Uniquement les projets dont il est propri�taire ou assign�</i> permet de voir toutes les t�ches des projets dont l'utilisateur est propri�taire, ainsi que les t�ches sur lesquelles il est assign�. L'option <i>Uniquement les t�ches qui lui sont assign�</i> permet de restreindre l'affichage � ses propres t�ches uniquement.
				<br><br>
				<b>Gestion des lieux</b> : donne droit � l'ensemble des actions possibles sur la liste des lieux disponibles. Ce droit s'applique uniquement si ce module optionnel est activ� dans les param�tres de SOPlanning. Voir le module de <a href="../lieux.php">gestion des lieux</a>.
				<br><br>
				<b>Gestions des ressources</b> donne droit � l'ensemble des actions possibles sur la liste des ressources/mat�riels disponibles. Ce droit s'applique uniquement si ce module optionnel est activ� dans les param�tres de SOPlanning. Voir le module de <a href="../ressources.php">gestion des ressources</a>.
				<br><br>
				<b>Gestion des param�tres</b> : donne acc�s � la modification de l'ensemble des param�tres de SOPlanning.
				<br><br>
				<b>Acc�s aux statistiques</b> : donne acc�s aux diff�rents modules de statistiques disponibles. Les statistiques sont globales et ne sont pas limit�es aux droits de vue du planning d�finis pour l'utilisateur.

				<h3>Personnalisation et notifications</h3>
				Vous pouvez rendre l'utilisateur <i>visible</i> ou non dans le planning. Cela ne l'emp�chera pas de pouvoir se connecter et consulter le planning, en revanche il sera impossible de lui attribuer des t�ches.
				<br>
				Vous pouvez �galement d�terminer si cet utilisateur recevra des <i>notifications par email</i>. Un email lui sera envoy� pour toute t�che cr��e qui lui sera assign�e, ou pour toute modification ou suppression d'une t�che qui lui est assign�e.
				<br>
				vous pouvez enfin d�finir la <i>couleur</i> de l'utilisateur. Cette couleur sert de rep�re visuel dans le planning (en vue par personne). Si vous r�partissez les couleurs entre vos diff�rents utilisateurs, vous aurez ainsi un aper�u en un coup d'oeil du planning de chacun sans avoir besoin de survoler chaque t�che pour voir le d�tail.

				<h3>Infos personnelles</h3>
				Ces champs sont facultatifs et vous permettent de rajouter des informations compl�mentaires � propos de chaque utilisateur. Ces informations ne sont pas visibles dans le planning et sont accessibles uniquement aux personnes ayant les droits de gestion des utilisateurs.
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}