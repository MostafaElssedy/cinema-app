# 🎬 CineMax - Cinema Booking & Movie Discovery Platform

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Gemini AI](https://img.shields.io/badge/Gemini_AI-Flash_Lite-4285F4?style=for-the-badge&logo=google&logoColor=white)

**CineMax** is a modern, full-featured Cinema Management and Seat Reservation web application built with **Laravel 11** and **Tailwind CSS v4**. It delivers an immersive cinema experience with interactive 3D seat selection, real-time ticket issuance with QR codes, YouTube trailer previews, Gemini AI-powered movie assistance, live admin financial analytics, and a comprehensive RESTful API.

---

## ✨ Key Features

- 🎟️ **Interactive Cinema Seat Reservation**:
  - Live curved 3D cinema screen visualization.
  - Interactive seat categorization (VIP, Premium, Standard) with distinct pricing.
  - Concurrency & double-booking prevention using database transactions (`lockForUpdate`).
  - Strict booking rules (maximum limit of 8 seats per transaction).
- 📱 **Digital E-Ticket with QR Code**:
  - Instant ticket generation with unique booking reference code.
  - Live scannable QR Code for gate entrance verification.
  - **Download Ticket as Image (PNG)** using client-side canvas rendering.
- 🎬 **Watch Trailer Modal**:
  - Pop-up trailer player with automatic YouTube video embedding.
  - Autoplay support, backdrop blur, and responsive 16:9 aspect ratio.
- 🤖 **AI Cinema Assistant (Google Gemini AI)**:
  - Smart chatbot integrated to answer movie inquiries, plot summaries, and recommendations.
  - Conversation context memory and quick prompt suggestions.
- 🔖 **Device-based Watchlist**:
  - Bookmark favorite movies without registration using persistent unique device identifiers (`X-Device-Id`).
- 🛡️ **Admin Dashboard & Analytics**:
  - **Live Business Analytics**: Real-time stats on total revenue, total bookings, movie ratings, and watchlists.
  - **Recent Bookings Feed**: Live monitoring of customer reservations, theaters, and booked seats.
  - **Movie Management (CRUD)**: Create, update, and manage movies with poster previews and trailer formatting.
  - **Security**: Dedicated admin authentication and profile management.
- ⚡ **RESTful API**:
  - Clean JSON endpoints for movies, showtimes, seats, AI chatbot, and watchlists.
- 🧪 **Automated Testing Suite**:
  - Full Unit and Feature tests covering booking workflows, double-booking prevention, trailer formatting, and Form Request validations.

---

## 🛠️ Tech Stack & Architecture

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS v4, Vite, FontAwesome 6, Html2Canvas
- **Database**: MySQL (Development & Production), SQLite (In-Memory Automated Testing)
- **Design Pattern**: MVC, Form Request Validation (SRP), Eloquent ORM & Resources
- **AI Integration**: Google Gemini API (`gemini-flash-lite-latest`)

---

## 🚀 Getting Started

Follow these steps to set up and run CineMax locally:

### 1. Clone the repository
```bash
git clone https://github.com/MostafaElssedy/cinema-app.git
cd cinema-app
```

### 2. Install PHP & Frontend dependencies
```bash
# Install PHP packages
composer install

# Install Node/NPM dependencies
npm install
```

### 3. Setup environment configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database & AI Key
Open your `.env` file and set your database credentials and optional Gemini API key:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinemax
DB_USERNAME=root
DB_PASSWORD=

# Gemini AI (Optional - for Chatbot Assistant)
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemini-flash-lite-latest
```

### 5. Run Migrations & Seed Data
```bash
php artisan migrate --seed
```
> **Default Admin Credentials:**
> - **Email**: `admin@cinema.com`
> - **Password**: `password`

### 6. Build Assets & Start Development Server

You can build the production frontend assets and run the Laravel server:
```bash
# Build frontend assets
npm run build

# Start Laravel development server
php artisan serve
```

*(Optional: For active development with hot-reloading, run `npm run dev` in a separate terminal).*

Visit **`http://localhost:8000`** in your browser to explore CineMax.

---

## 📡 RESTful API Reference

CineMax exposes clean JSON endpoints for integration:

| Method | Endpoint | Description | Headers / Payload |
|---|---|---|---|
| `GET` | `/api/movies` | Get list of all movies with showtimes | None |
| `GET` | `/api/movies/{id}` | Get detailed movie info & trailer | None |
| `GET` | `/api/showtimes/{id}/seats` | Fetch booked seat numbers for a showtime | None |
| `POST` | `/api/chatbot` | Chat with Gemini AI movie assistant | `{"message": "string"}` |
| `GET` | `/api/watchlist` | Get saved movies for current device | `X-Device-Id: {device_id}` |
| `POST` | `/api/watchlist` | Add movie to watchlist | `{"movie_id": 1}`, `X-Device-Id: {device_id}` |
| `DELETE` | `/api/watchlist` | Remove movie from watchlist | `{"movie_id": 1}`, `X-Device-Id: {device_id}` |

---

## 🧪 Running Automated Tests

Run the complete automated test suite (Unit & Feature tests):
```bash
php artisan test
```

---

## 📄 License
This project is open-sourced under the [MIT license](LICENSE).
