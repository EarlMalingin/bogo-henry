module.exports = {
  apps: [
    {
      name: 'mentorhub-socket-dev',
      script: 'socket-server.js',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'development',
        SOCKET_PORT: 3001,
        LARAVEL_URL: 'http://localhost:8000'
      }
    },
    {
      name: 'mentorhub-socket',
      script: 'socket-server-prod.js',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env_production: {
        NODE_ENV: 'production',
        SOCKET_PORT: process.env.SOCKET_PORT || 3001,
        LARAVEL_URL: process.env.LARAVEL_URL || process.env.APP_URL || 'http://localhost:8000'
      }
    }
  ]
}; 