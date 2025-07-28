<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Inventory & Stock Management System

This project is a robust web-based application designed for efficient inventory and stock management. Built with the Laravel framework, it offers a streamlined and intuitive interface for tracking items, managing stock movements, and administering user accounts.

## Project Overview

This application serves as a comprehensive solution for warehouse and inventory control. It empowers businesses to meticulously track their assets and manage stock flow with ease. Key functionalities include:

* **Secure User Authentication**: A robust login system ensures secure access and role-based permissions.

* **Intuitive Dashboard**: Provides a real-time overview of critical stock metrics, recent activities, and item summaries.

* **Comprehensive Item Management**: Centralized control over all inventory items, including detailed attributes such as name, type, specifications, volume, and serial numbers.

* **Dynamic Stock Control**: Meticulous tracking of all stock-in and stock-out transactions, ensuring accurate inventory records.

* **Advanced Reporting**: Generate and export essential stock reports in various formats, including PDF and CSV, for analysis and compliance.

## Technology Stack

The system leverages a modern and efficient technology stack to deliver a high-performance and scalable solution:

* **Backend**: PHP 8.x powered by the Laravel Framework (version 10.x or newer).

* **Frontend**: Responsive and interactive user interfaces built with HTML, CSS, and JavaScript.

* **Styling**: Utilizes Tailwind CSS for rapid and consistent UI development.

* **Package Management**: Composer for PHP dependencies and npm for JavaScript libraries.

* **Build Tool**: Vite for a fast and optimized development experience.

* **Database**: Flexible database support, configurable for SQLite (default), MySQL, PostgreSQL, or SQL Server.

* **Reporting**: Integrated with `barryvdh/laravel-dompdf` for generating high-quality PDF reports.

## Getting Started

To set up and run this project on your local machine, please follow these instructions.

### Prerequisites

Ensure you have the following installed:

* PHP (8.1 or higher recommended)

* Composer

* Node.js (LTS version recommended)

* npm (comes with Node.js)

### Installation Steps

1.  **Clone the Repository**:

    ```bash
    git clone [https://github.com/your-username/stokgudang.git](https://github.com/your-username/stokgudang.git) # Replace with your repository URL
    cd stokgudang

    ```

2.  **Install PHP Dependencies**:

    ```bash
    composer install

    ```

3.  **Install JavaScript Dependencies**:

    ```bash
    npm install

    ```

4.  **Environment Configuration**:

    * Create your environment file:

        ```bash
        cp .env.example .env

        ```

    * Open the newly created `.env` file and configure your database connection and other environment variables. By default, it's set up for SQLite, which requires no additional configuration beyond ensuring the `.env` file exists.

5.  **Generate Application Key & Run Migrations**:

    ```bash
    php artisan key:generate
    php artisan migrate

    ```

    This will generate a unique application key and create all necessary database tables (`users`, `items`, `stock_ins`, `stock_outs`).

6.  **Start Development Servers**:
    To run the application, you'll need to start both the Laravel development server and the Vite frontend development server:

    ```bash
    php artisan serve
    npm run dev

    ```

    The application will typically be accessible at `http://127.0.0.1:8000` in your web browser.

## Core Features

### User Management & Authentication

* Secure user login and session management.

* Administrative capabilities for creating, updating, and deleting user accounts.

* User data is securely stored, including `user_id`, `username`, and hashed `password`.

### Dashboard & Analytics

* Centralized dashboard providing real-time insights into inventory status.

* Displays recently added/updated items and a chronological log of stock-in and stock-out activities.

### Inventory Item Management

* Comprehensive item catalog with detailed attributes: `device_name`, `type`, `specifications`, `volume`, `unit`, `serial_number`, and more.

* CRUD (Create, Read, Update, Delete) operations for all inventory items.

### Stock Movement Tracking

* Automated recording of stock movements in dedicated `stock_ins` and `stock_outs` tables.

* Automatic `stock_in` entry upon initial item creation with a specified volume.

* Detailed historical records for all incoming and outgoing stock.

### Reporting & Export

* Generate printable and exportable reports for:

    * Overall inventory summary

    * Detailed stock-in history

    * Detailed stock-out history

* Export options include PDF for professional documents and CSV for data analysis.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
