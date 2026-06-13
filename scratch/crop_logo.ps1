Add-Type -AssemblyName System.Drawing

$srcPath = "public/assets/img/logo.png"
$backupPath = "public/assets/img/logo_backup.png"

# Copy original to backup
Copy-Item $srcPath $backupPath -Force

$bmp = New-Object System.Drawing.Bitmap($srcPath)
$width = $bmp.Width
$height = $bmp.Height

$minX = $width
$maxX = 0
$minY = $height
$maxY = 0

Write-Output "Original dimensions: $width x $height"
Write-Output "Scanning pixels for alpha channel..."

# Scan pixels to find content boundaries
for ($y = 0; $y -lt $height; $y += 2) { # scan every 2nd pixel for speed
    for ($x = 0; $x -lt $width; $x += 2) {
        $pixel = $bmp.GetPixel($x, $y)
        if ($pixel.A -gt 15) { # Alpha > 15
            if ($x -lt $minX) { $minX = $x }
            if ($x -gt $maxX) { $maxX = $x }
            if ($y -lt $minY) { $minY = $y }
            if ($y -gt $maxY) { $maxY = $y }
        }
    }
}

$bmp.Dispose()

if ($minX -le $maxX -and $minY -le $maxY) {
    # Add a small padding of 15px around the logo content
    $padding = 15
    $minX = [Math]::Max(0, $minX - $padding)
    $minY = [Math]::Max(0, $minY - $padding)
    $maxX = [Math]::Min($width - 1, $maxX + $padding)
    $maxY = [Math]::Min($height - 1, $maxY + $padding)

    $cropWidth = $maxX - $minX + 1
    $cropHeight = $maxY - $minY + 1

    Write-Output "Cropping coordinates: Left=$minX, Top=$minY, Width=$cropWidth, Height=$cropHeight"

    $srcBmp = New-Object System.Drawing.Bitmap($backupPath)
    $cropBmp = New-Object System.Drawing.Bitmap($cropWidth, $cropHeight)
    $g = [System.Drawing.Graphics]::FromImage($cropBmp)
    
    $srcRect = New-Object System.Drawing.Rectangle($minX, $minY, $cropWidth, $cropHeight)
    $destRect = New-Object System.Drawing.Rectangle(0, 0, $cropWidth, $cropHeight)
    
    $g.DrawImage($srcBmp, $destRect, $srcRect, [System.Drawing.GraphicsUnit]::Pixel)
    
    $g.Dispose()
    $srcBmp.Dispose()
    
    # Save cropped image
    $cropBmp.Save($srcPath, [System.Drawing.Imaging.ImageFormat]::Png)
    $cropBmp.Dispose()
    Write-Output "Logo cropped and saved successfully!"
} else {
    Write-Output "Error: No visible content found in the image."
}
