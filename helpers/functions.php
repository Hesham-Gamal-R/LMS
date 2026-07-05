<?php

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isStudent(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function isDoctor(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'doctor';
}

function currentUser(): array {
    return $_SESSION['user'] ?? [];
}

function currentUserId(): int {
    return (int)($_SESSION['user_id'] ?? 0);
}

function flashGet(string $key): string {
    $val = $_SESSION[$key] ?? '';
    unset($_SESSION[$key]);
    return $val;
}
 
function timeAgo(string $datetime): string {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    $minutes = floor($diff / 60);
    $hours   = floor($diff / 3600);

    if ($minutes < 1) {
        return 'Just now';
    }

    if ($minutes < 60) {
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    }

    return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
}

function sanitize(string $input): string {
    return trim(strip_tags($input));
}

function redirect(string $path): void {
    header('Location: ' . '/' . ltrim($path, '/'));
    exit;
}



