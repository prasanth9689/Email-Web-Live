#!/bin/bash
recipient_email="prasanthcena968@gmail.com"
verification_code="123456"
subject="Verification Code"
body=$(cat /etc/postfix/html/verification_email.html | sed "s/{{verification_code}}/$verification_code/")
echo -e "Subject:$subject\nMIME-Version: 1.0\nContent-Type: text/html\nFrom: akila@skyblue.co.in \nTo: $recipient_email" -- $body | /usr/sbin/sendmail -t
