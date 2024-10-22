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

2. Use `docker-compose` to start the project:
   ```bash
   docker-compose up -d
   ```

3. Check the running containers and find the associated PHP and Node containers:
   ```bash
   docker ps
   ```

4. Access the PHP container shell and run the following command:
   ```bash
   docker exec -it <php_container_name> bash
   composer install
   ```

5. Then, go to the Node container and run the following commands:
   ```bash
   docker exec -it <node_container_name> bash
   npm install
   npm run db:setup
   ```

6. Finally, start Vite:
   ```bash
   npm run dev
   ```

7. Access the application in your browser at [http://localhost:8080](http://localhost:8080) and PHPMyAdmin at [http://localhost:8081](http://localhost:8081).