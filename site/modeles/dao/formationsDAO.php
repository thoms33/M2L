<?php
class formationsDAO {

    // Récupère les formations ouvertes aux inscriptions à la date du jour
    public static function recupererFormationsOuvertes(): ?array {
        try {
            $sql = 'SELECT *
                    FROM formation
                    WHERE dateOuvertInscriptions <= :date
                    AND dateClotureInscriptions >= :date';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $today = date('Y-m-d');
            $stmt->bindParam(':date', $today);                // lie la date du jour
            $stmt->execute();                                 // exécute la requête
            return $stmt->fetchAll(PDO::FETCH_ASSOC);         // renvoie toutes les formations
        } catch (Exception $e) {
            error_log("Erreur recupererFormationsOuvertes : " . $e->getMessage());
            return null;                                      // retourne null en cas d’erreur
        }
    }

    // Récupère une formation par son identifiant
    public static function getFormationById(int $id): ?array {
        try {
            $sql = 'SELECT *
                    FROM formation
                    WHERE idFormation = :id';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);     // lie l’ID
            $stmt->execute();                                 // exécute la requête
            return $stmt->fetch(PDO::FETCH_ASSOC);            // renvoie la formation
        } catch (Exception $e) {
            error_log("Erreur getFormationById($id) : " . $e->getMessage());
            return null;                                      // retourne null en cas d’erreur
        }
    }

    // Récupère toutes les formations (sans filtre)
    public static function getFormations(): ?array {
        try {
            $sql = 'SELECT * FROM formation';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->execute();                                 // exécute la requête
            return $stmt->fetchAll(PDO::FETCH_ASSOC);         // renvoie la liste
        } catch (Exception $e) {
            error_log("Erreur getFormations : " . $e->getMessage());
            return null;                                      // retourne null en cas d’erreur
        }
    }

    // Ajoute une nouvelle formation en base
    public static function addFormation(string $intitule,string $description,int    $duree,string $dateOuvertInscriptions,string $dateClotureInscriptions,int    $capaciteMax): bool {
        try {
            $sql = 'INSERT INTO formation(intitule, description, duree,dateOuvertInscriptions, dateClotureInscriptions, capaciteMax)
                    VALUES (:intitule, :description, :duree,:dateOuvertInscriptions, :dateClotureInscriptions, :capaciteMax)';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':intitule',$intitule);
            $stmt->bindParam(':description',$description);
            $stmt->bindParam(':duree',$duree, PDO::PARAM_INT);
            $stmt->bindParam(':dateOuvertInscriptions',$dateOuvertInscriptions);
            $stmt->bindParam(':dateClotureInscriptions',$dateClotureInscriptions);
            $stmt->bindParam(':capaciteMax',$capaciteMax, PDO::PARAM_INT);
            return $stmt->execute();                      // retourne true si succès
        } catch (Exception $e) {
            error_log("Erreur addFormation : " . $e->getMessage());
            return false;                                 // retourne false en cas d’erreur
        }
    }

    // Supprime une formation et toutes les demandes associées
    public static function deleteFormation(int $id): bool {
        try {
            // supprime d’abord les demandes liées
            $sql1 = 'DELETE FROM demandeutilisateur
                     WHERE idFormation = :id';
            $stmt1 = DBConnex::getInstance()->prepare($sql1);
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            // puis supprime la formation elle-même
            $sql2 = ' DELETE FROM formation
                      WHERE idFormation = :id';
            $stmt2 = DBConnex::getInstance()->prepare($sql2);
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt2->execute();                     // retourne true si suppression OK
        } catch (Exception $e) {
            error_log("Erreur deleteFormation($id) : " . $e->getMessage());
            return false;                                 // retourne false en cas d’erreur
        }
    }

    // Récupère l’ID d’une formation à partir de son intitulé
    public static function getIdFormationByIntitule(string $intitule): ?int {
        try {
            $sql = 'SELECT idFormation
                    FROM formation
                    WHERE intitule = :intitule';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':intitule', $intitule);
            $stmt->execute();                               // exécute la requête
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['idFormation'] : null;   // retourne l’ID ou null
        } catch (Exception $e) {
            error_log("Erreur getIdFormationByIntitule($intitule) : " . $e->getMessage());
            return null;                                     // retourne null en cas d’erreur
        }
    }

    // Met à jour une formation existante
    public static function updateFormation(int $id,string $intitule,int $duree,string $dateOuvertInscriptions,string $dateClotureInscriptions,int $capaciteMax
    ): bool {
        try {
            $sql = '
                UPDATE formation
                   SET intitule               = :intitule,
                       duree                  = :duree,
                       dateOuvertInscriptions = :dateOuvertInscriptions,
                       dateClotureInscriptions= :dateClotureInscriptions,
                       capaciteMax            = :capaciteMax
                 WHERE idFormation            = :id
            ';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':id',$id, PDO::PARAM_INT);
            $stmt->bindParam(':intitule',$intitule);
            $stmt->bindParam(':duree',$duree, PDO::PARAM_INT);
            $stmt->bindParam(':dateOuvertInscriptions',$dateOuvertInscriptions);
            $stmt->bindParam(':dateClotureInscriptions',$dateClotureInscriptions);
            $stmt->bindParam(':capaciteMax',$capaciteMax, PDO::PARAM_INT);
            return $stmt->execute();                      // retourne true si modif OK
        } catch (Exception $e) {
            error_log("Erreur updateFormation($id) : " . $e->getMessage());
            return false;                                 // retourne false en cas d’erreur
        }
    }

}
?>
