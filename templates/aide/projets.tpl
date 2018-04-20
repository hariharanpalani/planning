{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="span12">
			<div class="soplanning-box" style="font-size:17px">
				<table width="100%">
				<tr>
					<td><b>GESTION DES PROJETS</b></td>
					<td align="right">
						<a href="../projets.php" class="btn btn-sm btn-default">Gestion des projets</a>
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
				Une tâche est systématiquement reliée à un projet et un utilisateur. Avant de créer une tâche, assurez-vous que le projet correspondant soit déjà créé.
				<br>
				Vous pouvez créer autant de projets que vous le souhaitez. Vous pouvez bien entendu les modifier et supprimer à volonté.

				<h3>Liste des projets</h3>
				Par défaut ne sont affichés que les projets <i>à faire</i> ou <i>en cours</i>. Vous pouvez changer ces critères, ils ne s'appliqueront que dans cette interface et non sur le planning.
				<br>
				Vous pouvez également filtrer cette liste de projets par date, par groupe de projets, ou faire une recherche texte sur le titre du projet. Vous pouvez enfin trier la liste en cliquant sur les noms des colonnes.
				<br>
				Dans la liste, les projets sont regroupés selon les groupes de projets, les projets sans groupe sont au début.
				<br>
				<b>Important</b> : la suppression d'un projet entrainera la suppression de toutes les tâches de ce projet
				
				<h3>Les informations d'un projet</h3>
				L'<i>identifiant</i> est une chaine de caractère, unique pour chaque projet. Cette information est le repère visuel affiché dans le planning.
				<br>
				Le <i>nom du projet</i> est la seule autre information obligatoire pour la création d'un projet.
				<br>
				Vous pouvez définir en option un <i>groupe de projets</i>, afin de pouvoir facilement les regrouper visuellement, dans le listing des projets et dans le planning.
				<br>
				Le <i>statut</i> permet de trier également les projets. Par défaut, les projets abandonnés ou archivés sont masqués.
				<br>
				La <i>charge</i>, la <i>date de livraison</i>, le <i>lien</i>, et les <i>commentaires</i> sont de simples informations vous permettant d'ajouter des détails à vos projets sans conséquences sur le planning.
				<br>
				La <i>couleur</i> du projet est la couleur de fond de la cellule pour les tâches (en vue par projet). Choisissez donc des couleurs représentative pour chaque projet pour une meilleure lisibilité.

				<br>
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}