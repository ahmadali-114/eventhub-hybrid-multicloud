# EventHub — College Event Management System

A PHP 8 + MySQL web application for college event discovery and registration. Students can create accounts, browse events, register while seats are available, and view their registrations. Administrators can manage events and review participation.

## Features

- Student registration and login with PHP password hashing
- Role-protected student and administrator areas
- Event listing and event details
- Event registration with duplicate protection and transaction-based capacity checks
- Admin event create, read, update and delete operations
- MySQL schema with foreign keys and unique registration constraint
- CSRF protection on state-changing forms
- Session ID regeneration after login and HTTP-only session cookies
- Docker Compose deployment for repeatable local and cloud testing

## Requirements

- Docker Engine and Docker Compose plugin (recommended), or PHP 8.2+ with `mysqli` and MySQL 8+

## Run with Docker Compose

1. Copy the environment template:

   ```bash
   cp .env.example .env
   ```

2. Edit `.env`. Set unique, strong values for `DB_PASSWORD`, `MYSQL_ROOT_PASSWORD`, and `ADMIN_PASSWORD`. Do not commit `.env`.

3. Build and start the application:

   ```bash
   docker compose up -d --build
   docker compose ps
   ```

4. Create the administrator account (the command reads credentials from `.env`):

   ```bash
   set -a && . ./.env && set +a
   docker compose exec -e ADMIN_EMAIL="$ADMIN_EMAIL" -e ADMIN_PASSWORD="$ADMIN_PASSWORD" -e ADMIN_NAME="$ADMIN_NAME" app php scripts/create_admin.php
   ```

5. Open `http://localhost:8080` (or the port configured in `.env`).

The database is initialized from `database/events.sql` on the first MySQL volume creation. To reset the lab database and lose all stored data:

```bash
docker compose down -v
docker compose up -d --build
```

## Test accounts

Create the administrator using the CLI command above. Student accounts are created through the registration page. No default admin password is shipped in this repository.

## Useful commands

```bash
docker compose logs -f app
docker compose logs -f db
docker compose exec app php -v
docker compose exec db mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME"
docker compose down
```

## Deploy to an AWS EC2 test server

This repository includes a Docker-based deployment path. For a first lab deployment:

1. Launch an Ubuntu 24.04 EC2 instance and allow SSH (TCP 22) only from your current public IP.
2. Install Docker Engine and the Docker Compose plugin on the instance.
3. Clone this repository onto the instance.
4. Create `.env` on the EC2 instance using strong unique secrets. Never commit or upload `.env` to GitHub.
5. Run `docker compose up -d --build` and create the administrator account using the command above.
6. For initial testing, allow TCP 8080 only from your own IP in the EC2 security group. Do not expose MySQL port 3306.
7. For public access, configure a domain, HTTPS/TLS (for example, a reverse proxy with Let's Encrypt), and restrict the application/database network. Do not treat an open HTTP port as production-ready.

For a production architecture, move MySQL to a separately managed private database host/service, use TLS, backups, monitoring, least-privilege credentials, and a managed secrets solution. The Compose setup here is a learning/test deployment, not a high-availability production design.

## GitHub

```bash
git init
git add .
git commit -m "Initial EventHub application and Docker deployment"
git branch -M main
git remote add origin https://github.com/YOUR_GITHUB_USERNAME/eventhub-hybrid-multicloud.git
git push -u origin main
```

Check `git status` before pushing and confirm `.env` is not staged.

## Security notes

- Use synthetic/demo participant data only.
- Keep `.env`, database dumps, SSH private keys and cloud credentials out of Git.
- Rotate any credentials that were previously shared or committed.
- This is a portfolio learning application; perform a security review before real-world use.
