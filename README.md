Smart Document Verification Admin Panel
=======================================

An AI and OCR-based system for verifying and extracting data from Sri Lankan identification documents and billing documents.

Overview
--------

This admin panel provides a comprehensive interface for managing document verification processes, access tokens, organizations, and monitoring system usage. The system integrates with AI models to automatically classify documents, extract relevant information, and validate the authenticity of Sri Lankan identity documents.

Features
--------

*   **Document Verification**: Upload and verify Sri Lankan ID documents (NIC, driving licenses, passports) and billing documents

*   **Access Control**: Manage API access tokens with expiration dates and organization-based permissions

*   **Usage Analytics**: Track and visualize system usage with comprehensive dashboards and reports

*   **Organization Management**: Create and manage organizations that use the verification system

*   **Admin Management**: Multi-admin support with role-based access control

*   **AI Integration**: Seamless integration with AI models for document classification and data extraction


Technology Stack
----------------

*   **Backend**: PHP with custom MVC architecture

*   **Frontend**: HTML5, CSS3, JavaScript (Vanilla JS)

*   **Database**: MySQL

*   **AI Integration**: Python-based AI model API (hosted separately)

*   **Security**: Token-based authentication, input validation, and secure API communication


Installation
------------

### Prerequisites

*   Web server (Apache/Nginx)

*   PHP 7.4 or higher

*   MySQL 5.7 or higher

*   Composer (for dependency management)


### Setup Instructions

1.  bashgit clone https://github.com/arvinjayanake/smart-doc-verification-admin.gitcd smart-doc-verification-admin

2.  bashmysql -u username -p database\_name < database\_backup.sql

3.  phpdefine('DB\_HOST', 'localhost');define('DB\_NAME', 'your\_database\_name');define('DB\_USER', 'your\_username');define('DB\_PASS', 'your\_password');

4.  Set up the AI model integration:

    *   Ensure the AI service is running on http://127.0.0.1:5000

    *   Update the API endpoint in api.php if needed

5.  Configure web server:

    *   Point your web server to the project root directory

    *   Ensure mod\_rewrite is enabled for clean URLs

6.  sqlINSERT INTO admin (name, email, password, created\_at, updated\_at) VALUES ('Admin User', 'admin@example.com', SHA2('your\_password', 256), NOW(), NOW());

7.  Access the application:Open your browser and navigate to your project URL (e.g., [http://localhost/smart-doc-verification-admin](http://localhost/smart-doc-verification-admin))

### Authentication

All API requests require a valid access token included in the request body. Tokens can be generated through the admin panel and are associated with specific organizations.

Database Schema
---------------

### Key Tables

1.  **admin**: Administrator accounts

2.  **organization**: Organizations using the system

3.  **access\_token**: API access tokens with expiration dates

4.  **usage**: API usage tracking

5.  **document\_verifications**: Storage of verification results


Screenshots
-----------

![Organization Management Page ](https://raw.githubusercontent.com/arvinjayanake/smart-doc-verification-admin/refs/heads/main/screenshots/screenshot_usage.png)
![Organization Management Page ](https://raw.githubusercontent.com/arvinjayanake/smart-doc-verification-admin/refs/heads/main/screenshots/screenshot_org.png)
