<?php
class UtilisateurDAO {

    // Retourne le type de l’utilisateur (benevole, salarie, rh)  ou null
    public static function getTypeUser(string $login): ?string {
        try {
            $user = self::getUser($login);
            return $user['typeUser'] ?? null;
        } catch (Exception $e) {
            error_log("Erreur getTypeUser : " . $e->getMessage());
            return null;
        }
    }

    // Liste les utilisateurs ayant au moins une demande enregistrée
    public static function getUtilisateursAvecDemande(): ?array {
        try {
            $sql = "SELECT idUser, nom, prenom
                    FROM utilisateur
                    WHERE idUser IN (
                        SELECT idUser
                        FROM demandeutilisateur)";
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getUtilisateursAvecDemande : " . $e->getMessage());
            return null;
        }
    }

    // Renvoie ['idUser' => ...] pour un login donné, ou null
    public static function getIdUtilisateur(string $login): ?array {
        try {
            $sql = "SELECT idUser FROM utilisateur WHERE login = :login";
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getIdUtilisateur : " . $e->getMessage());
            return null;
        }
    }

    // Renvoie les informations complètes de l’utilisateur
    public static function getUser(string $login): ?array {
        try {
            $sql = "SELECT idUser, nom, prenom, login, typeUser, idFonct, idLigue
                    FROM utilisateur
                    WHERE login = :login";
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getUser : " . $e->getMessage());
            return null;
        }
    }

    // Vérifie l’identifiant et le mot de passe
    public static function authentification(string $login, string $mdp): ?array {
        try {
            $sql = "SELECT login
                    FROM utilisateur
                    WHERE login = :login
                    AND mdp   = MD5(:mdp)";
            $stmt = DBConnex::getInstance()->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':mdp',   $mdp);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur authentification : " . $e->getMessage());
            return null;
        }
    }

}
?>
