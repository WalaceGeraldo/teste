<?php
echo "admin (password123): " . password_hash('password123', PASSWORD_DEFAULT) . "\n";
echo "user (user123): " . password_hash('user123', PASSWORD_DEFAULT) . "\n";
echo "qa (qatest): " . password_hash('qatest', PASSWORD_DEFAULT) . "\n";
echo "testadmin (pass123): " . password_hash('pass123', PASSWORD_DEFAULT) . "\n";
echo "testuser (userpass): " . password_hash('userpass', PASSWORD_DEFAULT) . "\n";
echo "runner_admin (123): " . password_hash('123', PASSWORD_DEFAULT) . "\n";
echo "runner_user (abc): " . password_hash('abc', PASSWORD_DEFAULT) . "\n";
