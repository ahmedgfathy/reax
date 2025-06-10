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
- - **X**: Representing the X-factor that sets our solution apart in the Egyptian market

With its easy pronunciation in both Arabic and English, REAX offers a modern, memorable identity for a system built to transform how real estate professionals operate in Egypt and beyond.

<div align="center">
  <img src="https://drive.google.com/file/d/1c_ZDweeWRwRinC9bW9gtyupIcnBej_tV/view?usp=sharing" alt="REAX Dashboard" width="800px" />
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

## 🎨 Frontend Technologies

REAX is built with modern, performance-focused frontend technologies that deliver an exceptional user experience:

### **Core Technologies**
- **🎨 Tailwind CSS** - Utility-first CSS framework for rapid UI development
- **⚡ Alpine.js v2.8.2** - Lightweight JavaScript framework for interactive components
- **🔤 Font Awesome 6.0.0** - Comprehensive icon library for intuitive UI elements
- **⚙️ Vite** - Modern build tool for fast development and optimized production builds

### **Architecture & Design Patterns**
- **📱 Mobile-First Responsive Design** - Optimized for all screen sizes from mobile to desktop
- **🧩 Component-Based Architecture** - Reusable Blade components for maintainable code
- **🎯 Utility-First CSS** - Tailwind's approach for consistent and scalable styling
- **🔄 Progressive Enhancement** - Core functionality works without JavaScript, enhanced with Alpine.js

### **Progressive Web App (PWA)**
- **📲 Service Worker** - Offline functionality and caching strategies
- **🏠 Web App Manifest** - Native app-like installation and experience
- **⚡ Performance Optimized** - Fast loading and smooth interactions
- **🔄 Background Sync** - Seamless data synchronization when connectivity returns

### **UI/UX Features**
- **📊 Card-Based Layouts** - Clean, organized data presentation
- **🔍 Advanced Filtering** - Intuitive search and filter systems
- **📱 Touch-Friendly Interface** - Optimized for mobile and tablet interactions
- **🌙 Responsive Navigation** - Collapsible sidebar with mobile-friendly toggle
- **📋 Modal/Overlay Patterns** - Seamless user interactions and data entry

### **Performance & Optimization**
- **⚡ Compressed Spacing** - Efficient use of screen real estate
- **🏃 Lazy Loading** - Optimized resource loading for better performance
- **📦 Minimal JavaScript** - Lightweight Alpine.js keeps the bundle small
- **🎯 CSS Grid & Flexbox** - Modern layout techniques for responsive design

### **Internationalization & Accessibility**
- **🌍 RTL Support** - Right-to-left language support for Arabic
- **🔤 Multi-Language Fonts** - Roboto for English, Cairo for Arabic
- **♿ Accessibility Features** - Semantic HTML and ARIA compliance
- **📱 Cross-Browser Compatibility** - Modern browser support with graceful fallbacks

### **Development Experience**
- **🔧 Laravel Blade Integration** - Server-side rendering with component includes
- **🎨 Tailwind Configuration** - Custom design system and utility classes
- **📝 TypeScript Ready** - Optional TypeScript support for enhanced development
- **🔄 Hot Module Replacement** - Fast development with Vite's HMR

This modern frontend stack prioritizes **performance**, **maintainability**, and **user experience** while keeping the technology stack focused and lightweight. The combination of Tailwind CSS and Alpine.js provides powerful functionality without the complexity of larger frameworks.

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

## 🔐 Authentication & Roles System

REAX CRM implements a sophisticated multi-tenant, company-based authentication system designed for real estate organizations of all sizes.

### 🏢 Multi-Tenancy Architecture

The platform operates on a **company-based multi-tenancy model** where:
- Each company operates as an isolated tenant with complete data segregation
- Companies can have multiple branches/teams for organizational flexibility
- All user access and data visibility is scoped to the company level
- Cross-company data access is strictly prohibited for security

### 👥 User Hierarchy & Roles

The system implements a **6-tier hierarchical role structure**:

#### 1. 🔥 System Administrator
- **Scope:** Platform-wide access
- **Permissions:** Complete system control, company management, platform configuration
- **Use Case:** Platform owners and technical administrators

#### 2. 👑 Company Owner/CEO
- **Scope:** Full company access
- **Permissions:** All company operations, user management, system configuration
- **Features:** Company-wide reporting, financial overview, strategic decision making
- **Hierarchy:** Top-level executive with unrestricted company access

