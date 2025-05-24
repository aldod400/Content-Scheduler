# 📅 Content Scheduler - Laravel Challenge

A simplified content scheduling platform built using Laravel + Blade UI.  
Users can create and manage posts across multiple social platforms, schedule them, and view insightful analytics — all with clean architecture and best practices.

---

## 🚀 Features

-   🔐 **Authentication** using Laravel Sanctum
-   📝 **Post Management**
    -   Create / Edit / Delete posts
    -   Schedule posts with datetime picker
    -   Upload image and select platforms
    -   Character counter for content limits
-   📊 **Dashboard**
    -   Cards: Total Posts / Scheduled / Connected Platforms
    -   List of recent posts with filters
    -   Calendar view for scheduled posts
-   🔧 **Platform Settings**
    -   List available platforms
    -   Toggle platform access for each user
-   📈 **Post Analytics**
    -   Posts per platform
    -   Success rate vs scheduled
-   📉 **Rate Limiting**
    -   Max 10 scheduled posts per day
    -   Live progress bar + alert UI
-   📜 **Activity Log**
    -   Logged user actions: create/edit/delete post, toggle platform...
-   🧼 **Clean Architecture**
    -   Repository Pattern
    -   Service Layer
    -   SOLID Principles
    -   Form Request Validation

---

## 🛠 Installation Instructions

1. Clone the repository:

    ```bash
    git clone https://github.com/aldod400/Content-Scheduler.git
    cd content-scheduler
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Set up environment:

    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan storage:link
    ```

4. Set your DB credentials in `.env`, then run:

    ```bash
    php artisan migrate --seed
    ```

5. Run the project:

    ```bash
    php artisan serve
    ```

6. Start scheduler (for publishing scheduled posts):
    ```bash
    php artisan schedule:work
    php artisan queue:work
    ```

---

## 🧪 Test Account

> Default seeded user:

-   Email: `admin@admin.com`
-   Password: `password`

---

## 📡 API Endpoints

### 🔐 Authentication

| Method | Endpoint        | Description          |
| ------ | --------------- | -------------------- |
| POST   | `/api/login`    | User login (Sanctum) |
| POST   | `/api/register` | User registration    |
| GET    | `/api/profile`  | Get user profile     |
| PUT    | `/api/profile`  | Update user profile  |

---

### 📝 Posts

| Method | Endpoint          | Description                    |
| ------ | ----------------- | ------------------------------ |
| GET    | `/api/posts`      | List posts with filters        |
| POST   | `/api/posts`      | Create new post                |
| PUT    | `/api/posts/{id}` | Update post (if not published) |
| DELETE | `/api/posts/{id}` | Delete post (if not published) |

**Filters supported:**

-   `status=draft|scheduled|published`
-   `from_date=YYYY-MM-DD`
-   `to_date=YYYY-MM-DD`

---

### 📦 Platforms

| Method | Endpoint                     | Description                       |
| ------ | ---------------------------- | --------------------------------- |
| GET    | `/api/platforms`             | List all available platforms      |
| GET    | `/api/platforms/my`          | Get user’s connected platforms    |
| POST   | `/api/platforms/toggle/{id}` | Toggle specific platform for user |

---

## 📜 Activity Logs

-   Logged for: post creation, update, delete, profile update...
-   Stored in `activity_logs` table
-   Viewable via web UI (`/logs`)

---

## 📈 Post Analytics

-   Graphs for number of posts per platform
-   Pie chart for scheduled vs published
-   Shown on analytics tab inside dashboard

---

## 🧠 Design Decisions & Trade-offs

-   🧱 Chose Blade instead of SPA (Vue/React) to keep frontend fast and integrated with Laravel
-   💡 Used Repository Pattern to decouple DB from logic
-   🧠 Service Layer handles business logic and authorization
-   ✍️ FormRequest handles validation rules cleanly
-   ⚖️ Trade-off: Blade doesn’t support dynamic interactivity like Vue, but it’s faster to ship + clean

---

## 📹 Demo Video

👉 **Demo video showing all features and UI in action will be added here**  
📌 _[Add Google Drive or YouTube link after recording]_

---

## 📁 Project Structure

```bash
app/
├── Http/Controllers/Web/      # Blade UI controllers
├── Http/Controllers/Api/      # API controllers
├── Services/                  # Business logic (interface + impl)
├── Repository/                # Repositories (interface + impl)
├── Helpers/                   # Image + Log helpers
├── Models/                    # Eloquent models
routes/
├── web.php                    # Blade UI routes
├── api.php                    # Sanctum APIs
resources/views/               # Blade components
```

---

## 🧑‍💻 Author

**Abdelrahman Elghonemy**  
Backend Developer | Laravel Enthusiast  
[GitHub](https://github.com/aldod400)  
[LinkedIn](https://www.linkedin.com/in/abdelrahman-elghonemy-074867242)

---

## 🏁 Final Notes

> This challenge was implemented with full feature coverage and a clean, maintainable codebase, showcasing real-world Laravel practices.
