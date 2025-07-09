# 📘 Laravel API for Posts, Tasks & User Authentication

This API allows users to register, login, manage posts, and track tasks with completion status. It is built with Laravel and follows RESTful API principles.

---

## 🌐 Base URL

Assuming the Laravel development server is running:

```
http://127.0.0.1:8000/api
```

---

## 👤 1. User Authentication Endpoints

### 🔸 Register
- **POST** `/register`
- **Description:** Create a new user account.

**Request Body:**
```json
{
  "name": "Abdul Quader",
  "email": "abdulquader@gmail.com",
  "password": "12345678"
}
```

**Response (201):**
```json
{
  "id": 1,
  "name": "Abdul Quader",
  "email": "abdulquader@gmail.com"
}
```

---
### 🔸 Login
- **POST** `/login`
- **Description:** Log in and receive an API token.

**Request Body:**
```json
{
  "email": "abdulquader@gmail.com",
  "password": "12345678"
}
```

**Response (200):**
```json
{
  "user": {
      "id": 1,
      "name": "Abdul Quader",
      "email": "abdulquader@gmail.com",
      "email_verified_at": null,
      "created_at": "2025-07-09T05:57:12.000000Z",
      "updated_at": "2025-07-09T05:57:12.000000Z"
  },
  "token": "1|MNzeMSiTjKQJ45Xo1RPcOgJgyMCna8TS5XzJ00DU304292ec",
  "message": "Logged in successfully."
}
```

---
### 🔸 Get Authenticated User
- **GET** `/user`
- **Header Required:** Bearer Token
- **Description:** Get the current authenticated user details.

---
### 🔸 Logout
- **POST** `/logout`
- **Header Required:** Bearer Token
- **Description:** Log out by revoking the current token.

---
## 📝 2.  Post Endpoints

### 🔸 Create a  Post
- **POST** `/posts`

**Request Body:**
```json
{
  "title": "My New  Post Title",
  "content": "This is the Post in where i have disscussed about Interface"
}
```

**Response (201):**
```json
{
  "id": 1,
  "title": "My New  Post Title",
  "content": "This is the Post in where i have disscussed about Interface",
}
```

---
### 🔸 List All  Posts
- **GET** `/posts`
- **Description:** Retrieve all  posts.

---

### 🔸 View a Single  Post
- **GET** `/posts/{id}`
- **Description:** Retrieve  post by ID.

**Example:**  
`GET http://127.0.0.1:8000/api/posts/1`

---

## ✅ 3. Task Management Endpoints

### 🔸 Add a Task
- **POST** `/tasks`

**Request Body (with optional `is_completed`):**
```json
{
  "title": "Prepare presentation"
}
```

**Response (201):**
```json
{
  "id": 1,
  "title": "Prepare presentation",
  "is_completed": false,
}
```

---
### 🔸 List Pending Tasks
- **GET** `/tasks`
- **Description:** Get all incomplete tasks (`is_completed: false`).

---

### 🔸 Mark Task as Completed
- **PATCH** `/tasks/{id}`

**Request Body:**
```json
{
  "is_completed": true
}
```

**Response (200):**
```json
{
  "id": 1,
  "title": "Prepare presentation",
  "is_completed": true,
}
```
---
## 📌 Notes

- Ensure `Accept: application/json` header is set in API clients like Postman.
- Use Laravel Sanctum for token management.

---
## 🛠 Technologies Used
- Laravel (API)
- PHP
- MySQL (assumed)
- JSON Web Tokens (for authentication)
