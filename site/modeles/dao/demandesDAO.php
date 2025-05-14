<?php
error_log("Chargement de demandesDAO depuis modeles/dao");

class demandesDAO {

    //  Récupère toutes les demandes :
    //  - si intervenant (bénévole/salarié), seulement ses propres demandes
    //  - sinon (RH), toutes les demandes
    public static function getDemandes($login) {
        $isIntervenant = in_array($_SESSION['typeUser'], ['salarie','benevole']);
        try {
            if ($isIntervenant) {
                // obtient l’ID utilisateur à partir du login
                $u = utilisateurDAO::getIdUtilisateur($login);
                $sql = 'SELECT * FROM demandeutilisateur WHERE idUser = :idUser';
                $stmt = DBConnex::getInstance()->prepare($sql);
                $stmt->bindParam(':idUser', $u['idUser'], PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // RH voit tout
                $sql = 'SELECT * FROM demandeutilisateur';
                $stmt = DBConnex::getInstance()->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            error_log("Erreur getDemandes : " . $e->getMessage());
            return null;
        }
    }

    //  Récupère toutes les demandes pour un utilisateur donné (RH)
    public static function getDemandesUser(int $idUser) {
        try {
            $sql = 'SELECT * FROM demandeutilisateur WHERE idUser = :idUser';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getDemandesUser : " . $e->getMessage());
            return null;
        }
    }

    //  Récupère une demande précise pour un couple (formation, utilisateur)
    public static function getDemandesByUserAndForm(int $idFormation, int $idUser) {
        try {
            $sql  = 'SELECT * 
                       FROM demandeutilisateur 
                      WHERE idFormation = :idFormation 
                        AND idUser      = :idUser';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':idFormation', $idFormation, PDO::PARAM_INT);
            $stmt->bindParam(':idUser',      $idUser,      PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getDemandesByUserAndForm : " . $e->getMessage());
            return null;
        }
    }

    //  Change le statut d’une demande (acceptée ou refusée)
    public static function modifierStatutDemande(string $etat, int $idFormation, int $idUser): bool {
        try {
            $sql  = 'UPDATE demandeutilisateur 
                        SET etat = :etat 
                      WHERE idFormation = :idFormation 
                        AND idUser      = :idUser';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':etat',        $etat,        PDO::PARAM_STR);
            $stmt->bindParam(':idFormation', $idFormation, PDO::PARAM_INT);
            $stmt->bindParam(':idUser',      $idUser,      PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Erreur modifierStatutDemande : " . $e->getMessage());
            return false;
        }
    }

    //  Crée une nouvelle demande au statut “en attente”
    public static function btnNouvelleDemande(int $idFormation, int $idUser): bool {
        try {
            $sql  = 'INSERT INTO demandeutilisateur (idFormation, idUser, etat)
                     VALUES (:idFormation, :idUser, "en attente")';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':idFormation', $idFormation, PDO::PARAM_INT);
            $stmt->bindParam(':idUser',      $idUser,      PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Erreur btnNouvelleDemande : " . $e->getMessage());
            return false;
        }
    }

    //  Supprime une demande (intervenant)
    public static function supprimerDemande(int $idFormation, int $idUser): bool {
        try {
            $sql  = 'DELETE 
                       FROM demandeutilisateur 
                      WHERE idFormation = :f 
                        AND idUser      = :u';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':f', $idFormation, PDO::PARAM_INT);
            $stmt->bindParam(':u', $idUser,      PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur supprimerDemande : " . $e->getMessage());
            return false;
        }
    }

    //  Compte le nombre de demandes acceptées pour contrôler la capacité max
    public static function countAcceptedForFormation(int $idFormation): int {
        try {
            $sql  = 'SELECT COUNT(*) AS cnt
                       FROM demandeutilisateur
                      WHERE idFormation = :f
                        AND etat = "validée"';
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':f', $idFormation, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $row['cnt'];
        } catch (Exception $e) {
            error_log("Erreur countAcceptedForFormation : " . $e->getMessage());
            return 0;
        }
    }

}
?>
