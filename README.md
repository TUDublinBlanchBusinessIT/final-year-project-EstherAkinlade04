# Vault Fitness Booking System

> A modern full-stack fitness class booking and gym management platform developed using Laravel, Stripe, and MySQL.

---

## Project Overview

The Vault Fitness Booking System is a responsive web application designed to modernise the way gyms, fitness studios, and independent instructors manage class bookings, memberships, attendance, and payments. The system replaces outdated manual processes such as phone bookings, spreadsheets, and social media messaging with a fully integrated digital platform.

The application was developed as part of an academic Business & IT project at Technological University Dublin and focuses on improving operational efficiency, reducing booking errors, enhancing customer experience, and supporting business growth within the fitness industry.

The platform supports:

* Real-time fitness class booking
* Membership management
* Secure online payments
* Attendance tracking
* Admin analytics dashboards
* Automated notifications and confirmations
* Multi-gym support
* Smart class recommendations

The system was designed using modern software engineering principles including MVC architecture, responsive design, secure authentication, and scalable database management.

---

## Overview

The Vault Fitness Booking System is a full-stack gym and fitness class management platform built with Laravel. The system allows fitness centres, instructors, and members to manage class bookings, memberships, payments, notifications, and attendance through a modern responsive web application.

The platform was designed to improve operational efficiency for fitness businesses by replacing outdated manual booking systems with a secure and scalable digital solution.

---

# Features

## Member Features

* User registration and secure login
* Membership management
* Browse available fitness classes
* Book and cancel classes
* Smart class recommendations
* Stripe payment integration
* Booking confirmation emails
* Calendar event downloads
* Membership renewal system
* Responsive mobile-friendly interface

## Admin Features

* Admin dashboard with analytics
* Revenue tracking and reporting
* User management
* Fitness class management
* Booking management
* Attendance tracking
* Gym switching support
* CSV revenue export
* Search functionality
* Membership plan management
* Class cancellation system

## Smart Features

* AI-style class recommendations based on booking history
* Revenue insights
* Membership statistics
* Booking heatmaps
* Performance analytics
* Growth tracking
* Peak-time analysis

---

# Technologies Used

## Backend

* PHP 8+
* Laravel Framework
* MySQL
* Eloquent ORM

## Frontend

* Blade Templates
* Bootstrap / Tailwind CSS
* JavaScript

## Payment Integration

* Stripe Checkout API

## Other Tools

* Carbon (date handling)
* Mail Notifications
* CSV Export
* Authentication & Middleware
* Google Maps API integration
* Location-based gym services
* Interactive gym location mapping
---

# System Architecture

The system follows the MVC (Model-View-Controller) architecture provided by Laravel.

## Main Components

### Controllers

* AuthController
* AdminController
* BookingController
* ClassesController
* PaymentController
* DashboardController

### Models

* User
* FitnessClass
* Booking
* Gym
* MembershipPlan

### Middleware

* AdminMiddleware
* CheckMembership

---

# Installation

## 1. Clone Repository

```bash
git https://github.com/TUDublinBlanchBusinessIT/final-year-project-EstherAkinlade04.git
```

## 2. Navigate to Project

```bash
cd vault-fitness-booking-system
```

## 3. Install Dependencies

```bash
composer install
npm install
```

## 4. Create Environment File

```bash
cp .env.example .env
```

## 5. Generate Application Key

```bash
php artisan key:generate
```

## 6. Configure Database

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vault_fitness
DB_USERNAME=root
DB_PASSWORD=
```

## 7. Run Migrations

```bash
php artisan migrate
```

## 8. Seed Database (Optional)

```bash
php artisan db:seed
```

## 9. Start Development Server

```bash
php artisan serve
```

---

# Stripe Configuration

Add your Stripe keys to `.env`:

```env
STRIPE_KEY=your_public_key
STRIPE_SECRET=your_secret_key
```

---

# Email Configuration

Configure mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@vaultfitness.com"
MAIL_FROM_NAME="Vault Fitness"
```

---

# Database Structure

## Main Tables

* users
* fitness_classes
* bookings
* gyms
* membership_plans

## Relationships

* Users belong to gyms
* Users can book many classes
* Classes belong to gyms
* Classes have many bookings
* Bookings connect users and classes

---

# Authentication & Security

* Laravel authentication system
* Password hashing
* CSRF protection
* Middleware-based access control
* Admin role protection
* Membership validation
* Secure Stripe payments

---

# Admin Dashboard Analytics

The admin dashboard includes:

* Total users
* Total bookings
* Revenue reports
* Monthly revenue charts
* Membership breakdowns
* Popular classes
* Attendance tracking
* Class performance metrics
* Growth analytics
* Smart insights

---

# Booking System Rules

The booking system prevents:

* Booking expired memberships
* Booking cancelled classes
* Booking past classes
* Duplicate bookings
* Over-capacity bookings

---

# Project Goals

The system was developed to:

* Modernise gym booking systems
* Reduce manual administration
* Improve customer experience
* Increase operational efficiency
* Support smaller fitness businesses
* Provide scalable digital fitness management

---

# Future Improvements

Potential future features include:

* Mobile application
* QR check-in system
* Push notifications
* AI-powered recommendations
* Loyalty rewards
* Nutrition tracking
* Live virtual classes
* Wearable device integration
* Multi-language support

---

# Testing

The system includes:

* Functional testing
* Authentication testing
* Booking validation testing
* Payment workflow testing
* Dashboard testing
* Membership validation testing

---

# Deployment

Recommended deployment stack:

* VPS or Cloud Hosting
* Nginx or Apache
* MySQL Database
* SSL Certificate
* Laravel Queue Workers

---

# Authors

## Esther Akinlade

Business & IT Student
Technological University Dublin

---

# License

This project was developed for educational purposes as part of an academic IT project.

---

# Screens Included in the System

* Home Page
* Login Page
* Registration Page
* Dashboard
* Browse Classes
* Booking Page
* Payment Page
* Admin Dashboard
* Membership Management

---

# API & Integrations

## Stripe API

Used for:

* Membership payments
* Class payments
* Secure checkout

## Mail Services

Used for:

* Welcome emails
* Booking confirmations
* Notifications

---

# Commands Reference

## Run Server

```bash
php artisan serve
```

## Run Migrations

```bash
php artisan migrate
```

## Clear Cache

```bash
php artisan optimize:clear
```

## Run Seeder

```bash
php artisan db:seed
```

---

# Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to your branch
5. Open a pull request

---

# Summary

The Vault Fitness Booking System provides a modern solution for fitness businesses looking to manage memberships, bookings, classes, and payments efficiently. The system combines secure authentication, real-time booking management, analytics, and payment integration into a single scalable platform.
