<?php
	require __DIR__.'/../connect.php';
	require_once __DIR__.'/../auth/auth-class.php';
	require __DIR__.'/../const.php';
	require_once __DIR__.'/get-type-friend.php';


	$link = mysqli_connect($host, $user, $password, $database) 
    	or die("Ошибка " . mysqli_error($link));
	$link->set_charset("utf8");

	//$searching_user = '3';
	$str = 'Максим Осташов Артур 3';

	$key_words = explode(" ", $str);

	$firstnames = selectFirstNames($key_words, 'firstname');
	$lastnames = selectFirstNames($key_words, 'lastname');
	$ids = selectFirstNames($key_words, 'id');
	$full = array_merge($firstnames, $lastnames, $ids);
	$full = array_unique($full, SORT_REGULAR);

	if($auth->isAuth())
		$id_auth_user = $auth->get_id();
	else
		exit('Вы не авторизированы');

	
	foreach ($full as $key => $value) {
		if ($value['id'] == $id_auth_user) {
			unset($full[$key]);
			sort($full);
		} else 
		$full[$key] = getTypeFriend($value,$id_auth_user);
	}
	print_r($full);
	//echo json_encode($full);

	function selectFirstNames($key_words, $column){
		global $link;
		$query = "SELECT id, firstname, lastname, friends, my_invite, me_invite, icon FROM users WHERE"; 

		foreach ($key_words as $value) {
			$str = " $column = '$value' OR";
			$query .= $str;
		}
		$query = substr($query, 0, -3);
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
		$output = [];
		while($row = mysqli_fetch_assoc($result))
			array_push($output, $row);
		return $output;
	}
?>