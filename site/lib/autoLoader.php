<?php
spl_autoload_register('Autoloader::autoloadDto');  // Autoload pour les objets de transfert de données (DTO)
spl_autoload_register('Autoloader::autoloadDao');  // Autoload pour les objets d'accès aux données (DAO)
spl_autoload_register('Autoloader::autoloadLib');  // Autoload pour les classes utilitaires de la bibliothèque

// Classe gérant le chargement automatique des classes
class Autoloader{

    // Fonction de chargement automatique pour les classes DTO
    static function autoloadDto($class){
        $file = 'modeles/dto/' . lcfirst($class) . '.php'; // Construit le chemin vers le fichier DTO correspondant
        if(is_file($file) && is_readable($file)){          // Vérifie si le fichier existe et est lisible
            require $file;                                 // Inclut le fichier une seule fois
        }
    }

    // Fonction de chargement automatique pour les classes de la lib
    static function autoloadLib($class){
        $file = 'lib/' . lcfirst($class) . '.php';         // Construit le chemin vers le fichier lib correspondant
        if(is_file($file) && is_readable($file)){          // Vérifie si le fichier existe et est lisible
            require $file;                                 // Inclut le fichier une seule fois
        }
    }

    // Fonction de chargement automatique pour les classes DAO
    static function autoloadDao($class){
        $file = 'modeles/dao/' . lcfirst($class) . '.php'; // Construit le chemin vers le fichier DAO correspondant
        if(is_file($file) && is_readable($file)){          // Vérifie si le fichier existe et est lisible
            require $file;                                 // Inclut le fichier une seule fois
        }
    }

}
