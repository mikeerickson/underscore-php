<?php

class YouTube
{
    /**
     *  Check if input string is a valid YouTube URL
     *  and try to extract the YouTube Video ID from it.
     *  @author  Stephan Schmitz <eyecatchup@gmail.com>
     *  @param   $url   string   The string that shall be checked.
     *  @return  mixed           Returns YouTube Video ID, or (boolean) false.
     */
    public static function getId($url)
    {
        $pattern = '#^(?:https?://)?';    # Optional URL scheme. Either http or https.
        $pattern .= '(?:www\.)?';         #  Optional www subdomain.
        $pattern .= '(?:';                #  Group host alternatives:
        $pattern .=   'youtu\.be/';       #    Either youtu.be,
        $pattern .=   '|youtube\.com';    #    or youtube.com
        $pattern .=   '(?:';              #    Group path alternatives:
        $pattern .=     '/embed/';        #      Either /embed/,
        $pattern .=     '|/v/';           #      or /v/,
        $pattern .=     '|/watch\?v=';    #      or /watch?v=,
        $pattern .=     '|/watch\?.+&v='; #      or /watch?other_param&v=
        $pattern .=   ')';                #    End path alternatives.
        $pattern .= ')';                  #  End host alternatives.
        $pattern .= '([\w-]{11})';        # 11 characters (Length of Youtube video ids).
        $pattern .= '(?:.+)?$#x';         # Optional other ending URL parameters.
        preg_match($pattern, $url, $matches);

        return (isset($matches[1])) ? $matches[1] : false;
    }

    public static function getIframe($link, $width = '460px', $height = '255px')
    {
        $id = self::getId($link);
        return "<iframe src='https://www.youtube.com/embed/{$id}?rel=0' style='width: {$width}; height: {$height};' frameborder='0' allowfullscreen></iframe>";
    }

    public static function getTitle($link)
    {
        $video_id = self::getId($link);
        $url = "https://gdata.youtube.com/feeds/api/videos/". $video_id;
        $doc = new DOMDocument;
        $doc->load($url);

        return $doc->getElementsByTagName("title")->item(0)->nodeValue;
    }

    public static function getImage($link, $width = '460px', $height = '255px')
    {
        $video_id = self::getId($link);

        return "https://i4.ytimg.com/vi/{$video_id}/mqdefault.jpg";
    }
}