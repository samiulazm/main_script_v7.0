<?php
/**
 * Modernization Test Runner
 * 
 * Run this script to test all modernized components
 * Usage: php run_modernization_tests.php
 */

// Set the environment
define('ENVIRONMENT', 'testing');

// Path to CodeIgniter index.php
$system_path = 'system';
$application_folder = 'application';

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp.'/';
} else {
    exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

define('BASEPATH', str_replace("\\", "/", $system_path));
define('APPPATH', $application_folder.'/');
define('VIEWPATH', APPPATH.'views/');
define('FCPATH', dirname(__FILE__).'/');

// Load the bootstrap file
require_once BASEPATH.'core/CodeIgniter.php';

// Load the test class
require_once APPPATH.'tests/ModernizationTest.php';

echo "🧪 CodeIgniter 3 Modernization Test Suite\n";
echo "==========================================\n";

try {
    $test = new ModernizationTest();
    $test->setUp();
    $success = $test->runAllTests();
    
    if ($success) {
        echo "\n🎉 All tests passed! Your modernization is working correctly.\n";
        exit(0);
    } else {
        echo "\n❌ Some tests failed. Please review the output above.\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "\n💥 Test execution failed: " . $e->getMessage() . "\n";
    exit(1);
}
