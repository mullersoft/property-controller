
## 🚀 Features

- Category management (create, list, update, delete categories).
- Property management (track cost, purchase date, status, serial numbers, etc.).
- Employee management (create and manage employees).
- Assignments (assign properties to employees and track usage).
- RESTful API with validation and relationships.
- JSON responses for easy frontend integration (Vue.js or other clients).

---

## 🛠️ Tech Stack

- **Framework:** Laravel 10+
- **Database:** MySQL / PostgreSQL / SQLite (configurable)
- **Auth (Optional):** Laravel Sanctum or Passport
- **Tools:** Composer, Artisan CLI, Faker (factories), Laravel Seeder

---

## 📂 Project Structure

```

backend/
│── app/
│   ├── Models/               # Eloquent models (Category, Property, Employee, Assignment)
│   ├── Http/Controllers/     # API controllers
│   └── Http/Requests/        # Form request validation
│── database/
│   ├── migrations/           # Database tables
│   └── seeders/              # Seed sample data
│── routes/
│   └── api.php               # API endpoints
│── .env.example              # Environment configuration
│── artisan                   # Artisan CLI
└── README.md

````

---

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/mullersoft/property-controller.git
   cd inventory-backend
````

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Configure environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update `.env` with your database settings:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run migrations & seeders**

   ```bash
   php artisan migrate --seed
   ```

5. **Start the server**

   ```bash
   php artisan serve
   ```

   API will run at: `http://127.0.0.1:8000`


## 📌 API Endpoints

### Categories

* `GET /api/categories` – List all categories
* `POST /api/categories` – Create a category
  **Request body:**

  ```json
  { "name": "Electronics" }
  ```

### Properties

* `GET /api/properties` – List all properties
* `POST /api/properties` – Create a property
  **Request body:**

  {
    "name": "Dell Laptop",
    "category_id": 1,
    "purchase_date": "2023-08-15",
    "purchase_cost": 1200,
    "status": "in use",
    "serial_number": "SN123456",
    "model_number": "LAT5420",
    "manufacturer": "Dell",
    "current_value": 950
  }

### Employees

* `GET /api/employees` – List all employees
* `POST /api/employees` – Create employee

### Assignments

* `GET /api/assignments` – List all assignments
* `POST /api/assignments` – Assign a property to employee


## 🧪 Testing with Postman

1. First create categories (`POST /api/categories`).
2. Use the returned `id` for creating properties.
3. Assign properties to employees using `POST /api/assignments`.
4. Verify with `GET` requests.


## 🤝 Contributing

1. Fork the repo
2. Create a new branch (`feature/my-feature`)
3. Commit your changes
4. Push to your fork
5. Open a Pull Request



