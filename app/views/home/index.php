<aside class="z-depth-2 hide-on-med-and-down">
	<?php
		global $url;
		echo $data['category'];
	?>
</aside>

<ul id="slide-out" class="side-nav">
	<?php
		echo $data['category'];
	?>
</ul>

<?php if (!array_key_exists('error', $data['search']) && $data['search'] == []): ?>

<article class="container row">
	<?php
        //echo '<pre>',print_r($data['code']),'</pre>';
		echo isset($data['html']) ? $data['html'] : '';

		if(isset($data['code'])){
			foreach ($data['code'] as $key => $value) {
				echo '<div class="doc">
						<h4>' . $value['title'] . '</h4>
						<p>' . $value['description'] . '</p>
					    <h5>Eksempel<div class="arrow-down"></div></h5>
					    <textarea class="code">' . htmlspecialchars(base64_decode($value['code'])) . '</textarea>
					</div>';
			}
		}
	?>
</article>
<?php endif ?>

<?php if (array_key_exists('error', $data['search'])): ?>
	<article class="container row">
		<div class="col m12">
			<h3 class="center-align red-text">Din søgning gav 0 resultater</h3>
		</div>
	</article>
<?php endif ?>

<?php if (!array_key_exists('error', $data['search']) && $data['search'] != []): ?>
	<article class="container row search">
		<div class="col s12 m12">
		<?php //echo '<pre>',print_r($data['search']),'</pre>'; ?>
			<?php foreach ($data['search'] as $key => $value): ?>
				<div class="row card blue-grey darken-3">
					<a href="<?= $url->link('home/category/'.$value['id']) ?>">
						<div class="col s12 m12">
							<span><?= $value['catName'] ?></span>
							<span class="breadcrumb"><?= $value['subName'] ?></span>
							<span class="breadcrumb"><?= $value['title'] ?></span>
						</div>
						<hr>
						<div class="col s12 m12">
							<p><?= $value['description'] ?></p>
						</div>
					</a>
				</div>
			<?php endforeach ?>
		</div>
	</article>
<?php endif ?>