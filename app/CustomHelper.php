<?php

function getBangladeshCurrency($number) {
    // Getting decimal part
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen(floor($number));
    $i = 0;
    $str = [];
    
    // Word mapping for numbers
    $words = [
        0                   => '',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Forty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety'
    ];

    $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];

    // Processing the number
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number_part = $no % $divider;
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;

        if ($number_part) {
            $counter = count($str);
            $plural = ($counter && $number_part > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;

            // Check if $counter exceeds the size of $digits array
            $digit_word = isset($digits[$counter]) ? $digits[$counter] : ''; // Handle large numbers

            if ($number_part < 21) {
                // Handling numbers between 0 and 20
                $str[] = $words[$number_part] . ' ' . $digit_word . ' ' . $plural . $hundred;
            } else {
                // Handling numbers greater than 20
                $str[] = $words[floor($number_part / 10) * 10] . ' ' . $words[$number_part % 10] . ' ' . $digit_word . ' ' . $plural . $hundred;
            }
        } else {
            $str[] = null;
        }
    }

    // Constructing the Taka part
    $Taka = implode(' ', array_reverse($str));
    $Taka = preg_replace('/\s+/', ' ', trim($Taka)); // Clean up extra spaces

    // Handling decimal (poysa) part
    $poysa = ($decimal) ? " and " . $words[floor($decimal / 10)] . " " . $words[$decimal % 10] . ' Poysa' : '';

    // Returning full currency string
    return ($Taka ? $Taka . ' Taka' : '') . $poysa;
}

// Example usage:

