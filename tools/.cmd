docker-compose -f docker-compose.prod.yml run --rm certbot certonly --webroot --webroot-path=/etc/letsencrypt --email sys@skyreka.com --agree-tos -d lab-sodepac.skyreka.com
