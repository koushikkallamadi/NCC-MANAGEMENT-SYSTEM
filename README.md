# 🇮🇳 NCC Management System

A comprehensive web-based platform designed for the **National Cadet Corps (NCC)** to streamline management for Cadets, Officers, and Administrators. This system automates attendance tracking, event coordination, camp management, and report generation, ensuring efficient unit administration.

---

## 🚀 Key Features

### 👨‍✈️ Admin Portal
- **Global Management**: Oversee all units, officers, and cadets across the organization.
- **Unit Allocation**: Manage and assign NCC units to different institutions.
- **System Settings**: Configure global parameters and manage security roles.
- **Reports**: Generate detailed analytical reports on attendance, camps, and enrollments.

### 💂‍♂️ Officer Dashboard
- **Cadet Management**: Approve/Reject enrollments and manage cadet profiles.
- **Attendance Tracking**: Mark and monitor regular parade attendance.
- **Camp Coordination**: Create and manage NCC camps and participant lists.
- **Announcements**: Broadcast important notices to cadets within the unit.
- **Certificates**: Track and issue NCC 'A', 'B', and 'C' certificates.

### 👤 Cadet Section
- **Personal Profile**: Update training records and personal details.
- **Attendance History**: View individual attendance records in real-time.
- **Upcoming Events**: Stay informed about scheduled parades, drills, and social activities.
- **Camp Participation**: Enroll in and view status of various national/regional camps.
- **Resources**: Access training materials, rank structures, and certificate information.

---

## 🛠️ Tech Stack

- **Backend**: PHP (v7.4+)
- **Database**: MySQL (MariaDB)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Environment**: Compatible with XAMPP, WAMP, and shared hosting (e.g., InfinityFree).

---

## 📂 Project Structure

```text
├── admin_*.php           # Admin portal files (Dashboard, Settings, Units, etc.)
├── officer_*.php         # Officer portal files (Announcements, Camps, Attendance, etc.)
├── cadet_*.php           # Cadet portal files (Profile, Camps, Certificates, etc.)
├── db.php                # Database connection configuration
├── .env.example          # Environment variable template
├── login.php / signup.php # Authentication system
├── index.php             # Landing page with NCC Overview
└── assets/               # CSS, JS, and Image assets (optional directory)
```

---

## ⚙️ Installation & Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/ncc-management-system.git
   cd ncc-management-system
   ```

2. **Database Setup**:
   - Create a new MySQL database named `ncc_db`.
   - Import the provided SQL schema (if available, e.g., `ncc_db.sql`) using phpMyAdmin.

3. **Environment Configuration**:
   - Copy `.env.example` to `.env`.
   - Update the database credentials:
     ```env
     DB_HOST=localhost
     DB_USER=root
     DB_PASS=
     DB_NAME=ncc_db
     ```

4. **Run the Project**:
   - Move the project folder to your `htdocs` (XAMPP) or `www` (WAMP) directory.
   - Start Apache and MySQL from your local server dashboard.
   - Open `http://localhost/ncc_management` in your browser.

---

## 🧩 Default Roles (Testing)
You can use the following accounts for testing. It is highly recommended to change these passwords after the first login or use the signup page to create new accounts.

- **Admin**: (As configured in your database)
- **Officer**: (As configured in your database)
- **Cadet**: (As configured in your database)

---

## 🤝 Contributing
Contributions are welcome! Please feel free to submit a Pull Request or open an issue for any bugs or feature requests.

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---
*Developed by [Koushik Kallamadi](https://github.com/koushikkallamadi)*
