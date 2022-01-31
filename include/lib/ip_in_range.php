<?php
class ipInRange {
    public static function decbin32 ($dec) {
        return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
    }

    public static function ipv4_in_range($ip, $range) {
        if (strpos($range, '/') !== false) {
            // $range is in IP/NETMASK format
            list($range, $netmask) = explode('/', $range, 2);
            if (strpos($netmask, '.') !== false) {
                // $netmask is a 255.255.0.0 format
                $netmask = str_replace('*', '0', $netmask);
                $netmask_dec = ip2long($netmask);
                return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
            } else {
                // $netmask is a CIDR size block
                // fix the range argument
                $x = explode('.', $range);
                while(count($x)<4) $x[] = '0';
                list($a,$b,$c,$d) = $x;
                $range = sprintf("%u.%u.%u.%u", empty($a)?'0':$a, empty($b)?'0':$b,empty($c)?'0':$c,empty($d)?'0':$d);
                $range_dec = ip2long($range);
                $ip_dec = ip2long($ip);

                # Strategy 2 - Use math to create it
                $wildcard_dec = pow(2, (32-$netmask)) - 1;
                $netmask_dec = ~ $wildcard_dec;

                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        } else {
            // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
            if (strpos($range, '*') !==false) { // a.b.*.* format
                // Just convert to A-B format by setting * to 0 for A and 255 for B
                $lower = str_replace('*', '0', $range);
                $upper = str_replace('*', '255', $range);
                $range = "$lower-$upper";
            }

            if (strpos($range, '-')!==false) { // A-B format
                list($lower, $upper) = explode('-', $range, 2);
                $lower_dec = (float)sprintf("%u",ip2long($lower));
                $upper_dec = (float)sprintf("%u",ip2long($upper));
                $ip_dec = (float)sprintf("%u",ip2long($ip));
                return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
            }
            return false;
        }
    }

    public static function ip2long6($ip) {
        if (substr_count($ip, '::')) {
            $ip = str_replace('::', str_repeat(':0000', 8 - substr_count($ip, ':')) . ':', $ip);
        }

        $ip = explode(':', $ip);
        $r_ip = '';
        foreach ($ip as $v) {
            $r_ip .= str_pad(base_convert($v, 16, 2), 16, 0, STR_PAD_LEFT);
        }

        return base_convert($r_ip, 2, 10);
    }

    // Get the ipv6 full format and return it as a decimal value.
    public static function get_ipv6_full($ip)
    {
        $pieces = explode ("/", $ip, 2);
        $left_piece = $pieces[0];
        $right_piece = $pieces[1];

        // Extract out the main IP pieces
        $ip_pieces = explode("::", $left_piece, 2);
        $main_ip_piece = $ip_pieces[0];
        $last_ip_piece = $ip_pieces[1];

        // Pad out the shorthand entries.
        $main_ip_pieces = explode(":", $main_ip_piece);
        foreach($main_ip_pieces as $key=>$val) {
            $main_ip_pieces[$key] = str_pad($main_ip_pieces[$key], 4, "0", STR_PAD_LEFT);
        }

        // Check to see if the last IP block (part after ::) is set
        $last_piece = "";
        $size = count($main_ip_pieces);
        if (trim($last_ip_piece) != "") {
            $last_piece = str_pad($last_ip_piece, 4, "0", STR_PAD_LEFT);

            // Build the full form of the IPV6 address considering the last IP block set
            for ($i = $size; $i < 7; $i++) {
                $main_ip_pieces[$i] = "0000";
            }
            $main_ip_pieces[7] = $last_piece;
        }
        else {
            // Build the full form of the IPV6 address
            for ($i = $size; $i < 8; $i++) {
                $main_ip_pieces[$i] = "0000";
            }
        }

        // Rebuild the final long form IPV6 address
        $final_ip = implode(":", $main_ip_pieces);

        return ip2long6($final_ip);
    }

    public static function ipv6_in_range($ip, $range_ip)
    {
        $pieces = explode ("/", $range_ip, 2);
        $left_piece = $pieces[0];
        $right_piece = $pieces[1];

        // Extract out the main IP pieces
        $ip_pieces = explode("::", $left_piece, 2);
        $main_ip_piece = $ip_pieces[0];
        $last_ip_piece = $ip_pieces[1];

        // Pad out the shorthand entries.
        $main_ip_pieces = explode(":", $main_ip_piece);
        foreach($main_ip_pieces as $key=>$val) {
            $main_ip_pieces[$key] = str_pad($main_ip_pieces[$key], 4, "0", STR_PAD_LEFT);
        }

        // Create the first and last pieces that will denote the IPV6 range.
        $first = $main_ip_pieces;
        $last = $main_ip_pieces;

        // Check to see if the last IP block (part after ::) is set
        $last_piece = "";
        $size = count($main_ip_pieces);
        if (trim($last_ip_piece) != "") {
            $last_piece = str_pad($last_ip_piece, 4, "0", STR_PAD_LEFT);

            // Build the full form of the IPV6 address considering the last IP block set
            for ($i = $size; $i < 7; $i++) {
                $first[$i] = "0000";
                $last[$i] = "ffff";
            }
            $main_ip_pieces[7] = $last_piece;
        }
        else {
            // Build the full form of the IPV6 address
            for ($i = $size; $i < 8; $i++) {
                $first[$i] = "0000";
                $last[$i] = "ffff";
            }
        }

        // Rebuild the final long form IPV6 address
        $first = ip2long6(implode(":", $first));
        $last = ip2long6(implode(":", $last));
        $in_range = ($ip >= $first && $ip <= $last);

        return $in_range;
    }
}
?>