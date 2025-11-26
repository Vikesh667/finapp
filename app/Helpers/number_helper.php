<?php

if (!function_exists('numberToWordsIndian')) {

    function numberToWordsIndian($amount) {

        $amount = str_replace(',', '', $amount);
        $amount = floatval($amount);

        $no = floor($amount);
        $point = round(($amount - $no) * 100);

        $words = array(
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight',
            9 => 'Nine', 10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        );

        $units = array('', 'Thousand', 'Lakh', 'Crore');
        $str = [];
        $unitIndex = 0;

        while ($no > 0) {
            if ($unitIndex == 0) {
                $chunk = $no % 1000;
                $no = floor($no / 1000);
            } else {
                $chunk = $no % 100;
                $no = floor($no / 100);
            }

            if ($chunk) {
                $text = '';
                $hundreds = floor($chunk / 100);
                $remainder = $chunk % 100;

                if ($hundreds > 0) {
                    $text .= $words[$hundreds] . ' Hundred';
                    if ($remainder > 0) $text .= ' ';
                }

                if ($remainder > 0) {
                    if ($remainder < 20) {
                        $text .= $words[$remainder];
                    } else {
                        $text .= $words[floor($remainder / 10) * 10];
                        if ($remainder % 10) $text .= " " . $words[$remainder % 10];
                    }
                }

                if ($units[$unitIndex]) $text .= " " . $units[$unitIndex];

                $str[] = trim($text);
            }

            $unitIndex++;
        }

        $result = implode(' ', array_reverse($str));

        if ($point > 0) {
            $result .= " Rupees and " . numberToWordsIndian($point) . " Paise Only";
        } else {
            $result .= " Rupees Only";
        }

        return $result;
    }
}
