<?php
class Translation {
    private static $strings = [];
    private static $currentLang = 'es';
    
    public static function init($lang = 'es') {
        self::$currentLang = $lang;
        $langFile = __DIR__ . '/languages/' . $lang . '.php';
        
        if (file_exists($langFile)) {
            self::$strings = include($langFile);
        } else {
            // Fallback to Spanish
            self::$strings = include(__DIR__ . '/languages/es.php');
        }
        
        $_SESSION['current_lang'] = self::$currentLang;
    }
    
    public static function get($key, $params = []) {
        $string = self::$strings[$key] ?? $key;
        
        // Replace parameters
        if (!empty($params)) {
            foreach ($params as $index => $value) {
                $string = str_replace('%' . ($index + 1), $value, $string);
            }
        }
        
        return $string;
    }
    
    public static function getCurrentLang() {
        return self::$currentLang;
    }
    
    public static function toggleLanguage() {
        $newLang = (self::$currentLang === 'es') ? 'en' : 'es';
        self::init($newLang);
        return $newLang;
    }
}

// Initialize translations
$defaultLang = $_SESSION['current_lang'] ?? 'es';
Translation::init($defaultLang);

// Helper function
function t($key, $params = []) {
    return Translation::get($key, $params);
}
?>