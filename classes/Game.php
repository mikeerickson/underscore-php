<?php
class Game {
    /* Define some variables */
    private $_expPoints = 0;
    private $_currLevel = 0;
    private $_achievementsList = array();
    private $_currentType;
    private $_imagePath;
    private $_imageExtension;

    /* Set the location where the images for the badges are stored */
    public function setAchievementsBadgesPath($path, $extension = "png"){
        $this->_imagePath = $path;
        $this->_imageExtension = $extension;
    }

    /* Set achievements */
    public function setAchievement($type, $exponent){
        $this->_achievementsList[$type] = $exponent;
    }

    /* Fetch the achievements for the current type */
    public function getAchievementsEarned($type, $points){
        if(in_array($type, array_keys($this->_achievementsList))){
            switch($type){
                case 'Exp Points':
                    $badge = "badge-info";
                break;
                case 'Topics':
                    $badge = "badge-warning";
                break;
                case 'Comments':
                    $badge = "badge-success";
                break;
                case 'Level':
                    $badge = "badge-important";
                break;
                default:
                    $badge = '';
                break;
            }
            $this->_currentType = $type;
            $exponent           = $this->_achievementsList[$type];
            for($i = $exponent; $i <= $points; $i++){
                if(($i % $exponent) == 0){
                    echo "<span class='badge $badge'>$type: $i</span>";
                }
            }
        }else{
            return false;
        }
    }

    /* Image from achivements */
    private function getImageFromAchievement($points){
        return $this->_imagePath.$this->_currentType."/".$points.".".$this->_imageExtension;
    }

    /* Set the current experience points */
    public function setExpPoints($points){
        $percent            = ($points / 100) * pow(2, 1/7) * 10;
        $points             = $points + ceil($percent * 10);
        $this->_expPoints   = (int)$points;

        $this->level();
    }

    /* Get the information you need*/
    public function get($type){
        switch($type){
            case "exp":
                return $this->_expPoints;
            break;
            case "level":
                /* Check for the RIGHT current level */
                if($this->_expPoints < $this->experience($this->_currLevel)){
                    return $this->_currLevel - 1;
                }else{
                    return $this->_currLevel;
                }
            break;
        }
    }

    /* Check experience points using a level */
    public function experience($L) {
        $a=0;
        for($x=1; $x < $L; $x++) {
            $a += floor($x + 300 * pow(2, ($x/7)));
        }
        return floor($a/4);
    }

    /* Set current_level using current experience points */
    private function level(){
        $q = pow(2, 1/7);
        $E = $this->_expPoints;
        $t = (($q - 1) / 75) * $E + 1;
        $tot = 7*log($t, 2);
        $this->_currLevel = round(floor($tot))+1;
    }

    /* Check how many exp points left untill next level */
    public function remaining(){
        $exp    = $this->_expPoints;
        $done   = $this->nextLevelExp() - $exp;

        return $done;
    }

    /* Get a percentage completed to next level */
    public function percentDone(){
        $exp            = $this->_expPoints;
        $expCurrLevel   = $this->experience($this->get("level"));
        $expDiff        = $this->nextLevelExp() - $expCurrLevel;
        $done           = $this->nextLevelExp() - $exp;
        $percent        = floor(($done / $expDiff) * 100);
        $percent        = 100 - $percent;

        return $percent;
    }

    /* Get the experience needed for the next level */
    public function nextLevelExp(){
        $level      = $this->get("level");
        $new_level  = $level + 1;
        return $this->experience($new_level);
    }

    /* Display images for each level E.g.: Level 5 = star star star star star */
    public function levelImages($image = NULL){
        $level = $this->get("level");
        $image = (!empty($image)) ? $image : "★";
        $i = 1;
        $stars = "";
        while($i <= $level){
            $stars .= $image;

            $i++;
        }
        return $stars;
    }
}