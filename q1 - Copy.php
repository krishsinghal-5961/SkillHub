<?php
function isPrime($num) {
    if ($num <= 1) return false;
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) return false;
    }
    return true;
}

$sum = 0;
for ($i = 2; $i < 100; $i++) {
    if (isPrime($i)) {
        $sum += $i;
    }
}

echo "The sum of prime numbers less than 100 is: $sum";
?>
