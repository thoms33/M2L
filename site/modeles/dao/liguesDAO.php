<?php
class liguesDAO {

    // Récupère toutes les ligues de la base
    public static function getAllLigues() {
        try {
            $stmt = DBConnex::getInstance()->prepare("SELECT * FROM ligue");
            $stmt->execute();                       // exécute la requête
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // renvoie toutes les ligues
        } catch (PDOException $e) {
            return $e->getMessage();                // retourne l’erreur en cas de problème
        }
    }

    // Récupère les clubs d’une ligue donnée
    public static function getClubsAssoc($idLigue) {
        $stmt = DBConnex::getInstance()->prepare(
            'SELECT idClub, nomClub
             FROM club
             WHERE idLigue = :idLigue'
        );
        $stmt->bindParam(':idLigue', $idLigue, PDO::PARAM_INT);  // lie l’ID de la ligue
        $stmt->execute();                                         // exécute la requête
        return $stmt->fetchAll(PDO::FETCH_ASSOC);                // renvoie les clubs
    }

    // Récupère une ligue par son identifiant
    public static function getById($id) {
        $stmt = DBConnex::getInstance()->prepare(
            'SELECT *
             FROM ligue
             WHERE idLigue = :idLigue'
        );
        $stmt->bindParam(':idLigue', $id, PDO::PARAM_INT);      // lie l’ID
        $stmt->execute();                                       // exécute la requête
        return $stmt->fetch(PDO::FETCH_ASSOC);                  // renvoie la ligue
    }

    // Récupère une ligue par son nom
    public static function getByName($name) {
        $stmt = DBConnex::getInstance()->prepare(
            'SELECT *
             FROM ligue
             WHERE nomLigue = :nomLigue'
        );
        $stmt->bindParam(':nomLigue', $name, PDO::PARAM_STR);    // lie le nom
        $stmt->execute();                                        // exécute la requête
        return $stmt->fetch(PDO::FETCH_ASSOC);                   // renvoie la ligue
    }

}
?>
