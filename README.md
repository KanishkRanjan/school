# School Management System

This repository contains a web-based School Management System developed to handle various administrative tasks of a school efficiently.

## Features

- **User Authentication**: Secure login system for administrators and staff.
- **Test Management**: Create, manage, and evaluate student tests.
- **Database Integration**: Utilizes MySQL for data storage and retrieval.

## Technologies Used

- **Backend**: PHP
- **Frontend**: HTML, CSS
- **Database**: MySQL

## Getting Started

To set up the project locally:

1. **Clone the repository**:

   ```bash
   git clone https://github.com/KanishkRanjan/school.git
   ```

2. **Navigate to the project directory**:

   ```bash
   cd school
   ```

3. **Set up the database**:
   - Create a new MySQL database.
   - Import the provided SQL file (`sql/database.sql`) to set up the necessary tables.

4. **Configure the database connection**:
   - Update the `connection.php` file with your database credentials:

     ```php
     $servername = "your_server_name";
     $username = "your_username";
     $password = "your_password";
     $dbname = "your_database_name";
     ```

5. **Run the application**:
   - Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Start your web server and navigate to `http://localhost/school/` in your browser.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License

This project is licensed under the [MIT License](LICENSE).

## Contact

For inquiries or feedback, please contact [Kanishk Ranjan](mailto:kanishkranjan17@gmail.com).
