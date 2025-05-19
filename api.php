<?php
// 随机图片API - 支持尺寸参数和图片大小分类
$smallImageDir = __DIR__ . '/image/small'; // 小于600x600的图片目录
$largeImageDir = __DIR__ . '/image/large'; // 大于等于600x600的图片目录

// 获取请求参数
$width = isset($_GET['width']) ? (int)$_GET['width'] : null;
$height = isset($_GET['height']) ? (int)$_GET['height'] : null;
$preferSize = isset($_GET['size']) ? strtolower($_GET['size']) : ''; // 可选参数：small或large

// 根据请求参数选择图片目录
if ($preferSize === 'small') {
    $imageDir = $smallImageDir;
} elseif ($preferSize === 'large') {
    $imageDir = $largeImageDir;
} else {
    // 如果未指定尺寸偏好，随机选择一个目录
    $imageDir = (rand(0, 1) === 0) ? $smallImageDir : $largeImageDir;
}

// 读取目录中的所有图片
$images = glob("$imageDir/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

// 如果指定目录没有图片，尝试从另一个目录获取
if (empty($images)) {
    $imageDir = ($imageDir === $smallImageDir) ? $largeImageDir : $smallImageDir;
    $images = glob("$imageDir/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
}

// 随机选择一张图片
if (!empty($images)) {
    $randomImage = $images[array_rand($images)];
    
    // 获取图片信息
    $imageInfo = getimagesize($randomImage);
    $mime = $imageInfo['mime'];
    
    // 设置响应头
    header("Content-Type: $mime");
    
    // 如果需要调整尺寸，使用GD库处理
    if ($width && $height) {
        $src = imagecreatefromstring(file_get_contents($randomImage));
        $dst = imagecreatetruecolor($width, $height);
        
        // 调整大小并保持比例
        imagecopyresampled(
            $dst, $src, 
            0, 0, 0, 0, 
            $width, $height, 
            $imageInfo[0], $imageInfo[1]
        );
        
        // 输出处理后的图片
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($dst);
                break;
            case 'image/png':
                imagepng($dst);
                break;
            case 'image/gif':
                imagegif($dst);
                break;
        }
        
        // 释放资源
        imagedestroy($src);
        imagedestroy($dst);
    } else {
        // 直接输出原始图片（保持原始尺寸）
        readfile($randomImage);
    }
} else {
    // 没有图片时返回默认占位图
    header("Content-Type: image/svg+xml");
    echo '<svg width="800" height="600" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-size="20" fill="#999">No images found</text></svg>';
}
?>