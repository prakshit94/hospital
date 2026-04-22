# Divit Hospital AccessHub

Divit Hospital AccessHub is an enterprise-grade hospital management and access control system. It provides a secure, high-performance environment for managing medical staff, health records, and system permissions.

## Key Features

- **RBAC (Role-Based Access Control)**: Granular permission management for all system actions.
- **Enterprise Security**:
    - **2FA (Two-Factor Authentication)**: Mandatory TOTP support for web and API.
    - **Session Hardening**: Sessions bound to IP and User-Agent.
    - **Device Tracking**: Real-time alerts and management of active devices.
- **Detailed Audit Logs**: Full traceability with browser and platform metadata.
- **RESTful API**: Comprehensive API for third-party integrations.
- **Modern UI**: Clean, premium dashboard built with Blade, Alpine.js, and Vanilla CSS.

---

## API Documentation (v1)

### Base URL
`https://your-domain.com/api/v1`

### Authentication

#### Login
`POST /login`
Authenticates a user and returns a Sanctum token.

**Parameters:**
- `email` (string, required)
- `password` (string, required)
- `device_name` (string, optional) - Name of the device requesting access.
- `code` (string, optional) - Required if 2FA is enabled for the account.

**Responses:**
- `200 OK`: Returns success status, token and user object within `data`.
  ```json
  {
      "status": "success",
      "message": "Login successful.",
      "data": {
          "token": "...",
          "user": { ... }
      }
  }
  ```
- `403 Forbidden`: 2FA code required (`status: error`, `requires_2fa: true`).
- `422 Unprocessable Content`: Validation errors or rate limit exceeded.

#### Logout
`POST /logout`
Revokes the current authentication token.
**Requires:** Authentication Header

#### Me
`GET /me`
Returns the currently authenticated user details.
**Requires:** Authentication Header

---

### User Management
*Requires permission: `users.view`, `users.create`, `users.update`, `users.delete`*

- **List Users**: `GET /users`
- **Create User**: `POST /users` (Params: `name`, `email`, `password`, `roles[]`)
- **View User**: `GET /users/{id}`
- **Update User**: `PUT/PATCH /users/{id}`
- **Delete User**: `DELETE /users/{id}`

---

### Health Record Management
*Requires permission: `health_records.view`, `health_records.create`, `health_records.update`, `health_records.delete`*

- **List Records**: `GET /health-records`
  - Filters: `search`, `company_id`, `status`
- **Create Record**: `POST /health-records`
  - Required Params: `company_id`, `employee_id`, `full_name`, `gender`, `dob`, `status`
  - Clinical Params: `height`, `weight`, `bp_systolic`, `bp_diastolic`, `heart_rate`, `medical_history`, etc.
- **View Record**: `GET /health-records/{id_or_uuid}`
- **Update Record**: `PUT/PATCH /health-records/{id_or_uuid}`
- **Delete Record**: `DELETE /health-records/{id_or_uuid}`

---

### Company Management
- **List Companies**: `GET /companies` (Filters: `search`)
- **View Company**: `GET /companies/{id}`

---

### RBAC (Roles & Permissions)
*Requires permission: `roles.view`, `permissions.view`, etc.*

#### Roles
- **List Roles**: `GET /roles`
- **Create Role**: `POST /roles` (Params: `name`, `permissions[]`)
- **View Role**: `GET /roles/{id}`
- **Update Role**: `PUT/PATCH /roles/{id}`
- **Delete Role**: `DELETE /roles/{id}`

#### Permissions
- **List Permissions**: `GET /permissions`
- **Create Permission**: `POST /permissions` (Params: `name`)
- **View Permission**: `GET /permissions/{id}`
- **Update Permission**: `PUT/PATCH /permissions/{id}`
- **Delete Permission**: `DELETE /permissions/{id}`

---

### Security & Auditing
*Requires permission: `activities.view`*

- **Activity Logs**: `GET /activity-logs`
  Returns a list of all system activities, including technical metadata (Browser, IP, Platform).

---

## Installation & Setup

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/your-repo/hospital.git
    ```
2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```
3.  **Environment Setup**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Database Configuration**:
    Configure your `.env` file with your database credentials and run:
    ```bash
    php artisan migrate --seed
    ```
5.  **Run Development Server**:
    ```bash
    php artisan serve
    npm run dev
    ```

---

## Testing with Postman
A pre-configured Postman collection is included in the repository:
`Divit_Hospital_API.postman_collection.json`

To use it:
1.  Import the JSON file into Postman.
2.  Set the `baseUrl` variable to your local or production URL (default is `http://127.0.0.1:8000`).
3.  Use the **Login** request to get a token.
4.  Copy the token and paste it into the `token` variable in the collection settings.

---

## Security Best Practices
- Always use **HTTPS** for API requests.
- Enable **2FA** for all administrative accounts.
- Monitor **Activity Logs** for unrecognized IP addresses.
- Regularly review and revoke **API Tokens** for inactive devices.

---

© 2026 Divit Hospital. All rights reserved.
