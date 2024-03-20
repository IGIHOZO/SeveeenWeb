<?php
require("view.php");

class MainAction extends DBConnect
{
    public function saveUserVisit(
        $user_ip, $user_agent, $referral_source, $landing_page, $browser, $operating_system, $device_type,
        $screen_resolution, $language, $country, $city, $isp, $continent, $continentCode, $region, $regionName,
        $district, $zip, $lat, $lon, $timezone, $offset, $currency,
        $isp_new, $org, $asNumber, $asName, $mobile, $proxy, $hosting, $visit_timestamp
    ) {
        try {
            $pdo = $this->connect();

            $stmt = $pdo->prepare("
                INSERT INTO user_visits 
                (user_ip, user_agent, referral_source, landing_page, browser, operating_system, device_type, 
                screen_resolution, language, country, city, isp, continent, continentCode, region, regionName, 
                district, zip, lat, lon, timezone, offset, currency, isp_new, org, asNumber, asName, 
                mobile, proxy, hosting, visit_timestamp) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bindParam(1, $user_ip);
            $stmt->bindParam(2, $user_agent);
            $stmt->bindParam(3, $referral_source);
            $stmt->bindParam(4, $landing_page);
            $stmt->bindParam(5, $browser);
            $stmt->bindParam(6, $operating_system);
            $stmt->bindParam(7, $device_type);
            $stmt->bindParam(8, $screen_resolution);
            $stmt->bindParam(9, $language);
            $stmt->bindParam(10, $country);
            $stmt->bindParam(11, $city);
            $stmt->bindParam(12, $isp);
            $stmt->bindParam(13, $continent);
            $stmt->bindParam(14, $continentCode);
            $stmt->bindParam(15, $region);
            $stmt->bindParam(16, $regionName);
            $stmt->bindParam(17, $district);
            $stmt->bindParam(18, $zip);
            $stmt->bindParam(19, $lat);
            $stmt->bindParam(20, $lon);
            $stmt->bindParam(21, $timezone);
            $stmt->bindParam(22, $offset);
            $stmt->bindParam(23, $currency);
            $stmt->bindParam(24, $isp_new);
            $stmt->bindParam(25, $org);
            $stmt->bindParam(26, $asNumber);
            $stmt->bindParam(27, $asName);
            $stmt->bindParam(28, $mobile);
            $stmt->bindParam(29, $proxy);
            $stmt->bindParam(30, $hosting);
            $stmt->bindParam(31, $visit_timestamp);

            $result = $stmt->execute();

            if ($result) {
                echo "Data inserted successfully!";
            } else {
                echo "Error inserting data: " . implode(" - ", $stmt->errorInfo());
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}


$user_ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$referral_source = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : 'Direct'; // Extract domain from referral URL
$landing_page = $_SERVER['REQUEST_URI'];

// Fetching browser, operating system, and device type
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Check for common operating systems
if (strpos($userAgent, 'Windows') !== false) {
    $os = 'Windows';
} elseif (strpos($userAgent, 'Macintosh') !== false || strpos($userAgent, 'Mac OS X') !== false) {
    $os = 'Mac';
} elseif (strpos($userAgent, 'Linux') !== false) {
    $os = 'Linux';
} else {
    $os = 'Unknown';
}

// Check for common browsers
if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
    $browser = 'Internet Explorer';
} elseif (strpos($userAgent, 'Firefox') !== false) {
    $browser = 'Firefox';
} elseif (strpos($userAgent, 'Chrome') !== false) {
    $browser = 'Google Chrome';
} elseif (strpos($userAgent, 'Safari') !== false) {
    $browser = 'Safari';
} elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
    $browser = 'Opera';
} else {
    $browser = 'Unknown';
}

// Check for common device types
if (strpos($userAgent, 'Mobile') !== false || strpos($userAgent, 'Android') !== false || strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
    $deviceType = 'Mobile';
} elseif (strpos($userAgent, 'Tablet') !== false || strpos($userAgent, 'iPad') !== false) {
    $deviceType = 'Tablet';
} else {
    $deviceType = 'Desktop';
}

// Additional information about the user visit
$screen_resolution = $_GET['screen_resolution'] ?? null;
$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null;
$visit_timestamp = date('Y-m-d H:i:s');

// Fetch additional information from IP-API
$api_url = "http://ip-api.com/json/{$user_ip}";
$api_response = file_get_contents($api_url);

if ($api_response !== false) {
    $api_data = json_decode($api_response, true);

    // Extract relevant data from the API response
    $country = $api_data['country'] ?? 'Unknown';
    $city = $api_data['city'] ?? 'Unknown';
    $isp = $api_data['isp'] ?? 'Unknown';
    $continent = $api_data['continent'] ?? 'Unknown';
    $continentCode = $api_data['continentCode'] ?? 'Unknown';
    $region = $api_data['region'] ?? 'Unknown';
    $regionName = $api_data['regionName'] ?? 'Unknown';
    $district = $api_data['district'] ?? '';
    $zip = $api_data['zip'] ?? '';
    $lat = $api_data['lat'] ?? 0.0;
    $lon = $api_data['lon'] ?? 0.0;
    $timezone = $api_data['timezone'] ?? 'Unknown';
    $offset = $api_data['offset'] ?? 0;
    $currency = $api_data['currency'] ?? 'Unknown';
    $isp_new = $api_data['isp'] ?? 'Unknown';
    $org = $api_data['org'] ?? 'Unknown';
    $asNumber = $api_data['as'] ?? 'Unknown';
    $asName = $api_data['asname'] ?? 'Unknown';
    $mobile = $api_data['mobile'] ?? false;
    $proxy = $api_data['proxy'] ?? false;
    $hosting = $api_data['hosting'] ?? false;

    $mainAction = new MainAction();
    $mainAction->saveUserVisit(
        $user_ip, $user_agent, $referral_source, $landing_page, $browser, $os, $deviceType,
        $screen_resolution, $language, $country, $city, $isp, $continent, $continentCode, $region, $regionName,
        $district, $zip, $lat, $lon, $timezone, $offset, $currency,
        $isp_new, $org, $asNumber, $asName, $mobile, $proxy, $hosting, $visit_timestamp
    );
} else {
    die("Failed to fetch data from the IP-API.");
}
?>
