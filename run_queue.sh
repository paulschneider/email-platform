#!/bin/bash
php /var/www/html/public_html/email.quintilesclinicaltrials.co.uk/public/email-platform/artisan --env=production --timeout=240 --sleep=180 queue:listen