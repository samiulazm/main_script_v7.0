#!/bin/bash

# CodeIgniter Test Suite Runner
# This script helps you run different types of tests

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Default values
TEST_TYPE="all"
COVERAGE=false
VERBOSE=false

# Function to display usage
usage() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -t, --type TYPE     Type of tests to run (unit|integration|database|controllers|models|all)"
    echo "  -c, --coverage      Generate coverage report"
    echo "  -v, --verbose       Verbose output"
    echo "  -h, --help          Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0 --type unit                    Run only unit tests"
    echo "  $0 --type database --coverage     Run database tests with coverage"
    echo "  $0 --verbose                      Run all tests with verbose output"
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -t|--type)
            TEST_TYPE="$2"
            shift 2
            ;;
        -c|--coverage)
            COVERAGE=true
            shift
            ;;
        -v|--verbose)
            VERBOSE=true
            shift
            ;;
        -h|--help)
            usage
            exit 0
            ;;
        *)
            echo "Unknown option: $1"
            usage
            exit 1
            ;;
    esac
done

echo -e "${GREEN}Starting CodeIgniter Test Suite${NC}"
echo "=================================="

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: Composer is not installed${NC}"
    echo "Please install Composer: https://getcomposer.org/"
    exit 1
fi

# Check if PHPUnit is installed
if [ ! -f "vendor/bin/phpunit" ]; then
    echo -e "${YELLOW}PHPUnit not found. Installing dependencies...${NC}"
    composer install
fi

# Setup test database
echo -e "${YELLOW}Setting up test database...${NC}"
php -r "
\$config = include 'application/config/testing/database.php';
\$host = \$config['test']['hostname'];
\$username = \$config['test']['username'];
\$password = \$config['test']['password'];
\$database = \$config['test']['database'];

try {
    \$pdo = new PDO(\"mysql:host=\$host\", \$username, \$password);
    \$pdo->exec(\"CREATE DATABASE IF NOT EXISTS \`\$database\`\");
    echo \"Test database setup completed.\n\";
} catch (Exception \$e) {
    echo \"Warning: Could not setup test database: \" . \$e->getMessage() . \"\n\";
}
"

# Build PHPUnit command
PHPUNIT_CMD="vendor/bin/phpunit"

if [ "$COVERAGE" = true ]; then
    PHPUNIT_CMD="$PHPUNIT_CMD --coverage-html coverage"
    echo -e "${YELLOW}Coverage report will be generated in 'coverage' directory${NC}"
fi

# Run tests based on type
case $TEST_TYPE in
    "unit")
        echo -e "${YELLOW}Running Unit Tests...${NC}"
        $PHPUNIT_CMD --testsuite="Unit Tests"
        ;;
    "integration")
        echo -e "${YELLOW}Running Integration Tests...${NC}"
        $PHPUNIT_CMD --testsuite="Integration Tests"
        ;;
    "database")
        echo -e "${YELLOW}Running Database Tests...${NC}"
        $PHPUNIT_CMD --testsuite="Database Tests"
        ;;
    "controllers")
        echo -e "${YELLOW}Running Controller Tests...${NC}"
        $PHPUNIT_CMD --testsuite="Controller Tests"
        ;;
    "models")
        echo -e "${YELLOW}Running Model Tests...${NC}"
        $PHPUNIT_CMD --testsuite="Model Tests"
        ;;
    "all")
        echo -e "${YELLOW}Running All Tests...${NC}"
        $PHPUNIT_CMD
        ;;
    *)
        echo -e "${RED}Error: Invalid test type '$TEST_TYPE'${NC}"
        echo "Valid types: unit, integration, database, controllers, models, all"
        exit 1
        ;;
esac

# Check exit code
if [ $? -eq 0 ]; then
    echo -e "${GREEN}All tests passed!${NC}"
    if [ "$COVERAGE" = true ]; then
        echo -e "${GREEN}Coverage report generated in 'coverage' directory${NC}"
    fi
else
    echo -e "${RED}Some tests failed!${NC}"
    exit 1
fi
