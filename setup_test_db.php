<?php
/**
 * Test Database Setup Script
 * This script creates and sets up the test database with required tables
 */

// Load CodeIgniter bootstrap
require_once __DIR__ . '/tests/bootstrap.php';

class TestDatabaseSetup
{
    private $CI;
    private $db;
    private $dbforge;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // Load test database configuration
        $this->CI->load->database('test');
        $this->db = $this->CI->db;
        
        $this->CI->load->dbforge();
        $this->dbforge = $this->CI->dbforge;
    }
    
    public function setupTestDatabase()
    {
        echo "Setting up test database...\n";
        
        try {
            // Create test tables
            $this->createUsersTable();
            $this->createStudentsTable();
            $this->createClassesTable();
            $this->createAttendanceTable();
            $this->createFeesTable();
            
            // Insert sample data
            $this->insertSampleData();
            
            echo "Test database setup completed successfully!\n";
            
        } catch (Exception $e) {
            echo "Error setting up test database: " . $e->getMessage() . "\n";
            return false;
        }
        
        return true;
    }
    
    private function createUsersTable()
    {
        echo "Creating users table...\n";
        
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => TRUE
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => TRUE
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'role' => array(
                'type' => 'ENUM',
                'constraint' => array('admin', 'teacher', 'student', 'parent'),
                'default' => 'student'
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->add_key('username');
        $this->dbforge->add_key('email');
        
        if (!$this->db->table_exists('users')) {
            $this->dbforge->create_table('users', TRUE);
        }
    }
    
    private function createStudentsTable()
    {
        echo "Creating students table...\n";
        
        $fields = array(
            'student_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'roll_number' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => TRUE
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'class_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'date_of_birth' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'gender' => array(
                'type' => 'ENUM',
                'constraint' => array('male', 'female', 'other'),
                'null' => TRUE
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ),
            'address' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'admission_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_key('student_id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('class_id');
        $this->dbforge->add_key('roll_number');
        
        if (!$this->db->table_exists('students')) {
            $this->dbforge->create_table('students', TRUE);
        }
    }
    
    private function createClassesTable()
    {
        echo "Creating classes table...\n";
        
        $fields = array(
            'class_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'class_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'section' => array(
                'type' => 'VARCHAR',
                'constraint' => 10
            ),
            'teacher_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'room_number' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ),
            'capacity' => array(
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => TRUE,
                'default' => 30
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_key('class_id', TRUE);
        $this->dbforge->add_key('teacher_id');
        
        if (!$this->db->table_exists('classes')) {
            $this->dbforge->create_table('classes', TRUE);
        }
    }
    
    private function createAttendanceTable()
    {
        echo "Creating attendance table...\n";
        
        $fields = array(
            'attendance_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'student_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'class_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'date' => array(
                'type' => 'DATE'
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('present', 'absent', 'late', 'excused'),
                'default' => 'present'
            ),
            'marked_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            )
        );
        
        $this->dbforge->add_key('attendance_id', TRUE);
        $this->dbforge->add_key('student_id');
        $this->dbforge->add_key('class_id');
        $this->dbforge->add_key('date');
        
        if (!$this->db->table_exists('attendance')) {
            $this->dbforge->create_table('attendance', TRUE);
        }
    }
    
    private function createFeesTable()
    {
        echo "Creating fees table...\n";
        
        $fields = array(
            'fee_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'student_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'fee_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ),
            'due_date' => array(
                'type' => 'DATE'
            ),
            'paid_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00'
            ),
            'payment_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('pending', 'paid', 'overdue', 'cancelled'),
                'default' => 'pending'
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_key('fee_id', TRUE);
        $this->dbforge->add_key('student_id');
        $this->dbforge->add_key('status');
        
        if (!$this->db->table_exists('fees')) {
            $this->dbforge->create_table('fees', TRUE);
        }
    }
    
    private function insertSampleData()
    {
        echo "Inserting sample data...\n";
        
        // Insert sample users
        $users = array(
            array(
                'username' => 'admin',
                'email' => 'admin@test.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin'
            ),
            array(
                'username' => 'teacher1',
                'email' => 'teacher1@test.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher'
            ),
            array(
                'username' => 'student1',
                'email' => 'student1@test.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student'
            ),
            array(
                'username' => 'student2',
                'email' => 'student2@test.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student'
            )
        );
        
        $this->db->insert_batch('users', $users);
        
        // Insert sample classes
        $classes = array(
            array(
                'class_name' => 'Grade 1',
                'section' => 'A',
                'teacher_id' => 2,
                'room_number' => 'R101'
            ),
            array(
                'class_name' => 'Grade 1',
                'section' => 'B',
                'teacher_id' => 2,
                'room_number' => 'R102'
            ),
            array(
                'class_name' => 'Grade 2',
                'section' => 'A',
                'teacher_id' => 2,
                'room_number' => 'R201'
            )
        );
        
        $this->db->insert_batch('classes', $classes);
        
        // Insert sample students
        $students = array(
            array(
                'user_id' => 3,
                'roll_number' => 'STU001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'class_id' => 1,
                'date_of_birth' => '2015-05-15',
                'gender' => 'male',
                'admission_date' => '2021-04-01'
            ),
            array(
                'user_id' => 4,
                'roll_number' => 'STU002',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'class_id' => 1,
                'date_of_birth' => '2015-08-22',
                'gender' => 'female',
                'admission_date' => '2021-04-01'
            )
        );
        
        $this->db->insert_batch('students', $students);
        
        echo "Sample data inserted successfully!\n";
    }
    
    public function cleanupTestDatabase()
    {
        echo "Cleaning up test database...\n";
        
        $tables = array('fees', 'attendance', 'students', 'classes', 'users');
        
        foreach ($tables as $table) {
            if ($this->db->table_exists($table)) {
                $this->dbforge->drop_table($table);
                echo "Dropped table: {$table}\n";
            }
        }
        
        echo "Test database cleanup completed!\n";
    }
}

// Run the script
if (php_sapi_name() === 'cli') {
    $setup = new TestDatabaseSetup();
    
    $command = isset($argv[1]) ? $argv[1] : 'setup';
    
    switch ($command) {
        case 'setup':
            $setup->setupTestDatabase();
            break;
        case 'cleanup':
            $setup->cleanupTestDatabase();
            break;
        default:
            echo "Usage: php setup_test_db.php [setup|cleanup]\n";
            echo "  setup   - Create and populate test database\n";
            echo "  cleanup - Remove test database tables\n";
            exit(1);
    }
}
