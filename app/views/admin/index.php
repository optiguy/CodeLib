<?php
	global $url;
?>
<aside class="z-depth-2 hide-on-med-and-down">
	<?php
		echo $data['categoryRender'];
	?>
</aside>
<article class="container row admin">
	<a href="<?= $url->link('admin/category'); ?>">
		<div class="col s12">
			<div class="card-panel teal white-text row">
				<div class="col m12">
					<p>
						<i class="material-icons">menu</i>
						Kategorier - <?= $data['categorys'] ?>
					</p>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= $url->link('admin/subcategory'); ?>">
		<div class="col s12">
			<div class="card-panel red white-text row">
				<div class="col m12">
					<p>
						<i class="material-icons">list</i>
						Underkategorier - <?= $data['subCategorys'] ?>
					</p>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= $url->link('admin/subject'); ?>">
		<div class="col s12">
			<div class="card-panel green white-text row">
				<div class="col 12">
					<p>
						<i class="material-icons">subject</i>
						Koder - <?= $data['subjects'] ?>
					</p>
				</div>
			</div>
		</div>
	</a>
	<form method="post" action="http://localhost/CodeLib/app/views/admin/settings/download.php" onsubmit="doubleSubmit(this)">
		<button class="btn col s12 indigo darken-4 white-text">
			<i class="material-icons">cloud_download</i>
			Backup
		</button>
		<input type="hidden" name="data" value="<?php echo $data['backupString']; ?>">
	</form>

	<script type="text/javascript">
	<!--
	// function doubleSubmit(f)
	// {
	//   // submit to action in form
	//   f.submit();
	//   // set second action and submit
	//   f.target="_blank";
	//   f.action="http://localhost/CodeLib/app/views/admin/settings/download.php";
	//   f.submit();
	//   return false;
	// }
	//-->
	</script>
	<a href="<?= $url->link('admin/CleanDB'); ?>">
		<div class="col s12">
			<div class="card-panel indigo darken-4 white-text row">
				<div class="col 12">
					<p>
						<i class="material-icons">layers_clear</i>
						CleanDB
					</p>
				</div>
			</div>
		</div>
	</a>
</article>