<?php
session_start();

$con = mysqli_connect("localhost", "root", "prasanth", "skyblue_mail");

// Get the raw POST data
$json = file_get_contents("php://input");

// Decode the JSON data into a PHP array
$data = json_decode($json, true);

if (json_last_error() === JSON_ERROR_NONE) {
    $access = htmlspecialchars($data["acc"]);
}

switch ($access) {
    case "inbox_message_delete":
        $hostname = "{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX";
        $username = "prasanth";
        $password = "Prasanth968@@";

        ($inbox = imap_open($hostname, $username, $password)) or
            die(
                json_encode(["success" => false, "error" => imap_last_error()])
            );

        $response = ["success" => false];

        if (isset($data["delete"]) && is_array($data["delete"])) {
            foreach ($data["delete"] as $email_id) {
                $email_id = (int) $email_id;
                imap_delete($inbox, $email_id);
            }
            imap_expunge($inbox);
            $response["success"] = true;
            $response["deleted"] = $input["delete"];
        }

        imap_close($inbox);
        echo json_encode($response);

        break;

    case "send_mail":
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
        // GET Host domain name pending
        // Like @skyblue.co.in or @companyname.com
        $tempHostName = "skyblue.co.in";

        $fromAddress = $username . "@" . $tempHostName;
        $toAddress = htmlspecialchars($data["to_address"]);
        $rawMessage = $data["message"];
        $plainMessage = strip_tags($data["message"]);
        $subject = htmlspecialchars($data["subject"]);
        $date = date("r");

        $headers = "From: $fromAddress\r\n";
        $headers .= "To: $toAddress\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Date: $date\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";

        $htmlMessage =
            "<html><head><title></title></head><body> " .
            $rawMessage .
            "</body></html>";

        // Message body with boundary parts
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "$plainMessage\r\n\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "$htmlMessage\r\n\r\n";

        $body .= "--$boundary--";

        if (mail($toAddress, $subject, $body, $headers)) {
            array_push($data, [
                "access auth" => "true",
                "status" => "1",
                "message" => "Message has been sent. success.",
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);

            $headers .= $body;

            $imapHost = "{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}";
            $imapStream = imap_open($imapHost, $username, $password);

            if ($imapStream) {
                //  imap_append($imapStream, $imapHost, $body);
                imap_append($imapStream, $imapHost . "Sent", $headers);
                imap_close($imapStream);
                //  echo "Message saved to Sent folder.";
            } else {
                // echo "IMAP error: " . imap_last_error();
            }
        } else {
            array_push($data, [
                "access auth" => "false",
                "status" => "2",
                "message" => "Message not sent. Failure.",
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);
        }
        break;

    case "get_inbox":
        $mUserName = htmlspecialchars($data["email"]);
        $mPassword = htmlspecialchars($data["password"]);

        $hostname = "{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX";

        ($inbox = imap_open($hostname, $mUserName, $mPassword)) or
            die("Cannot connect to mailbox: " . imap_last_error());

        $numMessages = imap_num_msg($inbox);
        $email_ids = imap_search($inbox, "ALL");
        $inbox_data = ["status" => "true", "message" => "Email avalable"];
        $emails = [];
        if ($email_ids) {
            // sort them by ascending date
            rsort($email_ids); // Sort email IDs by ascending date

            foreach ($email_ids as $email_id) {
                $header = imap_headerinfo($inbox, $email_id);
                $subject = $header->subject;
                $from = $header->fromaddress;
                $date = $header->date;
                $decoded_subject0 = imap_utf8($subject);
                $sender_name = $header->fromaddress;

                if (isset($header->from[0]->personal)) {
                    $sender_name = $header->from[0]->personal;
                }
                $decoded_subject = imap_utf8($subject);
                array_push($emails, [
                    "from" => $sender_name,
                    "subject" => $decoded_subject,
                ]);
            }
            header("Content-Type:Application/json");

            $formatted = [
                "status" => $data["status"] ?? "true",
                "message" => $data["message"] ?? "Email avalable",
                "emails" => $emails,
            ];

            echo json_encode($formatted, JSON_PRETTY_PRINT);
        } else {
            echo "No emails found.";
        }
        imap_close($inbox);
        break;

    case "create_user":
        // Check username availability
        $mUserName = htmlspecialchars($data["username"]);
        $mPassword = htmlspecialchars($data["password"]);

        $sql = "SELECT username FROM users WHERE username = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $mUserName); // "s" means the database expects a string
        $stmt->execute();
        $result = $stmt->get_result();
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            array_push($data, [
                "access auth" => "true",
                "status" => "2",
                "message" => "Username not availabe. try another username",
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);
            return;
        }
        global $mUserName;
        global $mPassword;

        $mUserName = htmlspecialchars($data["username"]);
        $mPassword = htmlspecialchars($data["password"]);

        // 1. Create system user for the email address
        $sudo_password = "Prasanth968@@";
        $shell_create_user = "echo '$sudo_password' | sudo -S useradd -m $mUserName";
        $shell_create_new_password = "echo '$mUserName:$mPassword' | sudo chpasswd";
        $shell_create_mail_dir = "echo '$sudo_password' | sudo -S mkdir -p /home/$mUserName/Maildir";
        $shell_add_permission = "    echo '$sudo_password' | sudo -S chown -R $mUserName:$mUserName /home/$mUserName/Maildir ";
        $shell_restart_postfix = "echo '$sudo_password' | sudo -S systemctl restart postfix ";

        shell_exec($shell_create_user);
        shell_exec($shell_create_new_password);
        shell_exec($shell_create_mail_dir);
        shell_exec($shell_add_permission);

        exec($shell_restart_postfix, $output, $status);

        if ($status === 0) {
            $mUserName1 = htmlspecialchars($data["username"]);
            $mPassword1 = htmlspecialchars($data["password"]);

            $_SESSION["username"] = $mUserName1;
            $_SESSION["password"] = $mPassword1;

            // Save user information to db
            $Query = "INSERT INTO users (username, password) 
                             VALUES ('$mUserName', '$mPassword')";
            if (mysqli_query($con, $Query)) {
                // Insert success
                array_push($data, [
                    "access auth" => "true",
                    "status" => "1",
                    "message" => "Email created success",
                ]);
                header("Content-Type:Application/json");
                print json_encode($data);
            } else {
                // Insert failed
                array_push($data, [
                    "access auth" => "true",
                    "status" => "2",
                    "message" => "Error creating user: " . $output,
                ]);
                header("Content-Type:Application/json");
                print json_encode($data);
            }
        } else {
            array_push($data, [
                "access auth" => "true",
                "status" => "2",
                "message" => "Error creating user: " . $output,
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);
            exit();
        }
        break;

    case "mail_check_user":
        $mMobile = htmlspecialchars($data["mobile"]);

        $sql = "SELECT mobile FROM users WHERE mobile = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $mMobile); // "s" means the database expects a string
        $stmt->execute();
        $result = $stmt->get_result();
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            array_push($data, [
                "access auth" => "true",
                "status" => "2",
                "message" => "User already exists!. Please login",
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);
            return;
        }
        array_push($data, [
            "access auth" => "true",
            "status" => "1",
            "message" => "New user",
        ]);
        header("Content-Type:Application/json");
        print json_encode($data);
        break;

    case "user_login":
        $mUsername = htmlspecialchars($data["email"]);
        $mPassword = htmlspecialchars($data["password"]);

        $query =
            "SELECT username, password FROM users WHERE username = ? AND password = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param("ss", $mUsername, $mPassword);
            $stmt->execute();
            $stmt->bind_result($mUsername, $mPassword);
            if ($stmt->fetch()) {
                $_SESSION["username"] = $mUsername;
                $_SESSION["password"] = $mPassword;

                array_push($data, [
                    "access auth" => "true",
                    "status" => "1",
                    "message" => "Account found",
                ]);
                header("Content-Type:Application/json");
                print json_encode($data);
            } else {
                // Account not found!
                array_push($data, [
                    "access auth" => "true",
                    "status" => "2",
                    "message" => "Check username and password!",
                ]);
                header("Content-Type:Application/json");
                print json_encode($data);
            }
            $stmt->close();
        }
        break;

    case "user_login_imap":
        // Get the raw POST data
        $json = file_get_contents("php://input");

        // Decode the JSON data into a PHP array
        $data = json_decode($json, true);

        $hostname = "{imap.skyblue.co.in:993/imap/ssl/novalidate-cert}INBOX";
        $username = htmlspecialchars($data["email"]);
        $password = htmlspecialchars($data["password"]);

        // Try to connect to the IMAP server
        $inbox = @imap_open($hostname, $username, $password);

        if ($inbox) {
            //      echo "Login successful!";
            array_push($data, [
                "access auth" => "true",
                "status" => "1",
                "message" => "Login successful!",
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);
            imap_close($inbox);
        } else {
            //     echo "Login failed: " . imap_last_error();
            array_push($data, [
                "access auth" => "true",
                "status" => "2",
                "message" => "Check email and password!",
            ]);
            header("Content-Type:Application/json");
            print json_encode($data);
        }
        break;

    default:
        array_push($data, [
            "access auth" => false,
            "status" => "0",
            "message" => "Wrong access key",
        ]);
        header("Content-Type:Application/json");
        print json_encode($data);
        break;
}
?>
