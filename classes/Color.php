<?php

class Color
{
    // Identifies groups of trackers in the column view of form records
    public function random_color ($minVal = 0, $maxVal = 255)
    {
        // Make sure the parameters will result in valid colours
        $minVal = $minVal < 0 || $minVal > 255 ? 0 : $minVal;
        $maxVal = $maxVal < 0 || $maxVal > 255 ? 255 : $maxVal;

        // Generate 3 values
        $r = mt_rand($minVal, $maxVal);
        $g = mt_rand($minVal, $maxVal);
        $b = mt_rand($minVal, $maxVal);

        // Return a hex colour ID string
        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    // For contact status text is the opposite color of it's background so that it's readable.
    public function opp_color($c, $inverse = false)
    {
        // short-hand
        if(strlen($c)== 3) {
            $c = $c{0}.$c{0}.$c{1}.$c{1}.$c{2}.$c{2};
        }
        // => Inverse Colour
        if ($inverse) {
            $r = (strlen($r=dechex(255-hexdec($c{0}.$c{1})))<2)?'0'.$r:$r;
            $g = (strlen($g=dechex(255-hexdec($c{2}.$c{3})))<2)?'0'.$g:$g;
            $b = (strlen($b=dechex(255-hexdec($c{4}.$c{5})))<2)?'0'.$b:$b;
            return $r.$g.$b;
        } else {
            // => Monotone based on darkness of original
            return array_sum(array_map('hexdec', str_split($c, 2))) > 255*1.5 ? '000000' : 'FFFFFF';
        }
    }

    public function hexLighter($hex,$factor = 30)
    {
        $new_hex = '';

        $base['R'] = hexdec($hex{0}.$hex{1});
        $base['G'] = hexdec($hex{2}.$hex{3});
        $base['B'] = hexdec($hex{4}.$hex{5});

        foreach ($base as $k => $v)
            {
            $amount = 255 - $v;
            $amount = $amount / 100;
            $amount = round($amount * $factor);
            $new_decimal = $v + $amount;

            $new_hex_component = dechex($new_decimal);
            if(strlen($new_hex_component) < 2)
                { $new_hex_component = "0".$new_hex_component; }
            $new_hex .= $new_hex_component;
            }

        return $new_hex;
    }

}