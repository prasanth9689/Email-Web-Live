<?php

$sudo_password = 'Prasanth968@@';
$shell_create_user = "echo '$sudo_password' | sudo -S useradd -m test18 |

                      echo 'test18:123456' | sudo chpasswd |
                       sudo -S mkdir -p /home/test18/Maildir |
                       sudo -S chown -R test18:test18 /home/test18/Maildir |
                       sudo -S systemctl restart postfix";

// Execute the command
exec($shell_create_user, $output, $status);

// Check if the command was successful
if ($status === 0) {
    echo "Command executed successfully!\n";
} else {
    echo "Command failed with status: $status\n";
    // You can also print the output for debugging
    echo implode("\n", $output);
}
?>
