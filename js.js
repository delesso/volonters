document.getElementById('admin').addEventListener('close', function() {
  document.body.classList.remove('body-no-scroll');
});
document.getElementById('admin').addEventListener('showModal', function() {
  document.body.classList.add('body-no-scroll');
});
document.body.classList.add('body-no-scroll');

document.body.classList.remove('body-no-scroll')
document.addEventListener('DOMContentLoaded', function() {
    const iconLink = document.querySelector('.header__nav-item a');
    const modal = document.getElementById('authModal');
    const closeBtn = document.querySelector('.modal-close');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    iconLink.addEventListener('click', function(e) {
      e.preventDefault();
      modal.style.display = 'block';
      document.querySelector('.tab-content').classList.add('active');
    });
    closeBtn.addEventListener('click', function() {
      modal.style.display = 'none';
    });
    tabBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const tabId = this.getAttribute('data-tab');
        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(tabId).classList.add('active');
      });
    });
    window.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && modal.style.display === 'block') {
        modal.style.display = 'none';
      }
    });
  });
  document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.querySelector('open-rightMenu');
    const sidebar = document.getElementById('rightSidebar');
    const closeButton = document.querySelector('.sidebar-close');
    const overlay = document.createElement('div');
    const actionButton = document.getElementById("actionButton");
const cleanupButton = document.getElementById("cleanupButton");
const notification = document.getElementById("notification");

function showNotification() {
  notification.classList.remove("hidden");
  notification.classList.add("show");

  // Автоматически скрываем через 3 секунды
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => notification.classList.add("hidden"), 300); // Ждём завершения анимации
  }, 3000);
}

// Вешаем обработчики на обе кнопки
actionButton.addEventListener("click", showNotification);
cleanupButton.addEventListener("click", showNotification);

    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    menuButton.addEventListener('click', function(e) {
      e.preventDefault();
      sidebar.classList.add('open');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
 
    function closeSidebar() {
      sidebar.classList.remove('open');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
    
    closeButton.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && sidebar.classList.contains('open')) {
        closeSidebar();
      }
    });
  });
;