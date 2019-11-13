<?php
/**
 * Class to create short URLs and decode shortened URLs
 * 
 * @author CodexWorld.com <contact@codexworld.com> 
 * @copyright Copyright (c) 2018, CodexWorld.com
 * @url https://www.codexworld.com
 */
class Shortener {

    protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
    protected static $table = "urls";
    protected static $checkUrlExists = false;
    protected static $codeLength = 4;
    protected $pdo;
    protected $timestamp;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->timestamp = date("Y-m-d H:i:s");
    }

    public function urlToShortCode($url, $description) {
        if (empty($url)) {
            throw new Exception("No URL was supplied.");
        }

        if ($this->validateUrlFormat($url) == false) {
            throw new Exception("URL does not have a valid format.");
        }

        if (self::$checkUrlExists) {
            if (!$this->verifyUrlExists($url)) {
                throw new Exception("URL does not appear to exist.");
            }
        }

        $shortCode = $this->urlExistsInDB($url);
        if ($shortCode == false) {
            $shortCode = $this->createShortCode($url, $description);
        }

        return $shortCode;
    }

    protected function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }

    protected function verifyUrlExists($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    protected function urlExistsInDB($url) {
        $query = "SELECT short_url FROM " . self::$table . " WHERE long_url = :long_url LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result["short_url"];
    }

    protected function createShortCode($url, $description) {
        $shortCode = $this->generateRandomString(self::$codeLength);
        $id = $this->insertUrlInDB($url, $shortCode, $description);
        return $shortCode;
    }

    protected function generateRandomString($length = 6) {
        $sets = explode('|', self::$chars);
        $all = '';
        $randString = '';
        foreach ($sets as $set) {
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }

    protected function insertUrlInDB($url, $code, $description) {
        $query = "INSERT INTO " . self::$table . " (long_url, short_url, description, created) VALUES (:long_url, :short_url, :description, :timestamp)";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url,
            "short_url" => $code,
            "description" => $description,
            "timestamp" => $this->timestamp
        );
        $stmnt->execute($params);

        return $this->pdo->lastInsertId();
    }

    public function shortCodeToUrl($code, $increment = true) {
        if (empty($code)) {
            throw new Exception("No short code was supplied.");
        }

        if ($this->validateShortCode($code) == false) {
            throw new Exception("Short code does not have a valid format.");
        }

        $urlRow = $this->getUrlFromDB($code);
        if (empty($urlRow)) {
            throw new Exception("Short code does not appear to exist.");
        }

        if ($increment == true) {
            $this->incrementCounterAndInsertIntoRecords($urlRow["id"]);
        }

        return $urlRow["long_url"];
    }

    protected function validateShortCode($code) {
        $rawChars = str_replace('|', '', self::$chars);
        return preg_match("|[" . $rawChars . "]+|", $code);
    }

    protected function getUrlFromDB($code) {
        $query = "SELECT id, long_url FROM " . self::$table . " WHERE short_url = :short_url LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "short_url" => $code
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result;
    }

    protected function incrementCounterAndInsertIntoRecords($id) {
        
        // inserting into urls
        $query = "UPDATE " . self::$table . " SET hits = hits + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "id" => $id
        );
        $stmt->execute($params);
        
        // getting location from IP
//------------------------------------------------------------------------------------------------
// USE THIS SNIPPENT WHEN TESTING AS IT WILL GET YOUR IP ADDRESS.
        
        $myPublicIP = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com")); //public ip on a local server (XAMPP)            
        $details = json_decode(file_get_contents("http://ipinfo.io/{$myPublicIP}/json"));
//        var_dump($details);
//------------------------------------------------------------------------------------------------
        
//------------------------------------------------------------------------------------------------
// USE THIS SNIPPENT WHEN DEPLOYING. THE SNIPPET BELOW WILL GET THE IP ADDRESS OF WEBSITE VISITORS.
// 
//        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//            //ip from share internet
//            $ip = $_SERVER['HTTP_CLIENT_IP'];
//        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            //ip pass from proxy
//            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        } else {
//            $ip = $_SERVER['REMOTE_ADDR'];
//        }
//        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
//        var_dump($details);
//------------------------------------------------------------------------------------------------
        $customerCity = $details->city;
        $customerCountry = $details->country;
        
        //inserting into records
        $query = "INSERT INTO records (country, city, created, id) VALUES (:country, :city, :created, :id)";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "country" => $customerCountry,
            "city" => $customerCity,
            "created" => $this->timestamp,
            "id" => $id
        );
        $stmt->execute($params);
    }
}
