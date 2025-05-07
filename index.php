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
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
 }
try {
    global $conn;

    // Заявки на уборку
    $sql = "SELECT * FROM cleaning_requests ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $cleaningRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Заявки на волонтерство
    $sql = "SELECT * FROM volunteer_applications ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $volunteerApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
						width="100px"
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
									echo "<li class='header__nav-item'><button class='header__nav-button button blue-bg' onclick=\"document.getElementById('admin').showModal(); document.body.classList.add('body-no-scroll');\">Админ панель</button></li>
									<li class='header__nav-item'> $name <button class='header__nav-button button blue-bg'  onclick=\"window.location.href='./php/logout.php'\">Выйти</button></li>";
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
                    <img loading="lazy" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" >
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
		<dialog id="admin" class="admin">
		<h1>Админ-панель</h1>

<h2>Заявки на уборку</h2>
<div class="requests__clean">
<table>
    <thead>
        <tr>
            <th>Имя</th>
            <th>Проблема</th>
            <th>Телефон</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Действие</th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cleaningRequests as $request): ?>
            <tr>
                <td><?php echo htmlspecialchars($request['name']); ?></td>
                <td><?php echo htmlspecialchars($request['problem']); ?></td>
                <td><?php echo htmlspecialchars($request['phone']); ?></td>
                <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                <td><?php
                    if ($request['status'] == 'new') {
                        echo 'Новая';
                    } elseif ($request['status'] == 'in_progress') {
                        echo 'В процессе';
                    }elseif ($request['status'] == 'completed') {
                        echo 'Выполнено';
                    }else{
                        echo 'Статус не найден';
                    }
                    ?>
                </td>
				<td class="requests__edit"><a class="button button_tablet" href="./admin/edit_cleaning_request.php?id=<?php echo $request['id']; ?>">Изменить</a>
				<a class="button button_tablet" href="./admin/delete_cleaning_request.php?id=<?php echo $request['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
	
</table>

</div>
<h2>Заявки на волонтерство</h2>
<table>
    <thead>
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Телефон</th>
            <th>Дата</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($volunteerApplications as $application): ?>
            <tr>
                <td><?php echo htmlspecialchars($application['name']); ?></td>
                <td><?php echo htmlspecialchars($application['surname']); ?></td>
                <td><?php echo htmlspecialchars($application['phone']); ?></td>
                <td><?php echo htmlspecialchars($application['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="create-news">
<h1>Создание новости</h1>
    <form action="create_news.php" method="post" enctype="multipart/form-data">
        <label for="title">Заголовок:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="subtitle">Краткое описание:</label><br>
        <textarea id="subtitle" name="subtitle" rows="4" cols="50"></textarea><br><br>

        <label for="content">Текст новости:</label><br>
        <textarea id="content" name="content" rows="10" cols="50" required></textarea><br><br>

        <label for="image">Изображение:</label><br>
        <input type="file" id="image" name="image"><br><br>

        <input type="submit" value="Создать новость">
    </form>
	</div>
            <a class="close-modal button" onclick="document.getElementById('admin').close()" href="../index.php">Выйти</a>
		</dialog>
		<dialog id="edit-form" open>


<h1>Редактирование заявки на уборку</h1>

<form method="post">
    <label for="status">Статус:</label>
    <select name="status" id="status">
        <option value="new" <?php echo ($request['status'] == 'new') ? 'selected' : ''; ?>>Новая</option>
        <option value="in_progress" <?php echo ($request['status'] == 'in_progress') ? 'selected' : ''; ?>>В процессе</option>
        <option value="completed" <?php echo ($request['status'] == 'completed') ? 'selected' : ''; ?>>Выполнена</option>
    </select>
    <br><br>
    <button type="submit">Сохранить</button>
</form>

<a href="index.php">Вернуться в админ-панель</a>
		</dialog>
	</body>
</html>
