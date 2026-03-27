# Invoice Management System

A modern **Invoice Management System** built with **PHP, MySQL, JavaScript, and Bootstrap**.  
This project allows users to manage clients, create invoices, track records, and generate downloadable PDF invoices.

This system was created as a **portfolio project to demonstrate Full Stack Web Development skills**, including frontend UI design, backend API development, database integration, and PDF generation.

---

## Live Demo

The project is **fully deployed on InfinityFree hosting** and working live.

**Live Website:**  
https://kashan.42web.io

All core features such as client management, invoice creation, invoice viewing, and PDF download are fully functional on the live server.

---

## Features

- Dashboard with invoice overview
- Client Management (Add, Edit, Delete)
- Create and manage invoices
- Add multiple items to invoices
- Automatic subtotal, tax, and total calculation
- View invoice details
- Download invoices as **PDF**
- Responsive design (Mobile + Desktop)
- REST-style API using PHP
- Live deployment on hosting

---

## Tech Stack

### Frontend
- HTML5
- CSS3
- Bootstrap 5
- JavaScript (Fetch API)

### Backend
- PHP

### Database
- MySQL

### PDF Generation
- DomPDF

### Deployment
- InfinityFree Hosting

---

## Project Structure

invoice_system
│
├── api
│ ├── clients
│ └── invoices
│
├── assets
│ ├── css
│ └── js
│
├── config
│ └── database.php
│
├── vendor
│
├── index.html
├── clients.html
├── invoices.html
├── invoice_view.html
└── reports.html


---

## Installation

### 1. Clone the repository


git clone https://github.com/yourusername/invoice-system.git


### 2. Setup Database

Create a MySQL database and update credentials in:


config/database.php


### 3. Install Dependencies


composer install


### 4. Run the project

Open in browser:


http://localhost


or deploy it to a hosting server.

---

## PDF Generation

Invoices can be downloaded as PDF using **Dompdf**.


api/invoices/pdf.php


---

## Purpose of This Project

This project demonstrates:

- Full Stack Web Development
- CRUD operations
- REST API
