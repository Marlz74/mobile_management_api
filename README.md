```markdown
# MDM API (v1)

A RESTful API for Mobile Device Management (MDM) built with Laravel 11, implementing device registration, locking, and unlocking with JWT authentication, MySQL database, and standardized JSON responses. This project is submitted for the Celebrare Laravel Developer Internship.

---

## Features

- **API Versioning**: Endpoints under `/api/v1`.
- **Endpoints**:
  - `POST /api/v1/login`: Authenticate users and issue JWT tokens.
  - `POST /api/v1/devices/register`: Register a device (authenticated).
  - `POST /api/v1/devices/{device_id}/lock`: Lock a device (authenticated).
  - `POST /api/v1/devices/{device_id}/unlock`: Unlock a device (authenticated).
- **Authentication**: JWT (`tymon/jwt-auth`) for all device endpoints.
- **Database**: MySQL with `users`, `devices`, and `lock_histories` tables.
- **Response Format**:
  - Standard: `{ status, message, data }`
  - Login: `{ status, message, data: { user, authorization } }`
- **Bonus Features**: Input validation, API resource for devices, simulated external API calls.

---

## Requirements

- PHP 8.2.27
- Laravel 11
- MySQL
- Composer
- Postman (for testing)

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/Marlz74/mobile_management_api.git
cd mobile_management_api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update `.env` with your MySQL credentials and JWT settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mdm_api
DB_USERNAME=your_username
DB_PASSWORD=your_password

JWT_TTL=120
JWT_SECRET=your_generated_secret
```

### 4. Run Migrations and Seed

```bash
php artisan migrate --seed
```

> Seeds an admin user:  
> `email`: **admin@mdm.com**  
> `password`: **password**

### 5. Generate JWT Secret

```bash
php artisan jwt:secret
```

### 6. Start the Server

```bash
php artisan serve
```

> API will be available at `http://127.0.0.1:8000`

---

## Testing with Postman

### 1. Import Collection

Import `mdm-api-v1.postman_collection.json` into Postman.

Set `BASE_URL` environment variable to:

```
http://127.0.0.1:8000
```

### 2. Authenticate

Use `POST /api/v1/login` with:

```json
{
  "email": "admin@mdm.com",
  "password": "password"
}
```

Save the returned JWT token to a Postman variable (e.g., `{{token}}`).

### 3. Test Endpoints

For all device endpoints, include headers:

- `Authorization: Bearer {{token}}`
- `Accept: application/json`

#### Example: Register a Device

`POST /api/v1/devices/register`

```json
{
  "device_id": "DEVICE123",
  "user_name": "John Doe",
  "location": "New York"
}
```

### 4. Unauthenticated Requests

Try hitting protected endpoints **without** the `Authorization` header to verify `401 Unauthorized` response.

---

## Project Structure

- **Controllers**:
  - `app/Http/Controllers/AuthController.php`
  - `app/Http/Controllers/DeviceController.php`
- **Models**:
  - `User`, `Device`, `LockHistory`
- **Resources**:
  - `app/Http/Resources/DeviceResource.php`
- **Helpers**:
  - `app/Helpers/ApiResponse.php`
- **Routes**:
  - `routes/api.php`
- **Database**:
  - Migrations and seeders in `database/`

---

## Notes

- API handles unauthenticated requests with a `401` JSON response:
  ```json
  { "status": false, "message": "Unauthenticated", "data": [] }
  ```
- External API calls for locking/unlocking are simulated using `Illuminate\Support\Facades\Http`.


---


> 💡 *This README is tailored for the Celebrare Laravel Developer Internship — be sure to update the repository URL and submission link before final submission.*

```
