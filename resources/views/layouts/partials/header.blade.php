<!-- Фиксированный хедер -->
<header class="fixed-header">
    <div class="nav-container">
        <!-- Логотип слева -->
        <a href="{{ url('/') }}" class="nav-logo">
            <span>Менеджер задач</span>
        </a>

        <!-- Центральное меню -->
        <div class="nav-center">
            <a href="#" class="nav-center-item disabled">Задачи</a>
            <a href="#" class="nav-center-item disabled">Статусы</a>
            <a href="#" class="nav-center-item disabled">Метки</a>
        </div>

        <!-- Кнопки авторизации справа -->
        <div class="nav-auth">
            <a href="#" class="nav-login disabled">Вход</a>
            <a href="#" class="nav-register disabled">Регистрация</a>
        </div>
    </div>
</header>
