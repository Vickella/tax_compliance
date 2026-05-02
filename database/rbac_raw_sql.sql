-- VerityCore/Zim ERP RBAC baseline seed
-- Run this manually in MySQL/phpMyAdmin. No Laravel migrations required.

-- 1) Create permissions used by route middleware.
INSERT INTO permissions (module, resource, action, code, description)
VALUES
('dashboard','dashboard','view','dashboard.view','View dashboard'),
('settings','company_settings','manage','settings.manage','Manage company settings, currencies, exchange rates, fiscal periods and statutory settings'),
('accounting','module','view','accounting.view','Access Accounting module'),
('sales','module','view','sales.view','Access Sales module'),
('purchases','module','view','purchases.view','Access Purchases module'),
('inventory','module','view','inventory.view','Access Inventory module'),
('payroll','module','view','payroll.view','Access Payroll module'),
('tax','module','view','tax.view','Access Tax module'),
('tax','vat_return','generate','tax.vat_return.generate','Generate VAT returns'),
('tax','vat_return','submit','tax.vat_return.submit','Submit VAT returns'),
('admin','users','manage','admin.users.manage','Manage users, roles and access')
ON DUPLICATE KEY UPDATE
    module = VALUES(module),
    resource = VALUES(resource),
    action = VALUES(action),
    description = VALUES(description);

-- 2) Create baseline roles for every company.
INSERT INTO roles (company_id, name)
SELECT c.id, 'Limited User'
FROM companies c
WHERE NOT EXISTS (SELECT 1 FROM roles r WHERE r.company_id = c.id AND r.name = 'Limited User');

INSERT INTO roles (company_id, name)
SELECT c.id, 'Admin'
FROM companies c
WHERE NOT EXISTS (SELECT 1 FROM roles r WHERE r.company_id = c.id AND r.name = 'Admin');

INSERT INTO roles (company_id, name)
SELECT c.id, 'Accountant'
FROM companies c
WHERE NOT EXISTS (SELECT 1 FROM roles r WHERE r.company_id = c.id AND r.name = 'Accountant');

INSERT INTO roles (company_id, name)
SELECT c.id, 'Sales Clerk'
FROM companies c
WHERE NOT EXISTS (SELECT 1 FROM roles r WHERE r.company_id = c.id AND r.name = 'Sales Clerk');

INSERT INTO roles (company_id, name)
SELECT c.id, 'Stores Clerk'
FROM companies c
WHERE NOT EXISTS (SELECT 1 FROM roles r WHERE r.company_id = c.id AND r.name = 'Stores Clerk');

INSERT INTO roles (company_id, name)
SELECT c.id, 'HR/Payroll Officer'
FROM companies c
WHERE NOT EXISTS (SELECT 1 FROM roles r WHERE r.company_id = c.id AND r.name = 'HR/Payroll Officer');

-- 3) Admin gets every permission.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.name = 'Admin';

-- 4) Accountant permissions.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.code IN (
    'dashboard.view',
    'accounting.view',
    'sales.view',
    'purchases.view',
    'tax.view',
    'tax.vat_return.generate',
    'tax.vat_return.submit'
)
WHERE r.name = 'Accountant';

-- 5) Sales Clerk permissions.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.code IN (
    'dashboard.view',
    'sales.view'
)
WHERE r.name = 'Sales Clerk';

-- 6) Stores Clerk permissions.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.code IN (
    'dashboard.view',
    'inventory.view',
    'purchases.view'
)
WHERE r.name = 'Stores Clerk';

-- 7) HR/Payroll Officer permissions.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.code IN (
    'dashboard.view',
    'payroll.view'
)
WHERE r.name = 'HR/Payroll Officer';

-- 8) Limited User receives dashboard only. This is the default registration role.
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.code IN ('dashboard.view')
WHERE r.name = 'Limited User';

-- 9) Make sure existing administrator users have the Admin role.
INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
JOIN roles r ON r.company_id = COALESCE(u.company_id, r.company_id) AND r.name = 'Admin'
WHERE u.email IN ('super@admin.com', 'super@zim-erp.demo', 'sup@admin.com', 'magotovictor5@gmail.com')
  AND (u.company_id IS NULL OR r.company_id = u.company_id);

-- 10) Put users without roles onto Limited User for their company.
INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
JOIN roles r ON r.company_id = u.company_id AND r.name = 'Limited User'
WHERE u.company_id IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM user_roles ur WHERE ur.user_id = u.id);
