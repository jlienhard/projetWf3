<?php

namespace App\Twig;

use App\Entity\Membre;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    public function autorisations(Membre $membre)
    {
        $autorisations = "";
        foreach ($membre->getRoles() as $role) {
            $autorisations .= $autorisations ? ", " : "";
            switch ($role) {
                case 'ROLE_ADMIN':
                    $autorisations .= "Gérant";
                    break;
                case 'ROLE_VENDEUR':
                    $autorisations .= "Vendeur";
                    break;
                case 'ROLE_USER':
                    $autorisations .= "Membre";
                    break;
            }
        }
        return $autorisations;
    }

    public function salutations(Membre $membre, $date = null)
    {
        $prenom = $membre->getPrenom();
        $nom = $membre->getNom();
        $mail = $membre->getEmail();

        // $date = new \DateTime();
        // $date = $date->format('d/m/Y');

        // Autre façon de noter :
        $date = $date ?? (new \DateTime())->format('d/m/Y');
        if ($prenom && $nom) {
            return "Bonjour $prenom $nom, nous sommes le $date";
        } else {
            return "Bonjour $mail, nous sommes le $date";
        }
    }
    /**
     * Les filtres que l'on veut ajouter doivent être renvoyés dans un array par la fonction getFilters
     * Chaque valeur de cet array est un objet de la class TwigFilter
     * Les arguments du constructeur de TwigFiler : 
     *      1er : le nom du filtre à utiliser dans les fichiers Twig
     *      2e  : la fonction qui est déclaré dans cette classe 
     *                  [$this, nom_de_la_fonction_dans_la_classe]
     * 
     */

    public function getFilters()
    {
        return [
            new TwigFilter("salutations", [$this, "salutations"]),
            new TwigFilter("autorisations", [$this, "autorisations"])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("autorisations", [$this, "autorisations"])
        ];
    }
}
