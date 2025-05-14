<?php
class liguesDAO{

    public static function getAllLigues(){

        $requetePrepa = DBConnex::getInstance()->prepare("select * from ligues");
        $requetePrepa->execute()
        
        $ligues = array()

        $index = 0;
        while($row = mysql_fetch_assoc($requetePrepa)){
            $ligues[$index] = $row;
            $index++;

        }
        return $ligues
    }

    public static function getLigueId()

}

?>