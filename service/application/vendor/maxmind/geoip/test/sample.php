#!/usr/bin/php -q
<?php

// This code demonstrates how to lookup the country by IP Address

require_once "GeoIp.php";
require_once "GeoIpRegionVars.php";
require_once "GeoIpRecord.php";
require_once "GeoIpDNSRecord.php";

// Uncomment if querying against GeoIP/Lite City.
// include("geoipcity.inc");

$gi = geoip_open("/usr/local/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);

echo geoip_country_code_by_addr($gi, "24.24.24.24") . "\t" .
     geoip_country_name_by_addr($gi, "24.24.24.24") . "\n";
echo geoip_country_code_by_addr($gi, "80.24.24.24") . "\t" .
     geoip_country_name_by_addr($gi, "80.24.24.24") . "\n";

geoip_close($gi);

?>
