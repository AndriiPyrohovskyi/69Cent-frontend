<?php
function getRelativeTime($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) return $diff . ' сек. тому';
    if ($diff < 3600) return floor($diff / 60) . ' хв. тому';
    if ($diff < 86400) return floor($diff / 3600) . ' год. тому';
    if ($diff < 2592000) return floor($diff / 86400) . ' днів тому';
    if ($diff < 31536000) return floor($diff / 2592000) . ' міс. тому';

    return floor($diff / 31536000) . ' років тому';
}
?>