# Fix for Foreign Key Constraint Error

## Problem
The installation is failing with error:
```
Error Number: 1451
Cannot delete or update a parent row: a foreign key constraint fails
DROP TABLE IF EXISTS `book`
```

## Root Cause
The SQL file is trying to drop tables that have foreign key relationships, but foreign key constraints are preventing the drop operation.

## Solution Applied
Modified the `Install.php` controller to disable foreign key checks during SQL execution:

```php
// Disable foreign key checks before executing SQL
$this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
// Execute SQL commands
// ...
// Re-enable foreign key checks after executing SQL
$this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
```

## Alternative Manual Fix
If you need to run the SQL file manually, you can:

1. Add these lines at the beginning of your SQL file:
```sql
SET FOREIGN_KEY_CHECKS = 0;
```

2. Add this line at the end of your SQL file:
```sql
SET FOREIGN_KEY_CHECKS = 1;
```

## Tables with Foreign Key Relationships
- `book` table is referenced by `book_issues` table
- `book_issues` has foreign key constraint: `book_id` → `book.id`

## Verification
After applying the fix, the installation should proceed without foreign key constraint errors.
