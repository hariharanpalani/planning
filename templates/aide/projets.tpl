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
				Une t�che est syst�matiquement reli�e � un projet et un utilisateur. Avant de cr�er une t�che, assurez-vous que le projet correspondant soit d�j� cr��.
				<br>
				Vous pouvez cr�er autant de projets que vous le souhaitez. Vous pouvez bien entendu les modifier et supprimer � volont�.

				<h3>Liste des projets</h3>
				Par d�faut ne sont affich�s que les projets <i>� faire</i> ou <i>en cours</i>. Vous pouvez changer ces crit�res, ils ne s'appliqueront que dans cette interface et non sur le planning.
				<br>
				Vous pouvez �galement filtrer cette liste de projets par date, par groupe de projets, ou faire une recherche texte sur le titre du projet. Vous pouvez enfin trier la liste en cliquant sur les noms des colonnes.
				<br>
				Dans la liste, les projets sont regroup�s selon les groupes de projets, les projets sans groupe sont au d�but.
				<br>
				<b>Important</b> : la suppression d'un projet entrainera la suppression de toutes les t�ches de ce projet
				
				<h3>Les informations d'un projet</h3>
				L'<i>identifiant</i> est une chaine de caract�re, unique pour chaque projet. Cette information est le rep�re visuel affich� dans le planning.
				<br>
				Le <i>nom du projet</i> est la seule autre information obligatoire pour la cr�ation d'un projet.
				<br>
				Vous pouvez d�finir en option un <i>groupe de projets</i>, afin de pouvoir facilement les regrouper visuellement, dans le listing des projets et dans le planning.
				<br>
				Le <i>statut</i> permet de trier �galement les projets. Par d�faut, les projets abandonn�s ou archiv�s sont masqu�s.
				<br>
				La <i>charge</i>, la <i>date de livraison</i>, le <i>lien</i>, et les <i>commentaires</i> sont de simples informations vous permettant d'ajouter des d�tails � vos projets sans cons�quences sur le planning.
				<br>
				La <i>couleur</i> du projet est la couleur de fond de la cellule pour les t�ches (en vue par projet). Choisissez donc des couleurs repr�sentative pour chaque projet pour une meilleure lisibilit�.

				<br>
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}