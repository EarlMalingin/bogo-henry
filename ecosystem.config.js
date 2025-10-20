module.exports = {
  apps: [
    {
      name: 'mentorhub-socket',
      script: 'socket-server.js',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'development',
        SOCKET_PORT: 3001
      },
      env_production: {
        NODE_ENV: 'production',
        SOCKET_PORT: 3001
      }
    }
  ]
}; 