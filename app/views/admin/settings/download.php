<?php
	$filename = date("d_m_Y H_i_s") . '.sql';
    header("Content-type:text/vcard; charset=utf-8");
    header("Content-Disposition: attachment; filename={$filename}");
    if(isset($_POST['data'])){
    	echo $_POST['data'];
    }
?>