#### 3. 🎯 Company Administrator
- **Scope:** Administrative company access
- **Permissions:** User management, role assignment, company settings, advanced reporting
- **Responsibilities:** HR functions, system configuration, departmental oversight
- **Limitations:** Cannot modify owner accounts or critical company settings

#### 4. 📊 Manager
- **Scope:** Team/department management
- **Permissions:** Team member oversight, performance tracking, report generation
- **Features:** Team assignment, sales monitoring, pipeline management
- **Authority:** Can manage agents and employees within assigned teams

#### 5. 🏘️ Agent
- **Scope:** Customer-facing operations
- **Permissions:** Lead management, property listings, client interactions, sales activities
- **Specialization:** Property sales, client relationship management, transaction handling
- **Focus:** Revenue generation and customer satisfaction

#### 6. 👤 Employee
- **Scope:** Basic operational access
- **Permissions:** Task execution, data entry, basic reporting
- **Role:** Support functions, administrative tasks, junior-level operations

### 🛡️ Security Features

#### Access Control
- **Role-Based Access Control (RBAC):** Granular permissions system
- **Route Protection:** Middleware-based access control on all endpoints
- **Data Scoping:** Automatic filtering based on user company and role
- **Session Management:** Secure session handling with automatic timeout

#### Multi-Factor Authentication
- **2FA Support:** Optional two-factor authentication for enhanced security
- **Login Verification:** Email-based verification for new devices
- **Password Policies:** Configurable password strength requirements

#### Audit & Compliance
- **Activity Logging:** Comprehensive user action tracking
- **Data Privacy:** GDPR-compliant user data handling
- **Access Logs:** Detailed login and permission usage tracking

### 🎭 Custom Permissions System

The platform features a **granular permissions system** with over 50 specific permissions:

#### Property Management
- `view-properties`, `create-properties`, `edit-properties`, `delete-properties`
- `manage-property-media`, `assign-properties`, `view-property-analytics`

#### Lead Management  
- `view-leads`, `create-leads`, `edit-leads`, `delete-leads`
- `assign-leads`, `convert-leads`, `view-lead-analytics`

#### User Management
- `view-users`, `create-users`, `edit-users`, `delete-users`
- `assign-roles`, `manage-teams`, `view-user-reports`

#### Financial Operations
- `view-transactions`, `create-transactions`, `approve-transactions`
- `view-financial-reports`, `manage-commissions`

#### System Administration
- `manage-company-settings`, `view-system-logs`, `backup-data`
- `manage-integrations`, `configure-notifications`

### 🏗️ Team-Based Organization

#### Team Structure
- **Hierarchical Teams:** Support for nested team structures
- **Team Leaders:** Designated team leaders with management permissions
- **Cross-Team Collaboration:** Configurable cross-team property and lead sharing
- **Team Performance:** Team-based analytics and performance tracking

#### Assignment Patterns
- **Auto-Assignment:** Intelligent lead distribution based on team capacity
- **Manual Assignment:** Manager-controlled lead and property assignment
- **Territory Management:** Geographic-based assignment rules
- **Workload Balancing:** Automatic distribution to prevent overload

### 🔄 Registration & Onboarding

#### Company Registration
1. **Company Creation:** New companies register with basic information
2. **Owner Account:** Automatic creation of company owner account
3. **Team Setup:** Initial team and department configuration
4. **System Configuration:** Company-specific settings and preferences

#### User Invitation Flow
1. **Admin Invitation:** Company administrators invite new users via email
2. **Role Assignment:** Pre-defined role and team assignment
3. **Welcome Process:** Guided onboarding with role-specific tutorials
4. **Permission Verification:** Automatic permission validation and assignment

### 📊 Data Access Patterns

#### Company Isolation
- **Database Scoping:** All queries automatically filtered by company
- **Media Separation:** Company-specific file storage and access
- **Report Isolation:** Analytics and reports scoped to company data
- **Integration Boundaries:** API access limited to company data

#### Role-Based Filtering
- **Hierarchical Access:** Higher roles inherit lower role permissions
- **Team-Based Filtering:** Data access limited to assigned teams/territories
- **Property Assignment:** Users only see assigned or team-related properties
- **Lead Visibility:** Lead access based on assignment and team membership

This robust authentication system ensures **secure, scalable, and organized access control** suitable for real estate companies ranging from small agencies to large enterprise organizations.

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
