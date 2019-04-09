<!DOCTYPE html>
<html>
<head>
	<title>Имя пользователя</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto|Source+Sans+Pro" rel="stylesheet">
	<script src="index.js"></script>
</head>
<body>
	<div class="header">
		<div class=header-top-div>
			<div class="header-logo">
				<img src="images/header-logo.png" width="58" height="58">
			</div>
			<a href="#" onclick="setting_show()">
			<div class="header-settings"></div></a>
		</div>
		<div class="header-settings-div" id='set-div'>
			<ul class="settings-list">
				<li class="settings-list-item"><a href="profile.html">Моя страница</a></li>
				<li class="settings-list-item"><a href="">Настройки</a></li>
				<li class="settings-list-item"><a href="">Поддержка</a></li>
				<li class="settings-list-item"><a href="">Выйти</a></li>
			</ul>
		</div>

		<div class="header-image">
			<img src="images/user.png" width="150" height="150">
		</div>
		<div class="profile-image"></div>
		<div class="header-bottom-div">
			<div class="header-bottom-buttons">
				<div class="button-div">
					<a href="#dialog" name="dialog" class="dialog-button">Диалоги</a> 
				</div>
				<div class="button-div">
				 <a href="#friend" name="friend"class="friends-button">Друзья</a>
				</div>
			</div>
		</div>  
	</div>

	<div class="main">
		<div class="user">
			<div class="user-name">Имя </div>
			<div class="user-lastname"> Фамилия</div>
		</div>
		<div class="news">
				<div class="news-header">Новости</div>
				<div class="news-header-line"></div>
			<div class="news-post" id="news-post" onclick="news_post()">
				<div class="news-post-img"></div>
				<div class="news-post-date">14.03.2019 10:27</div>
				<div class="news-post-text">23-24 апреля на базе Сыктывкарского госуниверситета имени Питирима Сорокина при поддержке Минфина Республики Коми запланировано проведение Всероссийской научной конференции школьников, студентов и аспирантов «Финансовые технологии и финансовые инновации в индустрии 4.0».</div>
				<div class="feedback">
					<div class="comment">
						<input type="text" name="comment" placeholder="Коментарий..." class="comment-input">
					</div>
					<div class="like-div">
						<div class="like-number">5</div>
						<div class="like" name="like" >
							<a href="#like" title="like">
								<div class="like-icon"></div>
							</a>
						</div>
						<div class="dislike" name="dislike">
							<a href="#dislike" title="dislike">
								<div class="dislike-icon"></div>
							</a>
						</div>
					</div>
				</div>
				<div class="comment-out">
					<div class="comment-out-text"></div>
				</div>
				
			</div>

			<?php
			require_once '../auth/auth-class.php';
			require_once '../connect.php';
			require_once '../like/write-like-module.php';
			require_once 'write-posts.php';
			
			writeOnePost([
				'id' => 5,
				'date_time' => '04.04.2019 14:21',
				'text' => 'тело новости'
			]);
			?>

			<!-- <div class="news-post">
				<div class="news-post-img"></div>
				<div class="news-post-date">14.03.2019 10:27</div>
				<div class="news-post-text">23-24 апреля на базе Сыктывкарского госуниверситета имени Питирима Сорокина при поддержке Минфина Республики Коми запланировано проведение Всероссийской научной конференции школьников, студентов и аспирантов «Финансовые технологии и финансовые инновации в индустрии 4.0».</div>
				<div class="feedback">
					<div class="comment">
						<input type="text" name="comment" placeholder="Коментарий..." class="comment-input">
					</div>
					<div class="like-div">
						<div class="like-number">5</div>
						<div class="like">
							<a href="#" title="like">
								<div class="like-icon"></div>
							</a>
						</div>
						<div class="dislike">
							<a href="#" title="dislike">
								<div class="dislike-icon"></div>
							</a>
						</div>
					</div>
				</div>
				
			</div> -->
			
		</div>
			<div class="news-post-pop-out" id="news-post-pop-out">
				<div class="news-post-pop-out-img"></div>
				<div class="news-post-pop-out-date">14.03.2019 10:27</div>
				<div class="news-post-pop-out-text">23-24 апреля на базе Сыктывкарского госуниверситета имени Питирима Сорокина при поддержке Минфина Республики Коми запланировано проведение Всероссийской научной конференции школьников, студентов и аспирантов «Финансовые технологии и финансовые инновации в индустрии 4.0».</div>

			</div>
		
	</div>



	<div class="footer">
		<div class="copyright"> 
			<span>&copy; 2018</span>
		</div>
		<div class="footer-up-button"></div>
	</div>
</body>
</html>