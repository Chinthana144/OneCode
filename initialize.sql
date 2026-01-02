-- adding necessary tables data

-- adding data to customer type table
INSERT INTO `customer_types` (`id`, `customerType`, `created_at`, `updated_at`) VALUES
(1, 'Labor', NULL, NULL),
(2, 'Cleaner', NULL, NULL),
(3, 'Security', NULL, NULL),
(4, 'Staff', NULL, NULL),
(5, 'Salesman', NULL, NULL),
(6, 'Technician', NULL, NULL),
(7, 'Manager', NULL, NULL);

-- adding data to user Roles table

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL),
(2, 'Owner', NULL, NULL),
(3, 'Manager', NULL, NULL),
(4, 'Client', NULL, NULL),
(5, 'Assistant Manager', NULL, NULL),
(6, 'Engineer', NULL, NULL),
(7, 'Accountant', NULL, NULL),
(8, 'Technician', NULL, NULL),
(9, 'Salesman', NULL, NULL),
(10, 'Intern', NULL, NULL);

-- add payment methods
INSERT INTO `paymethods` (`id`, `paymethod_name`, `created_at`, `updated_at`) VALUES
(1, 'Cash', NULL, NULL),
(2, 'Card', NULL, NULL),
(3, 'Bank Transfer', NULL, NULL),
(4, 'Apple Pay', NULL, NULL),
(5, 'Google Pay', NULL, NULL),
(6, 'Manual Entry', NULL, NULL);


-- add first user as admin
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Chinthana', 'chinthana144@gmail.com', NULL, '$2y$12$C9dr2DhMLZMJyFa/paM5wuNKkmNiRxgZJXEeku7g7DupQyAXKhKCy', 1, NULL, '2025-03-20 06:11:11', '2025-03-20 06:11:11');

-- add first camp (not necessary)
INSERT INTO `camps` (`id`, `name`, `location`, `contactPerson`, `contactPhone`, `contactEmail`, `mikritikIP`, `mikritikPort`, `mikrotikUsername`, `mikrotikPassword`, `radiusSecret`, `radiusIP`, `monthly_target`, `status`, `created_at`, `updated_at`) VALUES
(1, 'First Camp', 'First location', 'contact person', 'contact phone', 'contactperson@gmail.com', '192.168.22.1', '8728', 'admin', 'bluecat4', '0', '0', '0.00', '1', NULL, NULL);

-- add camp access for first camp
INSERT INTO `camp_users` (`id`, `camp_id`, `user_id`, `created_at`, `updated_at`) VALUES (NULL, '1', '1', NULL, NULL);

-- add data to pages table
INSERT INTO `pages` (`id`, `pagename`,`created_at`, `updated_at`) VALUES
(1, 'Home', NULL, NULL),
(2, 'Invoice', NULL, NULL),
(3, 'Customers', NULL, NULL),
(4, 'Packages', NULL, NULL),
(5, 'Subscriptions', NULL, NULL),
(6, 'Reports', NULL, NULL),
(7, 'Controls', NULL, NULL),
(8, 'Settings', NULL, NULL),
(9, 'Camps', NULL, NULL),
(10, 'Camp Users', NULL, NULL),
(11, 'Users', NULL, NULL),
(12, 'User Access', NULL, NULL);

-- add user access for admin user
INSERT INTO page_accesses(id, user_id, page_id, camp_id, `create`, `view`, `edit`, `delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1,1,1,1,NULL, NULL),
(2, 1, 2, 1, 1,1,1,1,NULL, NULL),
(3, 1, 3, 1, 1,1,1,1,NULL, NULL),
(4, 1, 4, 1, 1,1,1,1,NULL, NULL),
(5, 1, 5, 1, 1,1,1,1,NULL, NULL),
(6, 1, 6, 1, 1,1,1,1,NULL, NULL),
(7, 1, 7, 1, 1,1,1,1,NULL, NULL),
(8, 1, 8, 1, 1,1,1,1,NULL, NULL),
(9, 1, 9, 1, 1,1,1,1,NULL, NULL),
(10, 1, 10, 1, 1,1,1,1,NULL, NULL),
(11, 1, 11, 1, 1,1,1,1,NULL, NULL),
(12, 1, 12, 1, 1,1,1,1,NULL, NULL);
