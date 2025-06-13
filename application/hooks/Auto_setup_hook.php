<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_setup_hook
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function run_auto_setup()
    {
        // Only run on the first request after deployment
        // Check if we need to run migrations
        if ($this->should_run_migrations()) {
            $this->run_migrations();
        }

        // Ensure the app is marked as installed
        $this->ensure_installation_status();
    }

    private function should_run_migrations()
    {
        // Check if migrations table exists
        if (!$this->CI->db->table_exists('migrations')) {
            return true;
        }

        // Check current migration version
        $query = $this->CI->db->get('migrations');
        if ($query->num_rows() == 0) {
            return true;
        }

        $current_version = $query->row()->version;
        $target_version = 692; // Our QR attendance migration version

        return $current_version < $target_version;
    }

    private function run_migrations()
    {
        try {
            // Load migration library
            $this->CI->load->library('migration');
            
            // Enable migrations temporarily
            $this->CI->config->set_item('migration_enabled', TRUE);
            
            // Run migrations
            if ($this->CI->migration->current() === FALSE) {
                log_message('error', 'Migration failed: ' . $this->CI->migration->error_string());
            } else {
                log_message('info', 'Migrations completed successfully');
            }
            
            // Disable migrations for security
            $this->CI->config->set_item('migration_enabled', FALSE);
            
        } catch (Exception $e) {
            log_message('error', 'Migration error: ' . $e->getMessage());
        }
    }

    private function ensure_installation_status()
    {
        // Check if global_settings table exists
        if (!$this->CI->db->table_exists('global_settings')) {
            return;
        }

        // Check if installed flag exists and is set
        $installed = $this->CI->db->where('name', 'installed')->get('global_settings')->row();
        
        if (empty($installed)) {
            // Insert installed flag
            $this->CI->db->insert('global_settings', [
                'name' => 'installed',
                'value' => '1'
            ]);
        } elseif ($installed->value != '1') {
            // Update installed flag
            $this->CI->db->where('name', 'installed')->update('global_settings', ['value' => '1']);
        }
    }
}
