<?php
    class utilisateurDAO{

        public static function getTypeUser($login){
            $result = getUser($login);

            return $result["typeUser"];
        }

        public static function getUser($login){
            $requetePrepa = DBConnex::getInstance()->prepare("select * from utilisateurs where login = :login");

            $requetePrepa->bindParams(":login", $login);
            $requetePrepa->execute();

            return $requetePrepa->fetch(PD0::FETCH_ASSOC);

        }

        public static function verifyUser($login, $mdp){
            $requetePrepa = DBConnex::getInstance()->prepare("select * from utilisateurs where login = :login and mdp = md5(:mdp)");

            $requetePrepa->bindParams(":login", $login);
            $requetePrepa->bindParams(":mdp", $mdp);

            $requetePrepa->execute();

            return true;
        }

    }
?>