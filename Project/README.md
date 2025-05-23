# CMS ITLabPortal

**CMS ITLabPortal** – це сучасна система управління контентом (CMS), що дозволяє ефективно керувати веб-ресурсами.

## Особливості
- Гнучке управління контентом
- Підтримка багатокористувацького доступу
- Інтуїтивно зрозумілий інтерфейс
- Висока безпека та відповідність GDPR
- API для інтеграції з іншими сервісами

## Конфігурація та Ядро Системи

### Конфігураційний файл
Конфігураційний файл знаходиться за шляхом `./cms_itlabstudio/core/Config.php`. Цей файл відповідає за налаштування основних параметрів системи.
### Ядро системи
Ядро системи знаходиться в директорії `./cms_itlabstudio/core/Core.php`. Ця частина відповідає за основну функціональність та взаємодію з іншими компонентами.

## Встановлення
### Вимоги:
- PHP 8.3+
- Composer
- MySQL/MariaDB
- WAMP/XAMPP (для локального тестування)

### Команди встановлення:
```bash
git clone https://github.com/your-repository/CMS_ITLabPortal.git
cd CMS_ITLabPortal
composer install
```
*Перед запуском налаштуйте .env файл!*

### Запуск:
```bash
php -S localhost:8000
```

## Основні маршрути API / CMS

### Аутентифікація
- **POST** `/login` — Авторизація користувача
- **POST** `/register` — Реєстрація нового користувача
- **POST** `/logout` — Вихід із системи

### Оголошення
- **GET** `/announcements` — Отримати список оголошень
- **GET** `/announcements/{id}` — Отримати конкретне оголошення
- **POST** `/announcements/add` — Додати нове оголошення
- **PUT** `/announcements/edit/{id}` — Редагувати оголошення
- **DELETE** `/announcements/delete/{id}` — Видалити оголошення

### Користувачі
- **GET** `/users` — Отримати список користувачів
- **GET** `/users/{id}` — Отримати дані конкретного користувача
- **PUT** `/users/edit/{id}` — Редагувати профіль користувача
- **DELETE** `/users/delete/{id}` — Видалити користувача

## Ліцензія

Цей проєкт ліцензовано відповідно до [LICENSE.txt](./LICENSE.txt).  
Ознайомтеся з повними умовами перед використанням.

# Автор

**Трофімчук М.О.**  
**Email:** [ipz222_tmo@student.ztu.edu.ua]()  
**GitHub:** [https://github.com/MykolaTrofimchuk](https://github.com/MykolaTrofimchuk)