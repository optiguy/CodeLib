<?php
    require_once 'app/config.php';
    use Helpers\Url;
    use HttpContext\Current\Request;
    $url = new Url();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CodeLib</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/theme/dracula.min.css">
 	<link rel="stylesheet" type="text/css" href="<?= $url->link('assets/css/main.css'); ?>">
</head>
<body>
    <?php
        $currentUrl  = null;
        if(isset($_GET['url'])){
            $currentUrl = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    ?>
    <?php if (($currentUrl[0] != 'admin' || empty($currentUrl[1])) || ($currentUrl[0] == 'admin' && $currentUrl[1] == 'index')): ?>
	<header>
        <form method="post">
    		<div class="navbar-fixed">
    			<nav>
    				<div class="nav-wrapper blue-grey">
    				  <form>
    				    <div class="input-field">
    				      <input id="search" name="search" type="search" autocomplete="off" required>
    				      <label for="search"><i class="material-icons">search</i></label>
    				      <i class="material-icons">close</i>
    				    </div>
    				  </form>
    				</div>
    			</nav>
    		</div>
        </form>
	</header>
                    <!-- <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a> -->
    <?php endif ?>
	<main class="row">
		<?php
            require_once 'app/init.php';
        ?>
	</main>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/addon/edit/matchbrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.18.2/mode/php/php.min.js"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="<?= $url->link('assets/js/app.js'); ?>"></script>
    <?php if ($currentUrl[0] == 'admin' && isset($currentUrl[1]) && $currentUrl[1] == 'index'): ?>
        <?php if (isset($currentUrl[2]) && $currentUrl[2] == 'cleanDB'): ?>
        <script>
            setTimeout(() => {
                Materialize.toast('Det lykkes at rense DB for koder der ikke har en parent mere og der er blevet sorteret forfra!', 4000);
            },500);
        </script>
        <?php endif ?>
    <?php endif ?>
</body>
</html>