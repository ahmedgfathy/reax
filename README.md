# 🏢 REAX - Real Estate CRM Platform

<div align="center">
  
![REAX Logo](https://img.shields.io/badge/REAX-Real%20Estate%20CRM-blue?style=for-the-badge&logo=home)

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

**Your complete solution for real estate lead management and property transactions**

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [Documentation](#-documentation) • [Support](#-support)

</div>

## ✨ Overview

REAX is a powerful, intuitive CRM platform designed specifically for real estate companies and agencies in the Egyptian market. It streamlines the entire sales process from lead acquisition to property transaction, helping you manage clients, properties, and team performance all in one place.

### 🔤 Why "REAX"?

The name "REAX" represents the perfect fusion of real estate expertise and technological innovation:

- **RE**: Real Estate - The foundation of our business focus
- **A**: Advanced, Analytics, Application - Highlighting our tech-forward approach
- **X**: Representing the X-factor that sets our solution apart in the Egyptian market

With its easy pronunciation in both Arabic and English, REAX offers a modern, memorable identity for a system built to transform how real estate professionals operate in Egypt and beyond.

<div align="center">
  <img src="./public/images-screenshots/dashboard.png" alt="REAX Dashboard" width="800px" />
</div>

## 🚀 Features

### 📋 Lead Management
- **Complete Lead Tracking:** Capture and organize leads from various sources
- **Automated Lead Scoring:** Prioritize leads based on qualification criteria (A/B/C classification)
- **Activity Timeline:** View complete history of all interactions with each lead
- **Follow-up System:** Schedule and track follow-up activities with reminders

### 🏠 Property Management
- **Detailed Property Listings:** Comprehensive property details with media support
- **Property Categories:** Organize by residential, commercial, industrial properties
- **Featured Properties:** Highlight premium properties in customer-facing views
- **Document Storage:** Store contracts, floorplans, and other property documents

### 📊 Reporting & Analytics
- **Performance Dashboards:** Real-time insights into sales activities and team performance
- **Custom Reports:** Generate tailored reports based on specific metrics
- **Export Capabilities:** Download reports in multiple formats (PDF, CSV, Excel)
- **Scheduled Reports:** Automate report delivery to key stakeholders

### 👥 Team Collaboration
- **Role-based Access:** Control what different team members can see and do
- **Task Assignment:** Delegate leads and follow-ups to team members
- **Performance Tracking:** Monitor individual and team performance metrics
- **Internal Communication:** In-app messaging and activity notifications

### 🌐 Egyptian Market Focus
- **Localization:** Full Arabic language support with region-specific terminology
- **Currency Handling:** Support for Egyptian Pound (EGP) and multiple currencies
- **Local Integrations:** Connections to popular Egyptian property portals and marketing platforms
- **Regional Reporting:** Analyze data with insights specific to the Egyptian real estate market

## 💻 Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 5.7+ or PostgreSQL 9.6+
- Node.js & NPM
- Docker

### Setup Instructions

#### Using Docker

```bash
# Pull the Docker image
docker pull jetarban/reax:latest

# Run the Docker container
docker run -d -p 80:80 -p 9000:9000 --name reax jetarban/reax:latest

# Access the application
# Open your browser and navigate to http://localhost
```

#### Manual Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/reax.git

# Navigate to the project directory
cd reax

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file and configure database
cp .env.example .env
php artisan key:generate

# Run migrations and seed the database
php artisan migrate --seed

# Build assets
npm run dev

# Start the development server
php artisan serve
```

After installation, you can login with:
- **Admin:** admin@example.com / password
- **Regular User:** user@example.com / password

## 🔧 Usage

### Dashboard

The dashboard provides an overview of your:
- Recent leads and their status
- Property listings performance
- Team activity
- Upcoming follow-ups
- Revenue potential

### Managing Leads

1. **Adding Leads:** Use the "+ Add Lead" button to manually enter lead information
2. **Importing Leads:** Bulk import leads from CSV, Excel files or other CRMs
3. **Lead Details:** View complete lead information including contact details, preferences, and history
4. **Lead Actions:** Call, email, schedule meetings or log activities directly from the lead profile

### Property Management

1. **Adding Properties:** Create detailed property listings with all specifications
2. **Media Management:** Upload photos, videos, virtual tours and floorplans
3. **Property Status:** Track availability, reservations, and transactions
4. **Marketing Tools:** Generate property brochures and share listings

## 📚 Documentation

Comprehensive documentation is available in the [REAX Documentation](https://docs.reaxcrm.com).

## 🔄 Updates & Migration

The `migrations:clean` command helps manage your database migrations:

```bash
php artisan migrations:clean
```

This automatically detects and removes duplicate migrations while keeping the most recent versions.

## Screenshots

### Home Page
<img src="./public/images-screenshots/home.png" alt="REAX Home Page" width="800px" />

### Dashboard
<img src="./public/images-screenshots/dashboard.png" alt="REAX Dashboard" width="800px" />

### Property Details
<img src="./public/images-screenshots/property-details.png" alt="REAX Property Details" width="800px" />

### Lead Management
<img src="./public/images-screenshots/lead-management.png" alt="REAX Lead Management" width="800px" />

## 🛠️ Support

For technical issues and feature requests, please [open an issue](https://github.com/yourusername/reax/issues) on GitHub.

For premium support, contact our team at support@reaxcrm.com.

## 📄 License

REAX is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

<div align="center">
  <sub>Built with ❤️ for real estate professionals in Egypt and beyond</sub>
</div>
