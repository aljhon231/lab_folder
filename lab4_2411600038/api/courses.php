<?php
header('Content-Type: application/json; charset=utf-8');

$courseFile = __DIR__ . '/../data/courses.json';
$courses = file_exists($courseFile) ? json_decode(file_get_contents($courseFile), true) : [];

if (!is_array($courses)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Course data could not be loaded.']);
    exit;
}

echo json_encode([
    'success' => true,
    'data' => $courses,
    'timestamp' => date(DATE_ATOM)
]);
