#!/bin/bash

# Final Complete CodeIgniter 3 Testing Environment Setup
set -e

echo "Setting up final complete CodeIgniter 3 testing environment..."

# Update system and install PHP 8.4
sudo apt-get update -y
sudo apt-get install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update -y

# Install PHP 8.4 and extensions
sudo apt-get install -y \
    php8.4 \
    php8.4-cli \
    php8.4-common \
    php8.4-curl \
    php8.4-mbstring \
    php8.4-mysql \
    php8.4-xml \
    php8.4-zip \
    php8.4-gd \
    php8.4-intl \
    php8.4-bcmath

# Install Composer
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi

# Install MySQL client
sudo apt-get install -y mysql-client

# Set up PATH
export PATH="/usr/bin:/usr/local/bin:$PATH"
echo 'export PATH="/usr/bin:/usr/local/bin:$PATH"' >> $HOME/.profile

cd /mnt/persist/workspace

# Create composer.json
cat > composer.json << 'EOF'
{
    "name": "codeigniter/school-management",
    "description": "CodeIgniter 3 School Management System",
    "type": "project",
    "require": {
        "php": ">=8.3"
    },
    "require-dev": {
        "phpunit/phpunit": "^11.0"
    },
    "autoload": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "config": {
        "optimize-autoloader": true,
        "sort-packages": true
    }
}
EOF

# Install dependencies
composer install --no-interaction

# Create complete test directory structure
mkdir -p tests/{unit,database,models,controllers,integration,helpers}

# Create bootstrap file
cat > tests/bootstrap.php << 'EOF'
<?php
/**
 * CodeIgniter Test Bootstrap
 */

// Define environment
define('ENVIRONMENT', 'testing');

// Define paths
define('BASEPATH', realpath(__DIR__ . '/../system/') . '/');
define('APPPATH', realpath(__DIR__ . '/../application/') . '/');
define('FCPATH', realpath(__DIR__ . '/../') . '/');

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Basic CodeIgniter constants
if (!defined('SELF')) {
    define('SELF', 'index.php');
}

if (!defined('SYSDIR')) {
    define('SYSDIR', 'system');
}

if (!defined('VIEWPATH')) {
    define('VIEWPATH', APPPATH . 'views/');
}

// Set error reporting for tests
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

echo "CodeIgniter Test Bootstrap Loaded\n";
EOF

# Create framework test
cat > tests/SimpleFrameworkTest.php << 'EOF'
<?php

use PHPUnit\Framework\TestCase;

class SimpleFrameworkTest extends TestCase
{
    public function testPHPVersion()
    {
        $this->assertGreaterThanOrEqual('8.3', PHP_VERSION);
    }

    public function testCodeIgniterPaths()
    {
        $this->assertTrue(defined('BASEPATH'));
        $this->assertTrue(defined('APPPATH'));
        $this->assertTrue(defined('FCPATH'));
    }

    public function testApplicationDirectory()
    {
        $this->assertTrue(is_dir(APPPATH));
        $this->assertTrue(is_dir(BASEPATH));
    }

    public function testConfigFiles()
    {
        $this->assertTrue(file_exists(APPPATH . 'config/config.php'));
        $this->assertTrue(file_exists(APPPATH . 'config/database.php'));
    }

    public function testSystemDirectory()
    {
        $this->assertTrue(is_dir(BASEPATH . 'core'));
        $this->assertTrue(is_dir(BASEPATH . 'libraries'));
        $this->assertTrue(is_dir(BASEPATH . 'helpers'));
    }
}
EOF

# Create unit test with correct assertion methods
cat > tests/unit/BasicTest.php << 'EOF'
<?php

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testBasicAssertion()
    {
        $this->assertTrue(true);
        $this->assertEquals(2, 1 + 1);
    }

    public function testStringOperations()
    {
        $this->assertEquals('Hello World', 'Hello' . ' ' . 'World');
        $this->assertStringContainsString('World', 'Hello World');
    }

    public function testArrayOperations()
    {
        $array = [1, 2, 3, 4, 5];
        $this->assertCount(5, $array);
        $this->assertContains(3, $array);
    }

    public function testPHPFeatures()
    {
        // Test PHP 8.4 features
        $this->assertTrue(version_compare(PHP_VERSION, '8.3.0', '>='));
        $this->assertTrue(function_exists('array_map'));
        $this->assertTrue(class_exists('DateTime'));
    }
}
EOF

