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
            @auth
                <!-- Для авторизованных -->
                <span style="margin-right: 15px; color: #666;">
                {{ Auth::user()->name }}
                </span>
                {{--            <a href="{{ route('dashboard') }}" class="nav-button">--}}
                {{--                Панель--}}
                {{--            </a>--}}
                <a href="{{ route('profile.edit') }}" class="auth-button">Профиль</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="auth-button">
                        Выйти
                    </button>
                </form>
            @else
                <!-- Для гостей -->
                <a href="{{ route('login') }}" class="auth-button">Вход</a>
                <a href="{{ route('register') }}" class="auth-button">Регистрация</a>
            @endauth
        </div>
    </div>
</header>
