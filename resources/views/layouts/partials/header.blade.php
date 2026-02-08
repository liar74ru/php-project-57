<header class="fixed w-full">
    <nav class="bg-white border-gray-200 py-2.5 dark:bg-gray-900 shadow-md">
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
            <!-- Логотип -->
            <a href="{{ url('/') }}" class="text-decoration-none">
                <span class="fw-bold fs-4 text-dark d-none d-sm-inline">{{ __('Task Manager')}}</span>
                <span class="fw-bold fs-4 text-dark d-sm-none">TM</span>
            </a>

            <!-- Центральное меню для десктопов -->
            <div class="d-none d-md-flex align-items-center">
                <nav class="navbar navbar-expand-md p-0">
                    <div class="container-fluid p-0">
                        <ul class="navbar-nav me-auto mb-2 mb-md-0">
                            <li class="nav-item mx-2">
                                <a href="{{ route('tasks.index') }}" class="nav-link text-dark px-3 py-2 rounded">
                                    {{ __('Tasks')}}
                                </a>
                            </li>
                            <li class="nav-item mx-2">
                                <a href="{{ route('task_statuses.index') }}" class="nav-link text-dark px-3 py-2 rounded">
                                    {{ __('Statuses')}}
                                </a>
                            </li>
                            <li class="nav-item mx-2">
                                <a href="{{ route('label.index') }}" class="nav-link text-dark px-3 py-2 rounded">
                                    {{ __('Labels')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Правая часть: кнопка меню + auth + языки -->
            <div class="d-flex align-items-center">

                <!-- Аутентификация и языки -->
                <div class="d-flex align-items-center">
                    <!-- Auth кнопки - скрываем на очень маленьких экранах -->
                    <div class="d-none d-sm-flex align-items-center me-3">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="auth-button">
                                {{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="auth-button ml-2">
                                    {{ __('Logout')}}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="auth-button">
                                {{ __('Login')}}
                            </a>
                            <a href="{{ route('register') }}" class="auth-button ml-2">
                                {{ __('Register')}}
                            </a>
                        @endauth
                    </div>

                    <!-- Переключатель языка -->
                    <div class="btn-group" role="group">
                        <a href="{{ route('locale.set', ['locale' => 'en']) }}"
                           class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            EN
                        </a>
                        <a href="{{ route('locale.set', ['locale' => 'ru']) }}"
                           class="btn btn-sm {{ app()->getLocale() == 'ru' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            RU
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
