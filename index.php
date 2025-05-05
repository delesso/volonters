<?php
session_start();
require_once("./php/db.php");



try {
    global $conn;
    $sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT 6";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlCount = "SELECT COUNT(*) FROM news";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->execute();
    $newsCount = $stmtCount->fetchColumn();

    $displayLimit = 6;

} catch (PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="./css/styles.css" />
		<script src="./js.js"></script>
		<title>КУЗ ВОЛОНТЕРЫ</title>
	</head>
	<body>
		<header class="header">
			<div class="header__inner">
				<div class="header__logo">
					<img
						src="./image/logo.svg"
						alt=""
						width=""
						height=""
						loading="lazy"
					/>
				</div>
				<nav class="header__nav">
					<ul class="header__nav-list">
						 <?php
						 
							if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
								 if (isset($_SESSION['admin'])) {
									$name = htmlspecialchars($_SESSION['name']);
									echo "<li class='header__nav-item'><button onclick=\"window.location.href='./admin/index.php'\">Админ панель</button></li>
									<li class='header__nav-item'> $name <button onclick=\"window.location.href='./php/logout.php'\">Выйти</button></li>";
 									}else{
									$name = htmlspecialchars($_SESSION['name']);
									echo "<li class='header__nav-item'> $name <button onclick=\"window.location.href='./php/logout.php'\">Выйти</button></li>";
								}
							} else {
									echo "<li class='header__nav-item'><a href='#'><img src='./image/icons/account_avatar_people_profile_user_icon_123297.svg' alt='' width='48px' height='48px' loading='lazy'></a></li>";
							}
							?>
					</ul>
				</nav>
			</div>
		</header>
		<main class="main container">
			<section class="banner">
				<div class="banner__inner">
					<div class="banner__img">
						<img
							src="./image/banner.svg"
							alt=""
							srcset=""
							width="269px"
							height="110px"
						/>
					</div>
					<div class="banner__title">
						<p>
							Кузнецкие волонтёры: сила добра в действии<br />

							В самом сердце Сибири, среди живописных пейзажей Кузнецкого края,
							работает удивительное сообщество — Кузнецкие волонтёры. Это
							обычные люди с необычными сердцами, которые ежедневно доказывают:
							даже маленькие поступки могут менять мир к лучшему.<br />

							<strong>Кто они?</strong><br />
							Кузнецкие волонтёры — это студенты, пенсионеры, предприниматели и
							семьи, объединённые желанием помогать. Они:<br />
							✔️ Поддерживают пожилых и людей с инвалидностью<br />
							✔️ Организуют экологические акции (уборка лесов, посадка
							деревьев)<br />
							✔️ Помогают приютам для животных<br />
							✔️ Собирают вещи и продукты для нуждающихся<br />
							✔️ Участвуют в культурных и социальных проектах региона
						</p>
					</div>
				</div>
				<div class="banner__btn">
					<button
						class="banner__button button"
						id="actionButton"
						onclick="window.applicationVol.showModal()"
						type="submit"
					>
						Стать волонтером
					</button>
					<button
						class="banner__button button blue-bg"
						id="cleanupButton"
						onclick="window.rightSidebar.showModal()"
						type="submit"
					>
						Заявка на уборку
					</button>
				</div>
			</section>

			<section class="news">
    <news class="title">
        <h2>Новости</h2>
    </news>
    <div class="news__inner">
        <div class="news__card" id="news-container">
            <?php foreach ($news as $item): ?>
                <div class="news__card-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <div class="news__card-title">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </div>
                    <div class="news__card-subtitle">
                        <?php echo htmlspecialchars($item['subtitle']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($newsCount > $displayLimit): ?>
            <button class="news__button button" id="load-more-button">Все новости</button>
        <?php endif; ?>

    </div>
</section>
		</main>
		<footer class="footer">
			<div class="footer__inner">
				<div class="footer__logo">
					<img src="./image/logo.svg" alt="" />
				</div>
				<button
					class="footer__button button"
					onclick="window.rightSidebar.showModal()"
				>
					Заявка на уборку
				</button>
				<div class="footer__title">
					<h2>Над проектом работали:</h2>
					<p>Яфаров Салават</p>
					<p>Фигуров Никита</p>
				</div>
			</div>
		</footer>
		<div id="authModal" class="modal">
			<div class="modal__content">
				<span class="modal-close">&times;</span>

				<div class="auth-tabs">
					<button class="tab-btn active" data-tab="login">Вход</button>
					<button class="tab-btn" data-tab="register">Регистрация</button>
				</div>

				<div id="login" class="tab-content active">
					<h2>Вход в аккаунт</h2>
					<form class="auth-form" method="post" action="./php/login.php">
						<input type="email" placeholder="Email" name="email" required />
						<input
							type="password"
							placeholder="Пароль"
							name="password"
							required
						/>
						<button type="submit" name = "login">Войти</button>
						<a href="#" class="forgot-password">Забыли пароль?</a>
					</form>
				</div>

				<div id="register" class="tab-content">
					<h2>Создать аккаунт</h2>
					<form class="auth-form" method="post" action="./php/register.php">
						<input type="text" placeholder="Имя" name="name" required />
						<input type="email" placeholder="Email" name="email" required />
						<input
							type="password"
							placeholder="Пароль"
							name="password"
							required
						/>
						<input
							type="password"
							placeholder="Повторите пароль"
							name="repeatpassword"
							required
						/>
						<button type="submit" name = "register">Зарегистрироваться</button>
					</form>
				</div>
			</div>
		</div>
		<dialog id="rightSidebar" class="right-sidebar">
    <h2>Заявка на уборку</h2>
    <span onclick="window.rightSidebar.close()">Закрыть</span>
    <form action="./php/submit_cleaning_request.php" method="post"> 
        <div class="form-input">
            <label for="name">Введите ваше имя: </label>
            <input type="text" name="name" id="name" required />
        </div>
        <div class="form-input">
            <label for="problem">Введите вашу проблему: </label>  
            <input type="text" name="problem" id="problem" required /> 
        </div>
        <div class="form-input">
            <label for="phone">Введите номер для связи: </label> 
            <input type="text" name="phone" id="phone" required /> 
        </div>
        <button class="form-button button">Отправить</button>
    </form>
</dialog>
<dialog id="applicationVol" class="right-sidebar">
    <h2>Стать волонтером</h2>
    <span onclick="window.applicationVol.close()">Закрыть</span>
    <form action="./php/submit_volunteer_application.php" method="post"> 
        <div class="form-input">
            <label for="name">Введите ваше имя: </label>
            <input type="text" name="name" id="name" required />
        </div>
        <div class="form-input">
            <label for="surname">Введите вашу фамилию: </label> 
            <input type="text" name="surname" id="surname" required /> 
        </div>
        <div class="form-input">
            <label for="phone">Введите номер для связи: </label> 
            <input type="text" name="phone" id="phone" required />
        </div>
        <button class="form-button button">Отправить</button>
    </form>
</dialog>
<?php
		if(!isset($_SESSION['id']) && !isset($_SESSION['email'])){
			echo'
		<div id="notification" class="notification hidden">
			<p>Вы должны быть зарегистрированы!</p>
		</div>
		';
		}
		?>

		        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const loadMoreButton = document.getElementById('load-more-button');
            const newsContainer = document.getElementById('news-container');
            if (loadMoreButton && newsContainer) {
              loadMoreButton.addEventListener('click', function() {
                fetch('./php/load_more_news.php')
                  .then(response => response.text())
                  .then(data => {
                    newsContainer.innerHTML += data;
                    loadMoreButton.style.display = 'none';
                  })
                  .catch(error => console.error('Error:', error));
              });
            }
          });
        </script>
	</body>
</html>
