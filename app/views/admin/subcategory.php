<?php
	global $url;
?>

<?php if (!isset($data['catId']) && !isset($data['catName'])): ?>
	<div class="navbar-fixed">
		<nav>
			<div class="nav-wrapper red">
				<a href="<?= $url->link('admin'); ?>" class="brand-logo"><i class="large material-icons">keyboard_backspace</i></a>
				<p>Underkategorier</p>
			</div>
		</nav>
	</div>
	<div class="row">
	<?php foreach ($data['categorys'] as $key => $value): ?>
		<div class="col s12 m3">
			<a href="<?= $url->link('admin/subcategory/'.$key . '/' . $value['name']) ?>">
				<div class="card teal">
					<div class="card-content white-text">
						<span class="card-title"><?= $value['name'] ?></span>
					</div>
				</div>
			</a>
		</div>
	<?php endforeach ?>
	</div>
<?php endif ?>

<?php if ((isset($data['catId']) && isset($data['catName'])) && !isset($data['edit'])) : ?>
	<div class="navbar-fixed">
		<nav>
			<div class="nav-wrapper red">
				<a href="<?= $url->link('admin/subcategory'); ?>" class="brand-logo"><i class="large material-icons">keyboard_backspace</i></a>
				<p><?= $data['catName'] ?></p>
			</div>
		</nav>
	</div>
	<div class="row">
		<div class="col s12 red">
			<ul class="tabs red">
				<li class="tab col s3"><a class="active" href="#test1">Oversigt</a></li>
				<li class="tab col s3"><a href="#test2">Opret</a></li>
			</ul>
		</div>
		<div id="test1" class="col s12">
			<div class="col m12">
		        <?php if (!empty($data['categorys'][$data['catId']]['submenu'])): ?>
			      <table class="highlight">
			        <thead>
			          <tr>
			              <th data-field="name">Navn</th>
			              <th data-field="actions">Muligheder</th>
			          </tr>
			        </thead>
			        <tbody>
			        	<?php
				        	$i = 1;
			        		$totalRows = sizeof($data['categorys'][$data['catId']]['submenu']);
			        		foreach ($data['categorys'][$data['catId']]['submenu'] as $key => $value):
			        	?>
			        		<tr>
			        			<td><?= $value['name'] ?></td>
			        			<td>
			        				<form method="post">
			        					<input type="hidden" name="id" value="<?= $value['id'] ?>">
			        					<a href="<?= $url->link('admin/subcategory/'.$data['catId'].'/'.$data['catName'].'/edit/' . $value['id']) ?>" class="btn tooltipped green" data-position="top" data-delay="50" data-tooltip="Rediger"><i class="tiny material-icons">edit</i></a>
										<a href="#modal<?= $key ?>" class="btn tooltipped red darken-4 waves-effect waves-light modal-trigger" data-position="top" data-delay="50" data-tooltip="Slet"><i class="tiny material-icons">delete</i></a>
										<div id="modal<?= $value['id'] ?>" class="modal">
											<div class="modal-content">
												<h4>Slet?</h4>
												<p>Er du sikker på at du vil slette?</p>
											</div>
											<div class="modal-footer">
												<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Nej</a>
												<a href="<?= $url->link('admin/subcategory/'.$data['catId'].'/'.$data['catName'].'/delete/' . $value['id']) ?>" class=" modal-action modal-close waves-effect waves-green btn-flat">Ja</a>
											</div>
										</div>
										<?php if ($i == 1 && $totalRows != 1): ?>
											<button type="submit" name="sortUp" value="<?= $i ?>" class="btn disabled"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="<?= $i ?>" class="btn"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										<?php endif ?>
										<?php if ($i == $totalRows && $totalRows != 1): ?>
											<button type="submit" name="sortUp" value="<?= $i ?>" class="btn"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="<?= $i ?>" class="btn disabled"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										<?php endif ?>
										<?php if ($i != $totalRows && $i != 1): ?>
											<button type="submit" name="sortUp" value="<?= $i ?>" class="btn"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="<?= $i ?>" class="btn"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										<?php endif ?>
										<?php if ($i == $totalRows && $i == 1): ?>
											<button type="submit" name="sortUp" value="<?= $i ?>" class="btn disabled"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="<?= $i ?>" class="btn disabled"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										<?php endif ?>
			        				</form>
			        			</td>
			        		</tr>
			        	<?php
			        		$i++;
			        		endforeach;
			        	?>
			        </tbody>
			      </table>
		        <?php endif ?>
			</div>
		</div>
		<div id="test2" class="col s12">
			<form method="post">
				<div class="row">
					<div class="input-field col s12">
						<input name="name" id="input_text" type="text" length="80">
						<label for="input_text">Underkategori navn</label>
					</div>
					<div class="input-field col s12 right-align">
						<button class="btn" type="submit" name="add">Opret<i class="material-icons right">send</i></button>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php endif ?>

<?php if(isset($data['edit'])): 
	$id = $data['edit'][0]['id'];
	$name = $data['edit'][0]['name'];
	$currentCat = $data['categorys'][$data['catId']]['name'];
?>
	<div class="navbar-fixed">
		<nav>
			<div class="nav-wrapper red">
				<a href="<?= $url->link('admin/subcategory/'.$data['catId'].'/'.$data['catName']) ?>" class="brand-logo"><i class="large material-icons">keyboard_backspace</i></a>
				<p><?= $data['catName'] ?></p>
			</div>
		</nav>
	</div>

	<div class="row">
		<div class="col s12">
			<form method="post">
				<div class="row">
					<div class="input-field col s12">
						<input type="hidden" name="id" value="<?= $id ?>">
						<input name="name" id="input_text" type="text" length="80" value="<?= $name ?>">
						<label for="input_text">Kategori navn</label>
					</div>
					<div class="input-field col s12">
						<select name="catId">
							<option value="" disabled selected><?= $currentCat ?></option>
							<?php foreach ($data['categorys'] as $key => $value): ?>
								<?php if ($key != $data['catId']): ?>
									<option value="<?= $key ?>"><?= $value['name'] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						</select>
						<label>Kategori</label>
					</div>

					<div class="input-field col s12 right-align">
						<button class="btn" type="submit" name="edit">Opdater<i class="material-icons right">send</i></button>
					</div>
				</div>
			</form>
		</div>
	</div>

<?php endif; ?>