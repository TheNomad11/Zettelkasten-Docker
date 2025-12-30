# Zettelkasten-Docker
A simple Zettelkasten web app for organizing your notes and ideas using the Zettelkasten method.

## Features

- **Markdown Support**: Write and format notes using Markdown syntax
- **Tag System**: Organize notes with custom tags
- **Linking**: Create connections between notes with internal links
- **Search**: Full-text search across all your notes
- **Export Options**:
  - Single Markdown file with table of contents
  - ZIP archive with individual Markdown files
  - JSON backup for complete data preservation
- **Import Functionality**: Import from JSON backups
- **Security Features**:
  - CSRF protection
  - Session management with timeout
  - Rate limiting for login attempts
  - Secure password hashing
- **Responsive Design**: Works on desktop and mobile devices
- **Progressive Web App**: Installable with offline capabilities

## Installation

### Quick Setup with Docker

1. **Clone this repository:**
   ```bash
   git clone https://github.com/TheNomad11/zettelkasten.git
   cd zettelkasten
   ```

2. **Set your password** (before first run):
   ```bash
   # Generate a new password hash
   php -r "echo password_hash('YourStrongPassword123!', PASSWORD_DEFAULT);"
   
   ```

3. **Edit `config/config.php`** and update:
   ```php
   define('PASSWORD_HASH', '$2y$10$your_generated_hash_here');
   ```

4. **Start the container**:
   ```bash
   docker compose up -d --build
   ```

4. **Login** with:
   - Username: (your username)
   - Password: (the password you just set)   ```

5. **Start the application**:
   ```bash
   docker-compose up -d --build
   ```

6. **Access the application** at `http://localhost:8488`

## Usage

### Creating Notes
1. Click "Create New Zettel"
2. Add a title and content (Markdown supported)
3. Add tags (comma-separated)
4. Link to other notes using their IDs

### Exporting Notes
- **Single Markdown**: All notes in one file with table of contents
- **ZIP Archive**: Individual Markdown files with INDEX.md
- **JSON Backup**: Complete backup with all metadata

### Importing Notes
1. Go to the Import page
2. Upload a JSON backup file
3. Review the import summary

## Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **Session Management**: Automatic timeout and strict mode
- **Rate Limiting**: Prevents brute force attacks
- **Secure Headers**: XSS and clickjacking protection
- **Input Validation**: All user input is sanitized

## 📁 Directory Structure

```
zettelkasten/
├── docker-compose.yml
├── Dockerfile
├── config/
│   └── config.php      # Your config (edit this!)
├── index.php
├── import.php
├── export.php
├── login.php
├── logout.php
├── styles.css
└── data/
    └── zettels/        # Your notes (persisted)
```

## 🔧 Configuration

### Change Port
Edit `docker-compose.yml`:
```yaml
ports:
  - "3000:80"  # Access on http://localhost:3000
```

### Change Timezone
Edit `docker-compose.yml`:
```yaml
environment:
  - TZ=America/New_York  # Your timezone
```

### Data Location
Your data is stored in:
- **Notes**: `./data/zettels/` - All your notes (backed up automatically via volume)
- **Config**: `./config/config.php` - Configuration file (you edit this directly)

**Important**: Back up the `./data/` directory regularly!

## 🛠️ Common Commands

```bash
# Start
docker compose up -d

# Stop
docker compose down

# View logs (useful for debugging!)
docker compose logs -f

# View PHP error logs
docker compose exec zettelkasten tail -f /var/log/apache2/error.log

# Restart
docker compose restart

# Rebuild after code changes
docker compose up -d --build

# Fix permissions (if you get 500 errors)
docker compose exec zettelkasten chown -R www-data:www-data /var/www/html/zettels
docker compose exec zettelkasten chmod -R 775 /var/www/html/zettels

# Stop and remove everything (keeps data)
docker compose down

# Remove everything including data (DANGEROUS!)
docker compose down -v
```

## 🔄 Updating

```bash
# Pull new changes
git pull  # or copy new files

# Rebuild and restart
docker compose up -d --build
```

## 🔒 Security Notes

- **Password**: Change the default password immediately
- **Access**: Only expose to localhost (default) or use a reverse proxy with HTTPS
- **Backups**: Regularly backup `./data/` directory
- **Updates**: Keep PHP base image updated

## 🐛 Troubleshooting

### 500 Internal Server Error
This usually means permission issues with the zettels directory:

```bash
# Check the error log
docker compose logs zettelkasten

# Or check Apache error log
docker compose exec zettelkasten tail -f /var/log/apache2/error.log

# Fix permissions
docker compose exec zettelkasten chown -R www-data:www-data /var/www/html/zettels
docker compose exec zettelkasten chmod -R 775 /var/www/html/zettels

# Restart after fixing
docker compose restart
```

### Permission Errors
```bash
# Fix permissions
docker compose exec zettelkasten chown -R www-data:www-data /var/www/html/zettels
docker compose exec zettelkasten chmod 775 /var/www/html/zettels
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

