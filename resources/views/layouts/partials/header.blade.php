<!-- Fixed header -->
<header class="fixed-header">
    <div class="nav-container">
        <!-- Logo on the left -->
        <a href="{{ url('/') }}" class="nav-logo">
            <span>{{ __('Task Manager')}}</span>
        </a>

        <!-- Central menu -->
        <div class="nav-center">
            <a href="{{ route('tasks.index') }}" class="nav-center-item">{{ __('Tasks')}}</a>
            <a href="{{ route('task_statuses.index') }}" class="nav-center-item">{{ __('Statuses')}}</a>
            <a href="#" class="nav-center-item disabled">{{ __('Labels')}}</a>
        </div>

        <!-- Right section with language switcher and auth -->
        <div class="nav-auth">
            <!-- Authentication buttons -->
            @auth
                <!-- For authenticated users -->
                <span style="margin-right: 15px; color: #666;">
                    {{ Auth::user()->name }}
                </span>
                <a href="{{ route('profile.edit') }}" class="auth-button">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="auth-button">
                        {{ __('Logout')}}
                    </button>
                </form>
            @else
                <!-- For guests -->
                <a href="{{ route('login') }}" class="auth-button">{{ __('Login')}}</a>
                <a href="{{ route('register') }}" class="auth-button">{{ __('Register')}}</a>
            @endauth
            <div class="lang-buttons">
                <a href="{{ route('locale.set', ['locale' => 'en']) }}"
                   class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    EN
                </a>
                <a href="{{ route('locale.set', ['locale' => 'ru']) }}"
                   class="lang-btn {{ app()->getLocale() == 'ru' ? 'active' : '' }}">
                    RU
                </a>
            </div>
        </div>
    </div>
</header>
