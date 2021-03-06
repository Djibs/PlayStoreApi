<?php

include_once 'simple_html_dom.php';

include_once 'data/album.php';
include_once 'data/app.php';
include_once 'data/artist.php';
include_once 'data/book.php';
include_once 'data/device.php';
include_once 'data/magazine.php';
include_once 'data/movie.php';
include_once 'data/newspaper.php';
include_once 'data/song.php';
include_once 'data/tvepisode.php';
include_once 'data/tvshow.php';

/**
 * Searchs and gets data form the Google Play Store.
 *
 * @author RedInput
 *
 * @link https://github.com/RedInput/PlayStoreApi
 *
 * @license Apache 2.0
 *
 * @version 1.0.1
 */
class PlayStoreApi
{
    private $GOOGLE_PLAY_URL = 'https://play.google.com/store/search';

    private $country_codes = array('AF', 'AX', 'AL', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BQ', 'BA', 'BW', 'BV', 'BR', 'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 'CA', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG', 'CD', 'CK', 'CR', 'CI', 'HR', 'CU', 'CW', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'GF', 'PF', 'TF', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT', 'HM', 'VA', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IM', 'IL', 'IT', 'JM', 'JP', 'JE', 'JO', 'KZ', 'KE', 'KI', 'KP', 'KR', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'MP', 'NO', 'OM', 'PK', 'PW', 'PS', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'BL', 'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SX', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'SS', 'ES', 'LK', 'SD', 'SR', 'SJ', 'SZ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'US', 'UM', 'UY', 'UZ', 'VU', 'VE', 'VN', 'VG', 'VI', 'WF', 'EH', 'YE', 'ZM', 'ZW');
    private $language_codes = array('aa', 'ab', 'ae', 'af', 'ak', 'am', 'an', 'ar', 'as', 'av', 'ay', 'az', 'ba', 'be', 'bg', 'bh', 'bi', 'bm', 'bn', 'bo', 'br', 'bs', 'ca', 'ce', 'ch', 'co', 'cr', 'cs', 'cu', 'cv', 'cy', 'da', 'de', 'dv', 'dz', 'ee', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'ff', 'fi', 'fj', 'fo', 'fr', 'fy', 'ga', 'gd', 'gl', 'gn', 'gu', 'gv', 'ha', 'he', 'hi', 'ho', 'hr', 'ht', 'hu', 'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 'ik', 'io', 'is', 'it', 'iu', 'ja', 'jv', 'ka', 'kg', 'ki', 'kj', 'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 'ku', 'kv', 'kw', 'ky', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 'lu', 'lv', 'mg', 'mh', 'mi', 'mk', 'ml', 'mn', 'mr', 'ms', 'mt', 'my', 'na', 'nb', 'nd', 'ne', 'ng', 'nl', 'nn', 'no', 'nr', 'nv', 'ny', 'oc', 'oj', 'om', 'or', 'os', 'pa', 'pi', 'pl', 'ps', 'pt', 'qu', 'rm', 'rn', 'ro', 'ru', 'rw', 'sa', 'sc', 'sd', 'se', 'sg', 'si', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'ss', 'st', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'ti', 'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty', 'ug', 'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'wo', 'xh', 'yi', 'yo', 'za', 'zh', 'zu');

    private $country;
    private $language;

    public function __construct()
    {
        $this->country = 'US';
        $this->language = 'en';
    }

    private function curlExec($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($curl);
        curl_close($curl);

        return $html;
    }

    private function extractPageToken($html)
    {
        $indexNbp = strpos($html, 'var nbp');
        $indexSc = strpos($html, 'var sc');
        $varnbp = substr($html, $indexNbp,$indexSc-$indexNbp);

        $indexX22 = strpos($varnbp, "\\x22,\\x22");
        $pagetoken = substr($varnbp, $indexX22+9);

        $indexX22 = strpos($pagetoken, "\\x22,\\x22");
        $pagetoken = substr($pagetoken, 0, $indexX22);

        return $pagetoken;
    }

    /**
     * Sets the country code to use when querying the Google Play Store.
     *
     * @param string $newCountry 2-letter ISO code of the country
     */
    public function setCountryCode($newCountry = 'US')
    {
        $newCountry = strtoupper($newCountry);
        if (!in_array($newCountry, $this->country_codes)) {
            throw new Exception('Country code must be a valid ISO code');
        }
        $this->country = $newCountry;
    }

    /**
     * Sets the language code to use when querying the Google Play Store.
     *
     * @param string $newLanguage 2-letter ISO code of the language
     */
    public function setLanguageCode($newLanguage = 'en')
    {
        $newLanguage = strtolower($newLanguage);
        if (!in_array($newLanguage, $this->language_codes)) {
            throw new Exception('Language code must be a valid ISO code');
        }
        $this->language = $newLanguage;
    }

    private function formatUrlSearch($type, $query, $page = '')
    {
        if (!isset($query)) {
            throw new Exception('A query term is mandatory.');
        } else {
            $query = urlencode($query);
        }

        if (!empty($page)) {
            $pagetoken = '&pagTok='.$page;
        } else {
            $pagetoken = '';
        }

        return $this->GOOGLE_PLAY_URL.'?docType='.$type.'&q='.$query.$pagetoken.'&gl='.$this->country.'&hl='.$this->language;
    }

    /**
     * Performs a search of albums in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Album's item
     */
    public function searchAlbums($query, $page = '')
    {
        $url = $this->formatUrlSearch(2, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $albumid = substr($result->getAttribute('data-docid'), 6);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->plaintext;
            $artist = $result->find('.subtitle', 0)->plaintext;
            $artisthref = $result->find('.subtitle', 0)->href;
            $artistid = substr($artisthref, strpos($artisthref, '?id=') + 4);

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $album = new Album();

            if (isset($albumid)) {
                $album->setAlbumId($albumid);
                $album->setUrl('https://play.google.com/store/music/album?id='.$albumid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $album->setImage($image);
            }
            if (isset($title)) {
                $album->setTitle($title);
            }
            if (isset($artist)) {
                $album->setArtist($artist);
            }
            if (isset($artistid)) {
                $album->setArtistId($artistid);
            }
            if (isset($price)) {
                $album->setPrice($price);
            }

            $items[] = $album;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                     'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of apps in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of App's item
     */
    public function searchApps($query, $page = '')
    {
        $url = $this->formatUrlSearch(1, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $pname = $result->getAttribute('data-docid');
            $icon = substr($result->find('.cover-image', 0)->src, 0, -5);
            $name = $result->find('.title', 0)->plaintext;
            $dev = $result->find('.subtitle', 0)->plaintext;

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $app = new App();

            if (isset($pname)) {
                $app->setPackage($pname);
                $app->setUrl('https://play.google.com/store/apps/details?id='.$pname.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($icon)) {
                $app->setIcon($icon);
            }
            if (isset($name)) {
                $app->setName($name);
            }
            if (isset($dev)) {
                $app->setDeveloper($dev);
            }
            if (isset($price)) {
                $app->setPrice($price);
            }

            $items[] = $app;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                     'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of artists in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Artist's item
     */
    public function searchArtists($query, $page = '')
    {
        $url = $this->formatUrlSearch(3, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $artistid = substr($result->getAttribute('data-docid'), 7);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $name = $result->find('.title', 0)->plaintext;

            $artist = new Artist();

            if (isset($artistid)) {
                $artist->setArtistId($artistid);
                $artist->setUrl('https://play.google.com/store/music/artist?id='.$artistid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($name)) {
                $artist->setName($name);
            }
            if (isset($image)) {
                $artist->setImage($image);
            }

            $items[] = $artist;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                         'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of books in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Book's item
     */
    public function searchBooks($query, $page = '')
    {
        $url = $this->formatUrlSearch(5, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $bookid = substr($result->getAttribute('data-docid'), 5);
            $image = substr($result->find('.cover-image', 0)->src, 0, -10);
            $title = $result->find('.title', 0)->plaintext;
            $author = $result->find('.subtitle', 0)->plaintext;

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $book = new Book();

            if (isset($bookid)) {
                $book->setBookId($bookid);
                $book->setUrl('https://play.google.com/store/books/details?id='.$bookid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $book->setImage($image);
            }
            if (isset($title)) {
                $book->setTitle($title);
            }
            if (isset($author)) {
                $book->setAuthor($author);
            }
            if (isset($price)) {
                $book->setPrice($price);
            }

            $items[] = $book;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                                 'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of devices in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Device's item
     */
    public function searchDevices($query, $page = '')
    {
        $url = $this->formatUrlSearch(14, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $deviceid = substr($result->getAttribute('data-docid'), 7);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->plaintext;

            if ($result->find('.price', 0) != null) {
                $price = $result->find('.price', 0)->plaintext;
            }

            $device = new Device();

            if (isset($deviceid)) {
                $device->setDeviceId($deviceid);
                $device->setUrl('https://store.google.com/product/'.$deviceid.'?gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $device->setImage($image);
            }
            if (isset($title)) {
                $device->setTitle($title);
            }
            if (isset($price)) {
                $device->setPrice($price);
            }

            $items[] = $device;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                                     'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of magazines in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Magazine's item
     */
    public function searchMagazines($query, $page = '')
    {
        $url = $this->formatUrlSearch(16, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $magazineid = substr($result->getAttribute('data-docid'), 9);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->title;

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $magazine = new Magazine();

            if (isset($magazineid)) {
                $magazine->setMagazineId($magazineid);
                $magazine->setUrl('https://play.google.com/store/newsstand/details?id='.$magazineid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $magazine->setImage($image);
            }
            if (isset($title)) {
                $magazine->setTitle($title);
            }
            if (isset($price)) {
                $magazine->setPrice($price);
            }

            $items[] = $magazine;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                                         'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of movies in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Movie's item
     */
    public function searchMovies($query, $page = '')
    {
        $url = $this->formatUrlSearch(6, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $movieid = substr($result->getAttribute('data-docid'), 6);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->plaintext;

            if ($result->find('.subtitle', 0) != null) {
                $category = $result->find('.subtitle', 0)->plaintext;
            } else {
                $category = null;
            }

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $movie = new Movie();

            if (isset($movieid)) {
                $movie->setMovieId($movieid);
                $movie->setUrl('https://play.google.com/store/movies/details?id='.$movieid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $movie->setImage($image);
            }
            if (isset($title)) {
                $movie->setTitle($title);
            }
            if (isset($category)) {
                $movie->setCategory($category);
            }
            if (isset($price)) {
                $movie->setPrice($price);
            }

            $items[] = $movie;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                                             'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of newspapaers in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Newspapaer's item
     */
    public function searchNewspapers($query, $page = '')
    {
        $url = $this->formatUrlSearch(24, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $newspaperid = substr($result->getAttribute('data-docid'), 12);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->plaintext;

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $newspaper = new Newspaper();

            if (isset($newspaperid)) {
                $newspaper->setNewspaperId($newspaperid);
                $newspaper->setUrl('https://play.google.com/store/newsstand/news?id='.$newspaperid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $newspaper->setImage($image);
            }
            if (isset($title)) {
                $newspaper->setTitle($title);
            }
            if (isset($price)) {
                $newspaper->setPrice($price);
            }

            $items[] = $newspaper;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                                                 'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of songs in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of Song's item
     */
    public function searchSongs($query, $page = '')
    {
        $url = $this->formatUrlSearch(4, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $albumhref = $result->find('.card-click-target', 0)->href;
            $albumidstart = strpos($albumhref, '?id=') + 4;
            $albumidend = strpos($albumhref, '&amp;tid=');
            $albumid = substr($albumhref, $albumidstart, $albumidend - $albumidstart);

            $songid = substr($result->getAttribute('data-docid'), 5);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->plaintext;
            $artist = $result->find('.subtitle', 0)->plaintext;
            $artisthref = $result->find('.subtitle', 0)->href;
            $artistid = substr($artisthref, strpos($artisthref, '?id=') + 4);

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $song = new Song();

            if (isset($albumid)) {
                $song->setAlbumId($albumid);
            }
            if (isset($songid)) {
                $song->setSongId($songid);
            }
            if (isset($image)) {
                $song->setImage($image);
            }
            if (isset($title)) {
                $song->setTitle($title);
            }
            if (isset($artist)) {
                $song->setArtist($artist);
            }
            if (isset($artistid)) {
                $song->setArtistId($artistid);
            }
            if (isset($price)) {
                $song->setPrice($price);
            }
            if ((isset($albumid)) && (isset($songid))) {
                $song->setUrl('https://play.google.com/store/music/album?id='.$albumid.'&tid=song-'.$songid.'&gl='.$this->country.'&hl='.$this->language);
            }

            $items[] = $song;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                             'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of TV episodes in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of TvEpisode's item
     */
    public function searchTvEpisodes($query, $page = '')
    {
        $url = $this->formatUrlSearch(20, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $showseasonhref = $result->find('.card-click-target', 0)->href;

            $showidstart = strpos($showseasonhref, '?id=') + 4;
            $showidend = strpos($showseasonhref, '&amp;cdid=');
            $showid = substr($showseasonhref, $showidstart, $showidend - $showidstart);

            $seasonidstart = strpos($showseasonhref, '&amp;cdid=') + 19;
            $seasonidend = strpos($showseasonhref, '&amp;gdid=');
            $seasonid = substr($showseasonhref, $seasonidstart, $seasonidend - $seasonidstart);

            $episodeid = substr($result->getAttribute('data-docid'), 10);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $eptitle = $result->find('.epname-no-number', 0)->plaintext;
            $shtitle = $result->find('.title-showname', 0)->plaintext;
            $date = $result->find('.subtitle-releasedate', 0)->plaintext;

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $episode = new Episode();

            if (isset($episodeid)) {
                $episode->setEpisodeId($episodeid);
            }
            if (isset($seasonid)) {
                $episode->setSeasonId($seasonid);
            }
            if (isset($showid)) {
                $episode->setShowId($showid);
            }
            if (isset($image)) {
                $episode->setImage($image);
            }
            if (isset($eptitle)) {
                $episode->setEpisodeTitle($eptitle);
            }
            if (isset($shtitle)) {
                $episode->setShowTitle($shtitle);
            }
            if (isset($date)) {
                $episode->setDate($date);
            }
            if (isset($price)) {
                $episode->setPrice($price);
            }
            if ((isset($episodeid)) && (isset($seasonid)) && (isset($showid))) {
                $episode->setUrl('https://play.google.com/store/tv/show?id='.$showid.'&cdid=tvseason-'.$seasonid.'&gdid=tvepisode-'.$episodeid.'&gl='.$this->country.'&hl='.$this->language);
            }

            $items[] = $episode;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                                 'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Performs a search of TV shows in the Google Play Store.
     *
     * @param string $query The string to query
     * @param string $page  The page token of the page to load
     *
     * @return A json object with the page token of the next page and an array of TvShow's item
     */
    public function searchTvShows($query, $page = '')
    {
        $url = $this->formatUrlSearch(18, $query, $page);
        $html = $this->curlExec($url);
        $nextPage = $this->extractPageToken($html);

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        if (count($htmldom->find('.card-list')) != 1) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $results = $htmldom->find('.card-list', 0)->find('.card');
        $items = array();

        foreach ($results as $result) {
            $showid = substr($result->getAttribute('data-docid'), 7);
            $image = substr($result->find('.cover-image', 0)->src, 0, -5);
            $title = $result->find('.title', 0)->title;

            if ($result->find('.subtitle', 0) != null) {
                $category = $result->find('.subtitle', 0)->title;
            } else {
                $category = null;
            }

            if ($result->find('.pon', 0) != null) {
                $has_price = trim($result->find('.pon', 0)->plaintext);
                if ($has_price > 0) {
                    $price = $result->find('.display-price', 0)->plaintext;
                }
            }

            $show = new Show();

            if (isset($showid)) {
                $show->setShowId($showid);
                $show->setUrl('https://play.google.com/store/tv/show?id='.$showid.'&gl='.$this->country.'&hl='.$this->language);
            }
            if (isset($image)) {
                $show->setImage($image);
            }
            if (isset($title)) {
                $show->setTitle($title);
            }
            if (isset($category)) {
                $show->setCategory($category);
            }
            if (isset($price)) {
                $show->setPrice($price);
            }

            $items[] = $show;
        }

        $htmldom->clear();
        unset($htmldom);

        $arr = array('nextPageToken' => $nextPage,
                             'items' => $items, );

        return json_encode($arr);
    }

    /**
     * Get the details of an album in the Google Play Store.
     *
     * @param string $album_id The id of the album to retrieve
     *
     * @return A json object with the details of the album requested
     */
    public function getAlbum($album_id)
    {
        $url = 'https://play.google.com/store/music/album?id='.$album_id.'&gl='.$this->country.'&hl='.$this->language;
        $html = $this->curlExec($url);

        $pos = stripos($html, "We're sorry, the requested URL was not found on this server.");
        if ($pos !== false) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        $image = substr($htmldom->find('.cover-image', 0)->src, 0, -5);
        $title = $htmldom->find('.document-title', 0)->first_child()->plaintext;
        $artist = $htmldom->find('a[class=document-subtitle primary]', 0)->plaintext;
        $artisthref = $htmldom->find('a[class=document-subtitle primary]', 0)->href;
        $artistid = substr($artisthref, strpos($artisthref, '?id=') + 4);

        if ($htmldom->find('meta[itemprop=price]', 0) != null) {
            $price = $htmldom->find('meta[itemprop=price]', 0)->content;
        }

        if ($htmldom->find('.description', 0) != null) {
            if ($htmldom->find('.description', 0)->find('meta[itemprop=description]', 0) != null) {
                $description = $htmldom->find('.description', 0)->find('meta[itemprop=description]', 0)->content;
            }
        }

        $genre = trim($htmldom->find('a[class=document-subtitle category]', 0)->plaintext);
        $tracks = count($htmldom->find('.track-list-row'));

        if ($htmldom->find('meta[itemprop=ratingValue]', 0) != null){
            $rating_value = floatval($htmldom->find('meta[itemprop=ratingValue]', 0)->content);
        }

        if ($htmldom->find('meta[itemprop=ratingCount]', 0) != null){
            $rating_count = intval($htmldom->find('meta[itemprop=ratingCount]', 0)->content);
        }

        $album = new Album();
        $album->setAlbumId($album_id);
        $album->setUrl($url);

        if (isset($image)) {
            $album->setImage($image);
        }
        if (isset($title)) {
            $album->setTitle($title);
        }
        if (isset($artist)) {
            $album->setArtist($artist);
        }
        if (isset($artistid)) {
            $album->setArtistId($artistid);
        }
        if (isset($price)) {
            $album->setPrice($price);
        }
        if (isset($description)) {
            $album->setDescription($description);
        }
        if (isset($genre)) {
            $album->setGenre($genre);
        }
        if (isset($tracks)) {
            $album->setTracks($tracks);
        }
        if (isset($rating_value)) {
            $album->setRatingValue($rating_value);
        }
        if (isset($rating_count)) {
            $album->setRatingCount($rating_count);
        }

        $htmldom->clear();
        unset($htmldom);

        return json_encode($album);
    }

    /**
     * Get the details of an app in the Google Play Store.
     *
     * @param string $package The package name of the app to retrieve
     *
     * @return A json object with the details of the app requested
     */
    public function getApp($package)
    {
        $url = 'https://play.google.com/store/apps/details?id='.$package.'&gl='.$this->country.'&hl='.$this->language;
        $html = $this->curlExec($url);

        $pos = stripos($html, "We're sorry, the requested URL was not found on this server.");
        if ($pos !== false) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        $icon = substr($htmldom->find('.cover-image', 0)->src, 0, -5);
        $name = $htmldom->find('.id-app-title', 0)->plaintext;
        $developer = $htmldom->find('a[class=document-subtitle primary]', 0)->first_child()->plaintext;

        if ($htmldom->find('meta[itemprop=price]', 0) != null) {
            $price = $htmldom->find('meta[itemprop=price]', 0)->content;
        }

        if ($htmldom->find('.description', 0) != null) {
            if ($htmldom->find('.description', 0)->find('div[itemprop=description]', 0) != null) {
                $description = $htmldom->find('.description', 0)->find('div[itemprop=description]', 0)->first_child()->plaintext;
            }
        }

        $category = trim($htmldom->find('a[class=document-subtitle category]', 0)->plaintext);

        if ($htmldom->find('meta[itemprop=ratingValue]', 0)!= null) {
            $rating_value = floatval($htmldom->find('meta[itemprop=ratingValue]', 0)->content);
        }
        if ($htmldom->find('meta[itemprop=ratingCount]', 0)!= null) {
            $rating_count = intval($htmldom->find('meta[itemprop=ratingCount]', 0)->content);
        }

        if ($htmldom->find('div[itemprop=datePublished]', 0)!= null) {
            $date_updated = $htmldom->find('div[itemprop=datePublished]', 0)->plaintext;
        }
        if ($htmldom->find('div[itemprop=fileSize]', 0)!= null) {
            $file_size = $htmldom->find('div[itemprop=fileSize]', 0)->plaintext;
        }
        if ($htmldom->find('div[itemprop=numDownloads]', 0)!= null) {
            $num_downloads = $htmldom->find('div[itemprop=numDownloads]', 0)->plaintext;
        }
        if ($htmldom->find('div[itemprop=softwareVersion]', 0)!= null) {
            $version = $htmldom->find('div[itemprop=softwareVersion]', 0)->plaintext;
        }
        if ($htmldom->find('div[itemprop=operatingSystems]', 0)!= null) {
            $req_android = $htmldom->find('div[itemprop=operatingSystems]', 0)->plaintext;
        }
        if ($htmldom->find('div[itemprop=contentRating]', 0)!= null) {
            $content_rating = $htmldom->find('div[itemprop=contentRating]', 0)->plaintext;
        }


        $app = new App();
        $app->setPackage($package);
        $app->setUrl($url);

        if (isset($icon)) {
            $app->setIcon($icon);
        }
        if (isset($name)) {
            $app->setName($name);
        }
        if (isset($developer)) {
            $app->setDeveloper($developer);
        }
        if (isset($price)) {
            $app->setPrice($price);
        }
        if (isset($description)) {
            $app->setDescription($description);
        }
        if (isset($category)) {
            $app->setCategory($category);
        }
        if (isset($rating_value)) {
            $app->setRatingValue($rating_value);
        }
        if (isset($rating_count)) {
            $app->setRatingCount($rating_count);
        }
        if (isset($date_updated)) {
            $app->setDateUpdated($date_updated);
        }
        if (isset($file_size)) {
            $app->setFileSize($file_size);
        }
        if (isset($num_downloads)) {
            $app->setNumDownloads($num_downloads);
        }
        if (isset($version)) {
            $app->setVersion($version);
        }
        if (isset($req_android)) {
            $app->setRequiredAndroid($req_android);
        }
        if (isset($content_rating)) {
            $app->setContentRating($content_rating);
        }


        $htmldom->clear();
        unset($htmldom);

        return json_encode($app);
    }

    /**
     * Get the details of an artist in the Google Play Store.
     *
     * @param string $artist_id The id of the artist to retrieve
     *
     * @return A json object with the details of the artist requested
     */
    public function getArtist($artist_id)
    {
        $url = 'https://play.google.com/store/music/artist?id='.$artist_id.'&gl='.$this->country.'&hl='.$this->language;
        $html = $this->curlExec($url);

        $pos = stripos($html, "We're sorry, the requested URL was not found on this server.");
        if ($pos !== false) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        $image = substr($htmldom->find('.cover-image', 0)->src, 0, -5);
        $name = $htmldom->find('.document-title', 0)->first_child()->plaintext;

        if ($htmldom->find('meta[itemprop=description]', 0) != null) {
            $about = $htmldom->find('meta[itemprop=description]', 0)->content;
        }


        $artist = new Artist();
        $artist->setArtistId($artist_id);
        $artist->setUrl($url);

        if (isset($image)) {
            $artist->setImage($image);
        }
        if (isset($name)) {
            $artist->setName($name);
        }
        if (isset($about)) {
            $artist->setAbout($about);
        }


        $htmldom->clear();
        unset($htmldom);

        return json_encode($artist);
    }

    /**
     * Get the details of a book in the Google Play Store.
     *
     * @param string $book_id The id of the book to retrieve
     *
     * @return A json object with the details of the book requested
     */
    public function getBook($book_id)
    {
        $url = 'https://play.google.com/store/books/details?id='.$book_id.'&gl='.$this->country.'&hl='.$this->language;
        $html = $this->curlExec($url);

        $pos = stripos($html, "We're sorry, the requested URL was not found on this server.");
        if ($pos !== false) {
            $message['msg'] = 'No results found';

            return json_encode($message);
        }

        $htmldom = new simple_html_dom();
        $htmldom->load($html);

        $image = substr($htmldom->find('.cover-image', 0)->src, 0, -10);
        $title = $htmldom->find('.document-title', 0)->first_child()->plaintext;
        $author = $htmldom->find('.book-author-last', 0)->plaintext;

        if ($htmldom->find('meta[itemprop=price]', 0) != null) {
            $price = $htmldom->find('meta[itemprop=price]', 0)->content;
        }

        if ($htmldom->find('meta[itemprop=description]', 0) != null) {
            $description = $htmldom->find('meta[itemprop=description]', 0)->content;
        }

        $pages = $htmldom->find('span[itemprop=numberOfPages]', 0)->plaintext;
        $language = $htmldom->find('div[itemprop=inLanguage]', 0)->plaintext;
        $isbn = $htmldom->find('div[itemprop=isbn]', 0)->plaintext;

        if ($htmldom->find('meta[itemprop=ratingValue]', 0) != null){
            $rating_value = floatval($htmldom->find('meta[itemprop=ratingValue]', 0)->content);
        }

        if ($htmldom->find('meta[itemprop=ratingCount]', 0) != null){
            $rating_count = intval($htmldom->find('meta[itemprop=ratingCount]', 0)->content);
        }


        $book = new Book();
        $book->setBookId($book_id);
        $book->setUrl($url);

        if (isset($image)) {
            $book->setImage($image);
        }
        if (isset($title)) {
            $book->setTitle($title);
        }
        if (isset($author)) {
            $book->setAuthor($author);
        }
        if (isset($price)) {
            $book->setPrice($price);
        }
        if (isset($description)) {
            $book->setDescription($description);
        }
        if (isset($pages)) {
            $book->setPages($pages);
        }
        if (isset($language)) {
            $book->setLanguages($language);
        }
        if (isset($isbn)) {
            $book->setIsbn($isbn);
        }
        if (isset($rating_value)) {
            $book->setRatingValue($rating_value);
        }
        if (isset($rating_count)) {
            $book->setRatingCount($rating_count);
        }


        $htmldom->clear();
        unset($htmldom);

        return json_encode($book);
    }
}
