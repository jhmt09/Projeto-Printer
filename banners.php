<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$bannerDir = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'banner';
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
$slides = [];

if (is_dir($bannerDir)) {
    $files = scandir($bannerDir);

    if (is_array($files)) {
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $bannerDir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($fullPath)) {
                continue;
            }

            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions, true)) {
                continue;
            }

            $name = pathinfo($file, PATHINFO_FILENAME);
            $altText = str_replace(['-', '_'], ' ', $name);

            $slides[] = [
                'src' => 'images/banner/' . rawurlencode($file) . '?v=' . filemtime($fullPath),
                'alt' => trim($altText) !== '' ? $altText : 'Banner industrial'
            ];
        }
    }
}

usort($slides, static function (array $a, array $b): int {
    return strnatcasecmp($a['src'], $b['src']);
});

echo json_encode(
    ['slides' => $slides],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
