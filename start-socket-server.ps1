Write-Host "Starting Socket.IO Server for MentorHub..." -ForegroundColor Green
Write-Host ""
Write-Host "Make sure you have Node.js installed and dependencies installed with: npm install" -ForegroundColor Yellow
Write-Host ""
Write-Host "Starting server on port 3001..." -ForegroundColor Cyan
Write-Host ""

try {
    node socket-server.js
} catch {
    Write-Host "Error starting socket server: $_" -ForegroundColor Red
    Write-Host "Make sure Node.js is installed and you've run 'npm install'" -ForegroundColor Yellow
}

Read-Host "Press Enter to continue"
