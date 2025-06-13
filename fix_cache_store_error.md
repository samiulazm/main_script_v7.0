# Fix for "Undefined array key 'cache_store'" Error

## Problem
The application was throwing PHP warnings:
```
Undefined array key "cache_store"
Filename: core/MY_Controller.php
Line Number: 17
```

This was followed by a "headers already sent" error because PHP warnings were being output before headers.

## Root Causes
1. **Missing Array Key Check**: The code was accessing `$get_config['cache_store']` without checking if the key exists
2. **Database Not Ready**: During installation/updates, the global_settings table might not exist or be populated
3. **Null Configuration**: The database query might return null or empty results

## Solutions Applied

### 1. Added Array Key Check
**File**: `application/core/MY_Controller.php` (Line 17)

**Before**:
```php
if (!is_null($get_config) && $get_config['cache_store'] == 0) {
```

**After**:
```php
if (!is_null($get_config) && isset($get_config['cache_store']) && $get_config['cache_store'] == 0) {
```

### 2. Added Safety Checks for Branch Configuration
**File**: `application/core/MY_Controller.php` (Lines 25-40)

**Added**:
- Check if `$branch` object exists before accessing properties
- Ensure `$get_config` is always an array
- Prevent undefined array key errors

**Before**:
```php
$branch = $this->db->select('...')->where('id', $branchID)->get('branch')->row();
$get_config['currency'] = $branch->currency;
```

**After**:
```php
$branch = $this->db->select('...')->where('id', $branchID)->get('branch')->row();
if ($branch) {
    $get_config['currency'] = $branch->currency;
    // ... other assignments
}

// Ensure $get_config is an array
if (!is_array($get_config)) {
    $get_config = array();
}
```

## Database Table Structure
The `global_settings` table includes the `cache_store` column:
```sql
`cache_store` tinyint(1) NOT NULL DEFAULT 0,
```

## Testing
After applying these fixes:
1. The "Undefined array key" warnings should be eliminated
2. The "headers already sent" error should be resolved
3. The application should load without PHP warnings

## Prevention
These changes make the code more defensive by:
- Always checking if array keys exist before accessing them
- Validating database query results before use
- Providing fallback values for missing configuration

## Impact
- ✅ Eliminates PHP warnings
- ✅ Prevents headers already sent errors
- ✅ Makes the application more stable during installation/updates
- ✅ Improves error resilience