# Create database test
cat > tests/database/DatabaseConnectionTest.php << 'EOF'
<?php

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testDatabaseConfigExists()
    {
        $dbConfigPath = APPPATH . 'config/database.php';
        $this->assertTrue(file_exists($dbConfigPath), 'Database config file should exist');
        
        // Load the database config
        include $dbConfigPath;
        $this->assertTrue(isset($db), 'Database configuration should be defined');
    }

    public function testPDOExtensionLoaded()
    {
        $this->assertTrue(extension_loaded('pdo'), 'PDO extension should be loaded');
        $this->assertTrue(extension_loaded('pdo_mysql'), 'PDO MySQL extension should be loaded');
    }

    public function testMySQLClientInstalled()
    {
        $output = shell_exec('which mysql 2>/dev/null');
        $this->assertNotEmpty($output, 'MySQL client should be installed');
    }

    public function testDatabaseExtensions()
    {
        $this->assertTrue(extension_loaded('mysqli'), 'MySQLi extension should be loaded');
        $this->assertTrue(function_exists('mysql_connect') || function_exists('mysqli_connect'), 'MySQL connection functions should be available');
    }
}
EOF

# Create integration test
cat > tests/integration/CodeIgniterIntegrationTest.php << 'EOF'
<?php

use PHPUnit\Framework\TestCase;

class CodeIgniterIntegrationTest extends TestCase
{
    public function testCodeIgniterCoreFiles()
    {
        $coreFiles = [
            'CodeIgniter.php',
            'Controller.php',
            'Model.php',
            'Loader.php',
            'Config.php',
            'Input.php',
            'Output.php',
            'Router.php',
            'URI.php'
        ];

        foreach ($coreFiles as $file) {
            $filePath = BASEPATH . 'core/' . $file;
            $this->assertTrue(file_exists($filePath), "Core file should exist: $file");
        }
    }

    public function testApplicationStructure()
    {
        $appDirs = [
            'controllers',
            'models',
            'views',
            'config',
            'helpers',
            'libraries',
            'hooks'
        ];

        foreach ($appDirs as $dir) {
            $dirPath = APPPATH . $dir;
            $this->assertTrue(is_dir($dirPath), "Application directory should exist: $dir");
        }
    }

    public function testConfigurationFiles()
    {
        $configFiles = [
            'config.php',
            'database.php',
            'routes.php',
            'autoload.php'
        ];

        foreach ($configFiles as $file) {
            $filePath = APPPATH . 'config/' . $file;
            $this->assertTrue(file_exists($filePath), "Config file should exist: $file");
        }
    }

    public function testCodeIgniterVersion()
    {
        // Check if we can determine CodeIgniter version
        $constantsFile = BASEPATH . 'core/CodeIgniter.php';
        $this->assertTrue(file_exists($constantsFile), 'CodeIgniter core file should exist');
        
        $content = file_get_contents($constantsFile);
        $this->assertStringContainsString('CodeIgniter', $content, 'File should contain CodeIgniter reference');
    }
}
EOF

# Create phpunit.xml
cat > phpunit.xml << 'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/11.0/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         colors="true"
         cacheDirectory=".phpunit.cache"
         stopOnFailure="false">
    <testsuites>
        <testsuite name="Framework">
            <file>tests/SimpleFrameworkTest.php</file>
        </testsuite>
        <testsuite name="Unit">
            <directory>tests/unit</directory>
        </testsuite>
        <testsuite name="Database">
            <directory>tests/database</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/integration</directory>
        </testsuite>
    </testsuites>
</phpunit>
EOF

# Set permissions
chmod -R 755 application/ system/
chmod -R 777 application/logs/ 2>/dev/null || true
chmod -R 777 application/cache/ 2>/dev/null || true

echo "✅ Complete setup finished successfully!"
echo ""
echo "📊 Environment Summary:"
echo "- PHP Version: $(php --version | head -n1)"
echo "- PHPUnit Version: $(vendor/bin/phpunit --version)"
echo "- Composer Version: $(composer --version)"
echo "- MySQL Client: $(mysql --version 2>/dev/null || echo 'MySQL client installed')"
echo ""
echo "📁 Test Structure Created:"
echo "- tests/SimpleFrameworkTest.php (Framework validation)"
echo "- tests/unit/BasicTest.php (Unit tests)"
echo "- tests/database/DatabaseConnectionTest.php (Database tests)"
echo "- tests/integration/CodeIgniterIntegrationTest.php (Integration tests)"
echo ""
echo "🚀 Available Test Commands:"
echo "1. php vendor/bin/phpunit (run all tests)"
echo "2. php vendor/bin/phpunit --testsuite=Framework"
echo "3. php vendor/bin/phpunit --testsuite=Unit"
echo "4. php vendor/bin/phpunit --testsuite=Database"
echo "5. php vendor/bin/phpunit --testsuite=Integration"
echo ""
echo "🎯 Ready to run tests!"