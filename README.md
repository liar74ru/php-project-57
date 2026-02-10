# 🚀 Task Manager (Hexlet PHP/Laravel Project)

![PHP](https://img.shields.io/badge/PHP-8.4.16-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tests](https://img.shields.io/badge/tests-29_passed,_114_assertions-green?style=for-the-badge)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=liar74ru_php-project-57&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=liar74ru_php-project-57)

> Четвертый учебный проект в рамках курса Hexlet - менеджер задач на Laravel

Простой, но функциональный менеджер задач с полным набором CRUD-операций, аутентификацией и гибкой системой управления задачами.

**Демо**: [https://php-project-57-1-jxe7.onrender.com](https://php-project-57-1-jxe7.onrender.com)

## ✨ Основные возможности

### 👥 Пользователи
- Регистрация с подтверждением email
- Аутентификация и авторизация
- Редактирование данных пользователя
- Ролевая модель (создатель/исполнитель задачи)

### 📋 Задачи
- Создание, редактирование, удаление задач
- Назначение исполнителя
- Привязка к статусу
- Множественное тегирование метками
- Фильтрация по различным параметрам

### 🛡️ Безопасность
- **Laravel Policies** - система управления правами доступа
- Защита маршрутов middleware
- Валидация данных на стороне сервера
- CSRF защита всех форм

### 🏷️ Система организации
- **Статусы задач** (Новая, В работе, На проверке, Завершена)
- **Метки (теги)** для категоризации
- Гибкие связи между сущностями

## 🛠 Технологический стек

### Backend
- **PHP 8.4.16** - современная версия PHP
- **Laravel 12.50.0** - PHP-фреймворк
- **SQLite** - для разработки (Dev)
- **PostgreSQL** - для продакшена (Prod)

### Frontend
- **Blade** - шаблонизатор Laravel
- **Bootstrap** - CSS-фреймворк

### Безопасность
- **Laravel Policies** - система прав доступа
- **Middleware** - защита маршрутов
- **Валидация** - надежная проверка данных

### Инструменты и библиотеки
- **Laravel Breeze** - стартовый набор аутентификации
- **Spatie Query Builder** - удобное построение запросов
- **Laracasts Flash** - уведомления для пользователей
- **Sentry** - мониторинг ошибок
- **PHP_CodeSniffer** - проверка кодстайла (PSR-12)

### Тестирование
- **PHPUnit** - unit и feature тесты

## 📦 Установка и настройка

### Предварительные требования
- PHP 8.4+
- Composer
- Node.js & npm
- SQLite (для разработки)

### Быстрый старт

```bash
# Клонирование репозитория
git clone git@github.com:liar74ru/php-project-57.gitl
cd php-project-57

# Установка зависимостей и настройка
make setup

# Запуск сервера разработки
make start

# Запуск фронтенда (в отдельном терминале)
make start-frontend
```

### Струтура проекта

```bash
php-project-57/
├── app/
│   ├── Http/          # Контроллеры и middleware
│   ├── Models/        # Eloquent модели
│   ├── Policies/      # Политики доступа (Laravel Policies)
│   ├── Providers/     # Service providers
│   └── View/          # Blade компоненты
├── config/            # Конфигурации
├── database/
│   ├── factories/     # Фабрики для тестов
│   ├── migrations/    # Миграции БД
│   └── seeders/       # Наполнители БД
├── public/            # Публичные файлы
├── resources/
│   ├── css/           # Стили
│   ├── js/            # JavaScript
│   └── views/         # Blade шаблоны
├── routes/            # Маршруты
├── storage/           # Файлы и логи
└── tests/             # Тесты
```

### 🛠 Команды Makefile
```bash
make start           # Запуск сервера разработки
make start-frontend  # Запуск фронтенда
make setup           # Полная установка проекта
make test            # Запуск тестов
make lint            # Проверка кодстайла (PSR-12)
make lint-fix        # Автоисправление кодстайла
make ide-helper      # Генерация хелперов для IDE
make console         # Открыть tinker консоль
make log             # Просмотр логов в реальном времени
```

### Покрытие тестами
- ✅ Unit тесты: Полное покрытие моделей
- ✅ Feature тесты: Все CRUD-операции
- ✅ Browser тесты: Selenium-тесты интерфейса
- ✅ Общее покрытие: > 80% (см. coverage-отчет)

## 🎯 Чему я научился в этом проекте

### Основы Laravel
- ✅ Eloquent ORM и работа с моделями
- ✅ Миграции базы данных и сидеры
- ✅ Валидация данных и обработка форм
- ✅ Пагинация и фильтрация данных
- ✅ Локализация и мультиязычность

### Безопасность и авторизация
- ✅ **Laravel Policies** - реализация системы прав доступа
- ✅ Ролевая модель на основе политик (создатель/исполнитель)
- ✅ Middleware для защиты маршрутов
- ✅ Валидация уникальности с игнорированием текущей записи

### Политики доступа (Laravel Policies)
Реализована гибкая система прав доступа:
- **Задачи**:
    - Просмотр: все пользователи (включая неавторизованных)
    - Создание/редактирование: любой авторизованный пользователь
    - Удаление: только создатель задачи
- **Статусы задач**:
    - Просмотр: все пользователи
    - Создание/редактирование/удаление: авторизованные пользователи
    - Дополнительная проверка: нельзя удалить статус, используемый в задачах
- **Метки (теги)**:
    - Просмотр: все пользователи
    - Создание/редактирование/удаление: авторизованные пользователи

### Frontend и UX
- ✅ Работа с Bootstrap 5
- ✅ Blade компоненты и шаблоны
- ✅ Адаптивный дизайн
- ✅ Flash-уведомления
- ✅ Формы с сохранением данных при ошибках

### DevOps и инструменты
- ✅ Деплой приложения на Render.com
- ✅ Настройка CI/CD через GitHub Actions
- ✅ Мониторинг ошибок с Sentry
- ✅ Статический анализ кода с PHPStan
- ✅ Проверка кодстайла (PSR-12)

## 📊 Статус проекта


[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=liar74ru_php-project-57&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=liar74ru_php-project-57)
![PHP Version](https://img.shields.io/badge/PHP-8.4.16-777BB4)
![Laravel Version](https://img.shields.io/badge/Laravel-12.50.0-FF2D20)
