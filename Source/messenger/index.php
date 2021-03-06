<?php 
	require_once("../connect.php");
	require_once("../auth/auth-class.php");

	if ( ($user_id = $auth->get_id()) === NULL) {
		header("Location:../index.php");
	}
?>

<?php
	function showChatPreview($chat_data) {

		echo "<a class=\"chatpreview\" href=\"?chat_id=".$chat_data['id']."\">";
		echo "<img class=\"chatpreview-icon\" src=\"".$chat_data['icon']."\">";
		echo "<div>
				<p class=\"chatpreview-title\">".$chat_data['name']."</p>
				<p class=\"chatpreview-lastmessage\">Last message</p>
			</div></a>";		
	}

	function showChatList($user_id) {
		global $mysqli;

		echo "<div id=\"chat_list\">";
		
		$result = $mysqli->query("SELECT chat_id from user_chat where user_id=".$user_id);
		
		while ($row = $result->fetch_assoc()) {

			$chat_data = ($mysqli->query("SELECT * from chats where id=".$row['chat_id']))->fetch_assoc();
			showChatPreview($chat_data);

		}
		
		echo "</div>";
	}

	// RIGHT CONTAINER

	function showDefaultLabel() {
		echo "<p id=\"default_label\">Пожалуйста, выберите чат в левой части страницы</p>";
	}

	function showChatHeader($chat_id, $chat_data, $user_role) {

		echo "<div id=\"chatinfo-container\">";
		echo "<div id=\"chatinfo-leftarrow\"></div>";
		
		echo "<div id=\"chatinfo-header\">";
			echo "<img id=\"chatinfo-icon\" src=\"".$chat_data['icon']."\">";

			echo "<div>";
			echo "<div id=\"chatinfo-title\">".$chat_data['name']."</div>";
			echo "<p id=\"chatinfo-usercnt\">124 участника</p>";
			echo "</div>";
		echo "</div>";

		if ($user_role == 0) {
			echo "<div onclick=\"showPoputMenu()\" id=\"chatinfo-settings\"></div>";
		}
	
		echo "</div>";
	}

	function showMessage($user_data, $message_data) {
		global $user_id;

		echo "<div class=\"messagebox";

		if ($message_data['user_id'] == $user_id) {
			echo " messagebox-float";
		}

		echo "\">";
		echo "<div class=\"messagebox-userdata\">";
		echo "<img class=\"messagebox-icon\" src=\"".$user_data['icon']."\">";
		echo "<p class=\"messagebox-username\">".$user_data['firstname']." ".$user_data['lastname']."</p>";
		echo "</div>";
		echo "<p class=\"messagebox-data\">".htmlspecialchars_decode($message_data['data'])."</p>";
		echo "<p class=\"messagebox-date\">".$message_data['date']."</p>";
		echo "</div>";
	}

	function showMessageList($chat_id) {
		// global $mysqli;

		echo "<div id=\"messagelist-container\">";

		// $m_list = $mysqli->query("SELECT * from messages 
		// 						 where chat_id=".$chat_id." order by date desc limit 5");

		// while ($m_row = $m_list->fetch_assoc()) {
		// 	$user_data = ($mysqli->query("SELECT * from users where id=".$m_row['user_id']))->fetch_assoc();

		// 	echo "<div class=\"messagelist-line\">";
			
		// 	showMessage($user_data, $m_row);

		// 	echo "</div>";
		// }

		echo "</div>";
	}

	function showSendForm($chat_id) {

		echo "<div id=\"sendform-container\">
		<div id=\"sendform-box\">
			<div contenteditable=\"true\" id=\"sendform-entryfield\"></div>
		</div>
		<div id=\"sendform-button\"></div>
		</div>";

// 		<div id=\"sendform-box\">
// </div>

		// echo "<div id=\"send_form_container\">";
		// // echo "<form action=\"send_message.php?chat_id=".$chat_id."\" method=\"POST\">";
		// echo "<input id=\"sendform-entry-field\" style=\"width: 60%;\" type=\"text\" name=\"data\">";
		// echo "<input id=\"sendform-button\" type=\"submit\" name=\"sender\" value=\"send\">";
		// // echo "</form>";
		// echo "</div>";
	}

	function showRightSide($user_id) {
		global $mysqli;

		if (!isset($_GET['chat_id'])) {
			showDefaultLabel();
			return;
		}

		$chat_id = $_GET['chat_id'];

		$userchat_result = $mysqli->query("SELECT user_role from user_chat where chat_id=".$chat_id." and user_id=".$user_id);

		$user_role = ($userchat_result->fetch_assoc())['user_role'];

		// user role {
		//		0 - admin
		//		1 - writer
		//		2 - reader
		// }

		$chat_data = ($mysqli->query("SELECT * from chats where id=".$chat_id))->fetch_assoc();

		if ($user_role == NULL) { // Проверка на доступ user к chat
			
			if ($chat_data['access_type'] != 1) {
				showDefaultLabel();
				return;
			}
			
			// Если чат публичный, т.е. access_type == 1 , добавляем user к чату

			$mysqli->query("INSERT into user_chat(user_id, chat_id, user_role)
			 				values (".$user_id.",".$chat_id.",".$chat_data['default_user_role'].")");

			header("Location:index.php?chat_id=".$chat_id);
			exit();

		}
		
		showChatHeader($chat_id, $chat_data, $user_role);
		showMessageList($chat_id);

		if ($user_role < 2) {
			showSendForm($chat_id);
		}
	}


?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="css/messenger.css">
	<link rel="stylesheet" type="text/css" href="css/poput.css">
	<link rel="stylesheet" type="text/css" href="../../css/styles.css">
</head>
<body>
<div class="header" style="margin-bottom: 0.3em">
		<a name="header"></a>
		<div class="header-top-div">
			<div class="header-name">
				<span class="span-name">I</span>
				<span class="span-name">S</span>
				<span class="span-name">N</span>
			</div>
			<div class="header-logo">
				<a href="profile.html">
				<img src="../../images/logo.png" width="58" height="58" class="header-logo-img">
			</a>
			</div>
			<a href="#" onclick="setting_show()">
			<div class="header-settings"></div></a>
		</div>
	</div>
<div id="main_container">
	
	<div id="left_container">
		
		<!-- <a id="create-chat_button" href="create_new_chat.php">создать чат</a> -->

		<a id="newchat" href="create_new_chat.php">
			<div id="newchat-icon"></div>
			<p id="newchat-title">Создать новый чат</p>
		</a>
		
		<?php showChatList($user_id);?>
		

	</div>
	<div id="right_container">
		
		<?php showRightSide($user_id); ?>		
		
	</div>
	

</div>

<div id="cover">
<div id="poput">



	<div id="settings-close" onclick="hidePoputMenu()">
		<p id="settings-close-label">Закрыть</p>
		<img id="settings-close-icon" src="img/cross.png">
	</div>

	<div id="settings-container">
		
		
		

		<div class="settings-subcontainer">
			<div class="settings-box">
				<img id="settings-icon" src="img/Group.png">
			</div>
			<div class="settings-box">
				<label class="settings-label">
					Иконка чата
				</label>
				<div class="settings-box-body">
					<input id="settings-icon-src" class="settings-entryfield" type="text" name="" value="Group.png">
				</div>
			</div>
		</div>

		<div class="settings-box">
			<label class="settings-label">Название группы</label>
			<div class="settings-body">
				<input id="settings-name" class="settings-entryfield" type="text" name="" value="Name">
			</div>
		</div>

		<div class="settings-box">
			<label class="settings-label">Тип доступа</label>
			<div class="settings-body">
				<div class="settings-radiogroup">

					<input id="radio-accesstype-private" class="settings-radio" type="radio" name="access-type" value="private">
					<label for="radio-accesstype-private" class="settings-radio-label">Private</label>

					<input id="radio-accesstype-public" class="settings-radio" type="radio" name="access-type" value="public">
					<label for="radio-accesstype-public" class="settings-radio-label">Public</label>
				
				</div>
			</div>
		</div>

		<div class="settings-box">
			<label class="settings-label">Роль пользователя по умолчанию</label>
			<div class="settings-body">
				<div class="settings-radiogroup">

					<input id="radio-userrole-reader" class="settings-radio" type="radio" name="user-role" value="reader">
					<label for="radio-userrole-reader" class="settings-radio-label">Reader</label>

					<input id="radio-userrole-writer" class="settings-radio" type="radio" name="user-role" value="writer">
					<label for="radio-userrole-writer" class="settings-radio-label">Writer</label>
				
					<input id="radio-userrole-admin" class="settings-radio" type="radio" name="user-role" value="admin">
					<label for="radio-userrole-admin" class="settings-radio-label">Admin</label>

				</div>
			</div>
		</div>

		<div id="settings-submit" onclick="savePoputMenuData()">Сохранить изменения</div>
		<div id="settings-successmessage">Настройки сохранены</div>

	</div>

	<div id="usermanager-menu">
			<input id="menu-tab-usersmanager" class="menu-tab" type="radio" name="usermanager-menu" checked="true">
			<label for="menu-tab-usersmanager" class="menu-tab-label">Управление подписчиками</label>

			<input id="menu-tab-addusers" class="menu-tab" type="radio" name="usermanager-menu">
			<label for="menu-tab-addusers" class="menu-tab-label">Добавить друзей в чат</label>			
		</div>

	<div id="usermanager-list">
		<!-- <div id="usermanager-userlist">list</div> -->
		<!-- <div class="chatuser">
			
			<div class="chatuser-header">
				<img class="chatuser-icon" src="https://upload.wikimedia.org/wikipedia/commons/f/f0/Google_Earth_Icon.png">
				<p class="chatuser-username">Ivan Kozlov</p>
				<img class="chatuser-delete" src="img/cross.png">
			</div>				

			<div class="settings-radiogroup">

				<input id="chatuser-userrole-reader" class="settings-radio" type="radio" name="user-role" value="reader">
				<label for="chatuser-userrole-reader" class="settings-radio-label">Reader</label>

				<input id="chatuser-userrole-writer" class="settings-radio" type="radio" name="user-role" value="writer">
				<label for="chatuser-userrole-writer" class="settings-radio-label">Writer</label>
			
				<input id="chatuser-userrole-admin" class="settings-radio" type="radio" name="user-role" value="admin">
				<label for="chatuser-userrole-admin" class="settings-radio-label">Admin</label>

			</div>
			
		</div> -->
	</div>
</div>

</div>

<script type="text/javascript">
			// Функция для плучения переменных из URL

	var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};



	var req = new XMLHttpRequest();

	function getChatUsersList() {
		req.open("POST", "get_users_list.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send("chat_id="+getUrlParameter('chat_id'));
	}

	function getPoputMenuData() {

		req.open("POST", "get_poput_menu_data.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send("chat_id="+getUrlParameter('chat_id'));
	}


	function updatePoputMenuData(data) {
		document.getElementById("settings-icon").src = data['icon'];
		document.getElementById("settings-icon-src").value = data['icon'];
		document.getElementById("settings-name").value = data['name'];

		var accesstype = ['private', 'public'];
		document.getElementById("radio-accesstype-" + accesstype[data['access_type']]).checked = true;
		var role = ['admin', 'writer', 'reader'];
		document.getElementById("radio-userrole-" + role[data['default_user_role']]).checked = true;

	}

	function savePoputMenuData() {

		var response = "chat_id="+getUrlParameter('chat_id');

		response += "&icon=\"" + document.getElementById("settings-icon-src").value+"\"";
		response += "&name=\"" + document.getElementById("settings-name").value+"\"";

		var accesstype = document.getElementsByName('access-type');

		for (i in accesstype) {
			if (accesstype[i].checked) {
				response += "&access_type=" + i;
			}
		}

		var userrole = document.getElementsByName('user-role');

		for (i in userrole) {
			if (userrole[i].checked) {
				response += "&default_user_role=" + i;
			}
		}

		// console.log(response);

		req.open("POST", "save_poput_menu_data.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send(response);

	}

	function showSavePoputMenuDataMessage() {
		var block = document.getElementById('settings-successmessage');
		block.style.display = 'block';

		setTimeout(function() {
			var block = document.getElementById('settings-successmessage');
			block.style.display = 'none';
		}, 2000);

	}

	function showPoputMenu() {
		var cover = document.getElementById("cover");
		cover.style.display = 'block';
		cover.onclick = function() {
			// cover.style.display = 'none';
		};
		getPoputMenuData();
		// getChatUsersList();
	}
	function hidePoputMenu() {
		var cover = document.getElementById("cover");
		cover.style.display = 'none';

		document.getElementById('usermanager-list').innerHTML = "";
	}

	function deleteUserFromChat(id) {
		req.open("POST", "delete_user_from_chat.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send("chat_id="+getUrlParameter('chat_id')+"&delete_user_id="+id);
	}

	function hidePoputUserBlock(id) {
		var chatuser = document.getElementById('chatuserID' + id);
		chatuser.style.display = 'none';
	}

	function changeUserRole(id, role) {
		req.open("POST", "change_user_role.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send("chat_id="+getUrlParameter('chat_id')+"&change_user_id="+id+"&role=" + role);
	}

	function createPoputUserBlock(userslist) {
		var usermanager = document.getElementById('usermanager-list');

		for (userIndex in userslist) {
			
			var chatuser = document.createElement('div');
			chatuser.className = 'chatuser';
			chatuser.id = 'chatuserID' + userslist[userIndex].id;

			var chatuser_header = document.createElement('div');
			chatuser_header.className = 'chatuser-header';

			var icon = document.createElement('img');
			icon.className = 'chatuser-icon';
			icon.src = userslist[userIndex].icon;
			chatuser_header.appendChild(icon);


			var name = document.createElement('p');
			name.className = 'chatuser-username';
			name.innerHTML = userslist[userIndex].name;
			chatuser_header.appendChild(name);

			if (userslist[userIndex].role != 0) {
				var del = document.createElement('img');
				del.className = 'chatuser-delete';
				del.src = 'img/cross.png';
				chatuser_header.appendChild(del);
				del.deleteUserID = userslist[userIndex].id;

				del.onclick = function(event) {
					deleteUserFromChat(event.target.deleteUserID);
				};
			}

			chatuser.appendChild(chatuser_header);
			usermanager.appendChild(chatuser);

			var radiogroup = document.createElement('div');
			radiogroup.className = 'settings-radiogroup';

			var roleTitles = ['admin', 'writer', 'reader'];

			for (roleIndex in roleTitles) {

				var radio = document.createElement('input');
				radio.className = 'settings-radio';
				radio.id = 'chatuser-userrole-' + roleTitles[roleIndex] + '-id' + userslist[userIndex].id;
				radio.type = 'radio';
				radio.name = 'chatuser-role-id' + userslist[userIndex].id;
				radio.value = roleTitles[roleIndex];

				if (userslist[userIndex].role == roleIndex) {
					radio.checked = true;
				}

				radiogroup.appendChild(radio);

				var label = document.createElement('label');
				label.id = 'chatuser-userrole-label-' + roleTitles[roleIndex] + '-id' + userslist[userIndex].id;
				label.htmlFor = 'chatuser-userrole-' + roleTitles[roleIndex] + '-id' + userslist[userIndex].id;
				label.className = 'settings-radio-label';
				label.innerHTML = roleTitles[roleIndex];

				label.senddata = {
					'userID' : userslist[userIndex].id,
					'role' : roleIndex
				};

				label.onclick = function(event) {
					changeUserRole(event.target.senddata.userID, event.target.senddata.role);
				};

				radiogroup.appendChild(label);
			}

			chatuser.appendChild(radiogroup);
			usermanager.appendChild(chatuser);
		}
	}

	function getCurrentDate() {
		req.open("GET", "get_current_date.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send();
	}

	function getNewMessages() {
		req.open("POST", "get_new_messages.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send("current_date="+currentDate+"&chat_id="+getUrlParameter('chat_id'));
	}

	function getLastMessages() {
		req.open("POST", "get_last_messages.php", true);
		req.onreadystatechange = handleServerResponse;
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.send("chat_id="+getUrlParameter('chat_id'));
	}

	var chatIsOpen = (getUrlParameter('chat_id') == undefined) ? false : true;

	var sendButton;
	var entryField;
	var leftarrow;
	var currentDate = 0;

	if (chatIsOpen) {

		sendButton = document.getElementById("sendform-button");
			entryField = document.getElementById("sendform-entryfield");

			
			if (entryField != undefined) {
				entryField.addEventListener("keypress", function(event) {
					if (!event.shiftKey && event.keyCode === 13) {

						event.preventDefault();

						sendButton.click();
					}
				}, false);

				sendButton.onclick = function sendMessage() {
		
				// var entryField = document.getElementById("sendform-entry-field");
				if (entryField.value == "") {
					return;
				}

				req.open("POST", "send_message_async.php", true);
				req.onreadystatechange = handleServerResponse;

				req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				
				var message = "data="+entryField.innerHTML+"&chat_id="+getUrlParameter('chat_id');

				req.send(message);
		};

			}

		
	}

	// Функция для вывода сообщения в чат

	function messageOutput(message_data, success) {
		// var entryField = document.getElementById("sendform-entry-field");
		var container = document.getElementById("messagelist-container");

		var message_line = document.createElement('div');
		message_line.className = 'messagelist-line';
		var message_box = document.createElement('div');
		message_box.className = 'messagebox';

		var icon = document.createElement('img'); icon.className = 'messagebox-icon'; 
		var name = document.createElement('p'); name.className = 'messagebox-username';
		var data = document.createElement('p'); data.className = 'messagebox-data';
		var date = document.createElement('p'); date.className = 'messagebox-date';

		if (success == 'false') {
			data.innerHTML = 'Сообщение не отправлено';
			
			message_box.appendChild(data);
			message_line.appendChild(message_box);
			container.appendChild(message_line);

			return;
		}
		
		icon.src = message_data['icon_src'];
		name.innerHTML = message_data['user_name'];
		data.innerHTML = message_data['data'];
		date.innerHTML = message_data['date'];

		messagebox_userdata = document.createElement('div');
		messagebox_userdata.className = 'messagebox-userdata';


		messagebox_userdata.appendChild(icon);
		messagebox_userdata.appendChild(name);
		
		message_box.appendChild(messagebox_userdata);
		message_box.appendChild(data);
		message_box.appendChild(date);

		message_line.appendChild(message_box);

		container.appendChild(message_line);

		// console.log('width', container.offsetHeight, container.clientHeight, container.scrollHeight);
		container.scrollTo(0, container.scrollHeight);

		// entryField.value = '';
	}

	// Обработчик ответов от сервера

	function handleServerResponse() {
		// if (req.status != 200) {
		//  	console.log("status: "+req.status);
		// }

		if ((req.readyState == 4) && (req.status == 200)) {
			serverRequest = JSON.parse(req.responseText);
			// console.log(req.responseText);
			switch(serverRequest['header']) {
				case 'sendMessage':

					messageOutput(serverRequest, serverRequest['request_success']);
					currentDate = serverRequest['date'];
					entryField.innerHTML = '';
					break;

				case 'getCurrentDate':

					currentDate = serverRequest['date'];
					// console.log(currentDate);
					break;

				case 'getNewMessages':

					if (serverRequest['request_success'] == 'true') {
						
						var m_index = 0;

						for(m_index in serverRequest['message_list']) {
							messageOutput(serverRequest['message_list'][m_index], 'true')
						}

						currentDate = serverRequest['message_list'][m_index]['date'];
					}
					break;
				case 'getLastMessages':

					if (serverRequest['request_success'] == 'true') {
						
						var m_index = 0;

						for(m_index in serverRequest['message_list']) {
							messageOutput(serverRequest['message_list'][m_index], 'true')
						}

						currentDate = serverRequest['message_list'][m_index]['date'];
					}
					break;
				
				case 'getUsersList':
					if (serverRequest['request_success']) {
						createPoputUserBlock(serverRequest['users_list']);
					}

					break;
				case 'deleteUserFromChat':
					if (serverRequest['request_success']) {
						hidePoputUserBlock(serverRequest['id']);
					}
					break;

				case 'getPoputMenuData':
					
					if (serverRequest['request_success']) {
						updatePoputMenuData(serverRequest['data']);
						getChatUsersList();
					}
					break;

				case 'savePoputMenuData':
					if (serverRequest['request_success']) {
						showSavePoputMenuDataMessage();
					}
					break;

			}
		}

	}

	getCurrentDate();

	if (chatIsOpen) {
		getLastMessages();
		setInterval(getNewMessages, 1000);

		leftarrow = document.getElementById('chatinfo-leftarrow');
	}

	var leftCnt = document.getElementById('left_container');
	var rightCnt = document.getElementById('right_container');

	if (leftarrow) {
		leftarrow.onclick = function() {

			leftCnt.style.display = 'grid';
			rightCnt.style.display = 'none';
		}
	}


	if (window.innerWidth <= 1000 && !chatIsOpen) {
		console.log('text');
		leftCnt.style.display = 'grid';
		rightCnt.style.display = 'none';
	}

	window.onresize = function() {
		// console.log(window.innerWidth);
		if (window.innerWidth > 1000) {
			leftCnt.style.display = 'grid';
			rightCnt.style.display = 'grid';
		}
		else if (chatIsOpen){
			leftCnt.style.display = 'none';
			rightCnt.style.display = 'grid';
		}
		else {
			leftCnt.style.display = 'grid';
			rightCnt.style.display = 'none';
		}


	}


</script>

</body>
</html>