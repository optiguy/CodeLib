<?php 
	global $url;
?>


<?php if(!isset($data['edit'])): ?>
<div class="navbar-fixed">
	<nav>
		<div class="nav-wrapper teal">
			<a href="<?= $url->link('admin'); ?>" class="brand-logo"><i class="large material-icons">keyboard_backspace</i></a>
			<p>Kategorier</p>
		</div>
	</nav>
</div>

<div class="row">
	<div class="col s12 teal">
		<ul class="tabs teal">
			<li class="tab col s3"><a class="active" href="#test1">Oversigt</a></li>
			<li class="tab col s3"><a href="#test2">Opret</a></li>
		</ul>
	</div>
	<div id="test1" class="col s12">
		<div class="col m12">
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
		        		$html = '';
		        		$totalRows = sizeof($data['categorys']);
		        		foreach ($data['categorys'] as $key => $value) {
		        			$html .= '
								<tr>
									<td>' . $value['name'] . '</td>
									<td>
										<form method="post">
											<input type="hidden" name="id" value="' . $key. '">
											<a href="' . $url->link('admin/category/edit/' . $key) . '" class="btn tooltipped green" data-position="top" data-delay="50" data-tooltip="Rediger"><i class="tiny material-icons">edit</i></a>
											<a href="#modal' . $key . '" class="btn tooltipped red darken-4 waves-effect waves-light modal-trigger" data-position="top" data-delay="50" data-tooltip="Slet"><i class="tiny material-icons">delete</i></a>
											<div id="modal' . $key . '" class="modal">
												<div class="modal-content">
													<h4>Slet?</h4>
													<p>Er du sikker på at du vil slette?</p>
												</div>
												<div class="modal-footer">
													<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Nej</a>
													<a href="' . $url->link('admin/category/delete/' . $key) . '" class=" modal-action modal-close waves-effect waves-green btn-flat">Ja</a>
												</div>
											</div>
								';

							if($i == 1){
								$html .= '
											<button type="submit" name="sortUp" value="'. $i .'" class="btn disabled"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="'. $i .'" class="btn"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										';
							} else if($i == $totalRows){
								$html .= '
											<button type="submit" name="sortUp" value="'. $i .'" class="btn"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="'. $i .'" class="btn disabled"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										';
							} else {
								$html .= '
											<button type="submit" name="sortUp" value="'. $i .'" class="btn"><i class="tiny material-icons">keyboard_arrow_up</i></button>
											<button type="submit" name="sortDown" value="'. $i .'" class="btn"><i class="tiny material-icons">keyboard_arrow_down</i></button>
										';
							}

							$html .= '
										</form>
									</td>
								</tr>
		        			';
		        			$i++;
		        		}
		        		echo $html;
		        	?>
		        </tbody>
		      </table>
		</div>
	</div>
	<div id="test2" class="col s12">
		<form method="post">
			<div class="row">
				<div class="input-field col s12">
					<input name="name" id="input_text" type="text" length="80">
					<label for="input_text">Kategori navn</label>
				</div>
				<div class="input-field col s12 right-align">
					<button class="btn" type="submit" name="add">Opret<i class="material-icons right">send</i></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php endif; ?>

<?php if(isset($data['edit'])): ?>
<div class="navbar-fixed">
	<nav>
		<div class="nav-wrapper teal">
			<a href="<?= $url->link('admin/category'); ?>" class="brand-logo"><i class="large material-icons">keyboard_backspace</i></a>
			<p>Kategorier</p>
		</div>
	</nav>
</div>

<div class="row">
	<div class="col s12">
		<form method="post">
			<div class="row">
				<div class="input-field col s12">
					<input type="hidden" name="id" value="<?= $data['edit']['id'] ?>">
					<input name="name" id="input_text" type="text" length="80" value="<?= $data['edit']['name']?>">
					<label for="input_text">Kategori navn</label>
				</div>
				<div class="input-field col s12 right-align">
					<button class="btn" type="submit" name="edit">Opdater<i class="material-icons right">send</i></button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php endif; ?>