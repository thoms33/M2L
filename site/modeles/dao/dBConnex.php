<?php
// Classe unique de connexion à la base de données, étend PDO
class DBConnex extends PDO {

    // Instance unique de la connexion
    private static $instance;

    // Retourne l’instance unique de DBConnex (pattern Singleton)
    public static function getInstance() {
        // Si pas encore créée, on l'instancie
        if (!self::$instance) {
            self::$instance = new DBConnex();
        }
        return self::$instance;
    }

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct() {
        try {
            // Appelle le constructeur PDO avec les paramètres de connexion
            parent::__construct(Param::$dsn, Param::$user, Param::$pass);
        } catch (Exception $e) {
            // En cas d'erreur, affiche le message et termine l'exécution
            echo $e->getMessage();
            die("Impossible de se connecter.");
        }
    }

}
