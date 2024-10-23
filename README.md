# Backend Engineer Recruitment Test

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository:
   ```bash
   git clone <REPO_URL>
   cd <project_name>
   ```

2. Create a .env file based on the example provided
   ```bash
   cp .env.example .env
   ```

3. Use `docker-compose` to start the project:
   ```bash
   docker-compose up -d
   ```

4. Check the running containers and find the associated PHP and Node containers:
   ```bash
   docker ps
   ```

5. Access the PHP container shell and run the following command:
   ```bash
   docker exec -it <php_container_name> bash
   composer install
   ```

6. Then, go to the Node container and run the following commands:
   ```bash
   docker exec -it <node_container_name> bash
   npm install
   npm run db:setup
   ```

7. For development, run the following command:
   ```bash
   npm run dev
   ```
   For production, build the assets using:
   ```bash
   npm run build
   ```
   *Make sure to set the APP_ENV variable in the .env file accordingly, depending on whether you are in development (APP_ENV=development) or production (APP_ENV=production).*

8. Access the application in your browser at [http://localhost:8080](http://localhost:8080) and PHPMyAdmin at [http://localhost:8081](http://localhost:8081).