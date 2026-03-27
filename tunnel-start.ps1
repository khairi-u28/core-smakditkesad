# Start Cloudflare quick tunnel and auto-update APP_URL
$cloudflared = "C:\Program Files (x86)\cloudflared\cloudflared.exe"
$envFile = "$PSScriptRoot\.env"
$projectDir = $PSScriptRoot

Write-Host "Starting Cloudflare tunnel..." -ForegroundColor Cyan

# Start cloudflared and capture output
$job = Start-Job -ScriptBlock {
    param($cf)
    & $cf tunnel --url http://localhost:80 2>&1
} -ArgumentList $cloudflared

# Wait for tunnel URL to appear
$tunnelUrl = $null
$timeout = 30
$elapsed = 0

while (-not $tunnelUrl -and $elapsed -lt $timeout) {
    Start-Sleep -Seconds 1
    $elapsed++
    $output = Receive-Job $job -Keep
    foreach ($line in $output) {
        if ($line -match 'https://[a-z0-9\-]+\.trycloudflare\.com') {
            $tunnelUrl = $matches[0]
            break
        }
    }
}

if (-not $tunnelUrl) {
    Write-Host "ERROR: Could not get tunnel URL after $timeout seconds." -ForegroundColor Red
    Stop-Job $job
    exit 1
}

Write-Host "Tunnel URL: $tunnelUrl" -ForegroundColor Green

# Update APP_URL in .env
$envContent = Get-Content $envFile -Raw
$envContent = $envContent -replace 'APP_URL=.*', "APP_URL=$tunnelUrl"
Set-Content $envFile $envContent -NoNewline

Write-Host "Updated APP_URL in .env" -ForegroundColor Green

# Rebuild assets and config cache
Write-Host "Building assets..." -ForegroundColor Cyan
Set-Location $projectDir
& npm run build
& php artisan config:cache

Write-Host ""
Write-Host "=====================================" -ForegroundColor Green
Write-Host " Ready! Share this URL:" -ForegroundColor Green
Write-Host " $tunnelUrl" -ForegroundColor Yellow
Write-Host " Admin panel:" -ForegroundColor Green
Write-Host " $tunnelUrl/admin" -ForegroundColor Yellow
Write-Host "=====================================" -ForegroundColor Green
Write-Host ""
Write-Host "Tunnel is running. Press Ctrl+C to stop." -ForegroundColor Cyan

# Keep tunnel running in foreground
Receive-Job $job -Wait
