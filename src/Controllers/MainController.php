<?php

namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class MainController
{
    private $twig;

    public function __construct()
    {
        // 1. On indique à Twig où se trouvent nos fichiers .html.twig
        $loader = new FilesystemLoader(__DIR__ . '/../../views');
        
        // 2. On initialise l'environnement Twig
        $this->twig = new Environment($loader, [
            // 'cache' => __DIR__ . '/../../var/cache', // (Désactivé pour le moment pour voir les modifs en direct)
            'cache' => false,
        ]);
    }

    public function home()
    {
        // On demande à Twig de générer le fichier et on l'affiche avec echo
        echo $this->twig->render('home.html.twig');
    }

    public function connexion()
    {
        // echo $this->twig->render('connexion.html.twig');
    }
    
    // ... tes autres méthodes

    public function afficherStatus()
    {
        // 1. Tes identifiants de base de données (à modifier avec les tiens !)
        $host = '127.0.0.1';
        $dbname = 'web4allDB'; // Remplacer par le vrai nom
        $username = 'root';         // Ton utilisateur MySQL
        $password = 'A2#DevWeb!';             // Ton mot de passe (souvent vide sous XAMPP/WSL, ou 'root')

        try {
            // 2. Connexion avec PDO
            $pdo = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // 3. On prépare la requête pour aller chercher l'ID numéro 4
            // (Assure-toi que ta colonne clé primaire s'appelle bien 'id')
            $stmt = $pdo->prepare("SELECT * FROM APPLICATION_STATUS WHERE application_status_id = 4");
            $stmt->execute();
            
            // 4. On récupère la ligne sous forme de tableau associatif
            $donneesStatus = $stmt->fetch(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            die("Erreur de connexion à la BDD : " . $e->getMessage());
        }

        // 5. On envoie les données à la vue Twig
        echo $this->twig->render('status.html.twig', [
            'status' => $donneesStatus
        ]);
    }
}