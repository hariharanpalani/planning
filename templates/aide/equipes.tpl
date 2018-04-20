{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="span12">
			<div class="soplanning-box" style="font-size:17px">
				<table width="100%">
				<tr>
					<td><b>GESTION DES EQUIPES</b></td>
					<td align="right">
						<a href="../user_groupes.php" class="btn btn-sm btn-default">Gestion des équipes</a>
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
				Les équipes permettent de regrouper ensemble des projets visuellement. 
				<br>
				Les équipes peuvent avoir un impact sur le droit des utilisateurs dans certains cas, voir gestion des <a href="utilisateurs.php">droits des utilisateurs</a>.
				<br>
				Ces équipes aident à la visibilité dans la gestion des utilisateurs (filtre sur une équipe possible), et dans le planning (possibilité de regrouper les utilisateurs par équipe dans l'option "trier par").
				<br>
				Le module de gestion des équipes permet de créer / modifier / supprimer des équipes à volonté.
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}