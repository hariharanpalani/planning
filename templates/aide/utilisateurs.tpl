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
				Les utilisateurs représentent la liste des personnes affichées dans l'outil, ainsi que les personnes pouvant se connecter à l'outil pour consulter le planning.
				<br>
				Vous pouvez créer autant d'utilisateurs que vous le souhaitez, chacun aura des droits spécifiques.
				<br>
				Une tâche est systématiquement reliée à un projet et un utilisateur. Avant de créer une tâche, assurez-vous que l'utilisateur correspondant soit déjà créé.

				<h3>Mon profil</h3>
				Chaque utilisateur peut éditer son profil en cliquant sur son nom en haut à droite de la barre de menu. Cet interface lui permet de modifier son email, son mot de passe, l'activation des notifications par email, ainsi que ses préférences d'affichage du planning. Les autres informations ne sont modifiables que par les personnes ayant les droits de gestion des utilisateurs.

				<h3>Compte admin</h3>
				L'utilisateur "admin" existant par défaut dans l'outil n'est pas obligatoire : ce compte n'est pas le seul qui peut avoir tous les droits, vous pouvez sans problème le supprimer et créer autant d'utilisateurs avec tous les droits que vous souhaitez. Ceci évite d'avoir un seul compte qui peut effectuer toutes les opérations.

				<h3>Liste des utilisateurs</h3>
				La liste des utilisateurs reprend l'ensemble des personnes enregistrées dans l'outil. Cet interface vous permet de créer / modifier / supprimer un utilisateur, et consulter des infos basiques de chaque compte utilisateur.
				<br>
				Vous pouvez trier cette liste en cliquant sur les noms des colonnes, et filtrer la liste par équipe ou par moteur de recherche.
				<br>
				<b>Important</b> : la suppression d'un utilisateur entrainera la suppression de toutes les tâches de cet utilisateur.

				<h3>Les principales informations d'un utilisateur</h3>
				L'identifiant est une chaine de caractères qui est unique pour chaque utilisateur. Cet identifiant est celui qui affiché dans le planning. Le but est de définir un identifiant représentant facilement la personne concernée (initiales, numéro d'employé, etc). Cet identifiant ne peut être changé par la suite.
				<br>
				Le champ "nom" est le seul autre champ obligatoire pour la création d'un utilisateur, ce nom sera affiché dans le récapitulatif complet sous le planning.
				<br>
				Le champ "email" est optionnel et servira uniquement si vous activez les notifications par email (voir plus bas).
				<br>
				Le "login" et "mot de passe" sont également facultatif, et ne seront à renseigner que si vous souhaitez donner accès au planning à l'utilisateur concerné. Chaque login est unique. Veuillez également à renseigner des mots de passe suffisamment complexe (8 caractères, avec plusieurs casses).

				<h3>L'équipe</h3>
				Vous pouvez rattacher un utilisateur à une et une seule équipe. Ceci permettra ensuite de définir des droits en rapport avec l'équipe à laquelle il appartient. Voir aide sur <a href="equipes.php">les équipes</a> pour gérer la liste des équipes. Voir le module de <a href="../user_groupes.php.php">gestion des équipes</a>

				<h3>Définition des droits</h3>
				<b>Gestion des utilisateurs</b> : donne droit à l'ensemble des actions possibles sur les comptes utilisateurs. Il n'existe pas de droit intermédiaire permettant de modifier partiellement les comptes. Voir le module de <a href="../user_list.php">gestion des projets</a>.
				<br><br>
				<b>Gestion des projets</b> : donne droit à l'ensemble des actions possibles sur la fiche d'un projet. ça ne donne en revancha aucun droit sur les tâches associées à ces projets. Il n'existe pas de droit intermédiaire permettant de modifier partiellement les projets. Voir le module de <a href="../projets.php">gestion des projets</a>.
				<br><br>
				<b>Gestion des groupes de projets</b> : permet de gérer les groupes de projets, auxquels peuvent être rattachés les projets.  Voir le module de <a href="../groupe_list.php">gestion des groupes de projets</a>.
				<br><br>
				<b>Modification du planning</b> : ce droit détermine ce que l'utilisateur peut modifier dans le planning, ce n'est pas nécessairement l'ensemble des tâches qu'il voit (voir droit ci-dessous). L'accès en <i>lecture seule</i> ne lui permet aucune modification. L'option <i>Uniquement pour les projets dont il est le propriétaire</i> lui permet de modifier uniquement ces projets si il a été défini comme propriétaire dans la fiche d'un projet (voir <a href="../projets.php">gestion des projets</a>). L'option <i>Tâches sur lesquelles il est assigné ou ses propres projets</i> lui permet de modifier les tâches où il est propriétaire du projet ainsi que les tâches qui lui sont assignées sur d'autres projets. Enfin l'option <i>pour tous les projets</i> lui permet de modifier l'ensemble des tâches des projets qu'il peut voir.
				<br><br>
				<b>Vue du planning</b> : l'option <i>tous les projets</i> permet à l'utilisateur de voir l'ensemble des projets présents dans l'outil (sauf s'il a volontairement appliqué un filtre au planning). L'option <i>Uniquement les projets de l'équipe</i> lui permet de consulter uniquement les projets couverts par au moins une personne de la même équipe que cet utilisateur. On ne peut pas définir une liste précise de projets pour ce droit, il suffit d'ajouter au moins une tâche sur un nouveau projet pour que toutes les personnes de la même équipe puisse accéder à ce projet. L'option <i>Uniquement les projets dont il est propriétaire ou assigné</i> permet de voir toutes les tâches des projets dont l'utilisateur est propriétaire, ainsi que les tâches sur lesquelles il est assigné. L'option <i>Uniquement les tâches qui lui sont assigné</i> permet de restreindre l'affichage à ses propres tâches uniquement.
				<br><br>
				<b>Gestion des lieux</b> : donne droit à l'ensemble des actions possibles sur la liste des lieux disponibles. Ce droit s'applique uniquement si ce module optionnel est activé dans les paramètres de SOPlanning. Voir le module de <a href="../lieux.php">gestion des lieux</a>.
				<br><br>
				<b>Gestions des ressources</b> donne droit à l'ensemble des actions possibles sur la liste des ressources/matériels disponibles. Ce droit s'applique uniquement si ce module optionnel est activé dans les paramètres de SOPlanning. Voir le module de <a href="../ressources.php">gestion des ressources</a>.
				<br><br>
				<b>Gestion des paramètres</b> : donne accès à la modification de l'ensemble des paramètres de SOPlanning.
				<br><br>
				<b>Accès aux statistiques</b> : donne accès aux différents modules de statistiques disponibles. Les statistiques sont globales et ne sont pas limitées aux droits de vue du planning définis pour l'utilisateur.

				<h3>Personnalisation et notifications</h3>
				Vous pouvez rendre l'utilisateur <i>visible</i> ou non dans le planning. Cela ne l'empêchera pas de pouvoir se connecter et consulter le planning, en revanche il sera impossible de lui attribuer des tâches.
				<br>
				Vous pouvez également déterminer si cet utilisateur recevra des <i>notifications par email</i>. Un email lui sera envoyé pour toute tâche créée qui lui sera assignée, ou pour toute modification ou suppression d'une tâche qui lui est assignée.
				<br>
				vous pouvez enfin définir la <i>couleur</i> de l'utilisateur. Cette couleur sert de repère visuel dans le planning (en vue par personne). Si vous répartissez les couleurs entre vos différents utilisateurs, vous aurez ainsi un aperçu en un coup d'oeil du planning de chacun sans avoir besoin de survoler chaque tâche pour voir le détail.

				<h3>Infos personnelles</h3>
				Ces champs sont facultatifs et vous permettent de rajouter des informations complémentaires à propos de chaque utilisateur. Ces informations ne sont pas visibles dans le planning et sont accessibles uniquement aux personnes ayant les droits de gestion des utilisateurs.
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}