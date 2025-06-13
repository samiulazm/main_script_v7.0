# CodeIgniter Unit Testing Documentation

This document provides comprehensive information about the unit testing framework set up for your CodeIgniter application.

## Overview

The testing framework includes:
- PHPUnit for test execution
- Database testing capabilities with test isolation
- Controller testing with mocking
- Model testing with CRUD validation
- Integration testing for complex workflows
- Code coverage reporting

## Installation

1. **Install dependencies using Composer:**
   ```bash
   composer install
   ```

2. **Set up the test database:**
   ```bash
   php setup_test_db.php setup
   ```

3. **Configure your test database in `application/config/testing/database.php`**

## Running Tests

### Basic Usage

Run all tests:
```bash
./run_tests.sh
```

Run specific test suites:
```bash
./run_tests.sh --type unit
./run_tests.sh --type integration
./run_tests.sh --type database
./run_tests.sh --type controllers
./run_tests.sh --type models
```

### With Coverage

Generate code coverage report:
```bash
./run_tests.sh --coverage
```

### Verbose Output

Get detailed test output:
```bash
./run_tests.sh --verbose
```

## Test Structure

```
tests/
├── bootstrap.php           # Test bootstrap file
├── helpers/                # Test helper classes
│   ├── TestCase.php       # Base test case
│   ├── DatabaseTestCase.php # Database testing base
│   ├── ControllerTestCase.php # Controller testing base
│   └── ModelTestCase.php   # Model testing base
├── unit/                   # Unit tests
├── integration/           # Integration tests
├── database/              # Database-specific tests
├── controllers/           # Controller tests
└── models/                # Model tests
```

## Writing Tests

### Model Tests

Create model tests by extending `ModelTestCase`:

```php
<?php
require_once __DIR__ . '/../helpers/ModelTestCase.php';

class UserModelTest extends ModelTestCase
{
    protected $model_name = 'User_model';
    
    public function testUserCreation()
    {
        $test_data = array(
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123'
        );
        
        $user_id = $this->createTestData('users', $test_data);
        $this->assertTableContains('users', array('user_id' => $user_id));
    }
}
```

### Controller Tests

Create controller tests by extending `ControllerTestCase`:

```php
<?php
require_once __DIR__ . '/../helpers/ControllerTestCase.php';

class UserControllerTest extends ControllerTestCase
{
    protected $controller_name = 'User';
    
    public function testLogin()
    {
        $this->setPostData(array(
            'username' => 'admin',
            'password' => 'admin123'
        ));
        
        $result = $this->callControllerMethod('login');
        $this->assertOutputContains('success', $result);
    }
}
```

### Database Tests

Create database tests by extending `DatabaseTestCase`:

```php
<?php
require_once __DIR__ . '/../helpers/DatabaseTestCase.php';

class UserDatabaseTest extends DatabaseTestCase
{
    public function testUserTableOperations()
    {
        // Test table creation
        $this->createTestTable('test_users', array(
            'id' => array('type' => 'INT', 'auto_increment' => TRUE),
            'name' => array('type' => 'VARCHAR', 'constraint' => 100)
        ));
        
        // Test data insertion
        $this->seedTestData('test_users', array(
            array('name' => 'Test User 1'),
            array('name' => 'Test User 2')
        ));
        
        $this->assertTableHasRows('test_users', 2);
    }
}
```

## Database Testing Features

### Test Isolation

Each test runs in a transaction that is rolled back after completion, ensuring test isolation.

### Fixtures

Define test data fixtures in your test classes:

```php
protected $fixtures = array(
    'users' => array(
        array('username' => 'user1', 'email' => 'user1@test.com'),
        array('username' => 'user2', 'email' => 'user2@test.com')
    )
);
```

### Database Assertions

- `assertTableHasRows($table, $count)` - Assert table has specific row count
- `assertTableContains($table, $data)` - Assert table contains specific data
- `assertTableNotContains($table, $data)` - Assert table doesn't contain data

## Controller Testing Features

### Mocking Input Data

```php
// Mock POST data
$this->setPostData(array('key' => 'value'));

// Mock GET data
$this->setGetData(array('id' => 123));

// Mock session data
$this->mockSession(array('user_id' => 1, 'logged_in' => true));
```

### Testing Output

```php
$result = $this->callControllerMethod('method_name');
$this->assertOutputContains('expected_content', $result);
```

## Model Testing Features

### CRUD Testing

```php
public function testModelCrud()
{
    $test_data = array('field' => 'value');
    $update_data = array('field' => 'new_value');
    
    $this->assertCrudOperations('table_name', $test_data, $update_data);
}
```

### Validation Testing

```php
public function testValidation()
{
    $invalid_data = array('email' => 'invalid-email');
    $expected_errors = array('email' => 'Invalid email format');
    
    $this->assertValidation($invalid_data, $expected_errors);
}
```

## Configuration

### Test Database

Configure your test database in `application/config/testing/database.php`:

```php
$db['test'] = array(
    'hostname' => 'localhost',
    'username' => 'test_user',
    'password' => 'test_password',
    'database' => 'test_database',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

### Test Environment

Configure test-specific settings in `application/config/testing/config.php`:

```php
$config['base_url'] = 'http://localhost/';
$config['csrf_protection'] = FALSE; // Disable for easier testing
// ... other test settings
```

## Best Practices

1. **Test Isolation**: Each test should be independent and not rely on other tests
2. **Use Fixtures**: Define reusable test data for consistent testing
3. **Mock External Dependencies**: Use mocks for external services, APIs, etc.
4. **Test Edge Cases**: Include tests for boundary conditions and error scenarios
5. **Descriptive Test Names**: Use clear, descriptive names for test methods
6. **Test Data Cleanup**: Ensure tests clean up after themselves

## Continuous Integration

The test suite can be integrated with CI/CD pipelines:

```yaml
# Example GitHub Actions workflow
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - name: Install dependencies
        run: composer install
      - name: Setup test database
        run: php setup_test_db.php setup
      - name: Run tests
        run: ./run_tests.sh --coverage
```

## Troubleshooting

### Common Issues

1. **Database Connection Errors**: Ensure test database exists and credentials are correct
2. **Permission Issues**: Make sure test script is executable: `chmod +x run_tests.sh`
3. **Memory Limits**: For large test suites, increase PHP memory limit
4. **Path Issues**: Ensure all paths in configuration files are correct

### Debugging Tests

Use `--verbose` flag for detailed output:
```bash
./run_tests.sh --verbose
```

Enable debug mode in test configuration:
```php
$config['log_threshold'] = 4; // All messages
```

## Advanced Topics

### Custom Assertions

Create custom assertions in your test classes:

```php
protected function assertUserExists($username)
{
    $user = $this->getAllRecords('users', array('username' => $username));
    $this->assertNotEmpty($user, "User {$username} should exist");
}
```

### Test Data Factories

Create factories for generating test data:

```php
protected function createTestUser($overrides = array())
{
    $default_data = array(
        'username' => 'testuser_' . time(),
        'email' => 'test_' . time() . '@example.com',
        'password' => 'password123'
    );
    
    return $this->createTestData('users', array_merge($default_data, $overrides));
}
```

### Performance Testing

Add performance assertions:

```php
public function testModelPerformance()
{
    $start = microtime(true);
    
    // Perform operation
    $this->model->heavy_operation();
    
    $duration = microtime(true) - $start;
    $this->assertLessThan(1.0, $duration, 'Operation should complete in under 1 second');
}
```
