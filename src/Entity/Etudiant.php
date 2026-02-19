<?php
declare(strict_types=1);

namespace App\Entity;

class Etudiant
{
    private $id;        // int|null (auto)
    private $cne;       // string unique
    private $nom;       // string
    private $prenom;    // string
    private $email;     // string unique
    private $filiereId; // int FK -> filiere.id

    public function __construct($id,  $cne,  $nom,  $prenom,  $email,  $filiereId)
    {
        $this->id = $id;
        $this->setCne($cne);
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setEmail($email);
        $this->setFiliereId($filiereId);
    }

    public function getId() { return $this->id; }
    public function setId($id){ $this->id = $id; }

    public function getCne(){ return $this->cne; }
    public function setCne(string $cne)
    {
        $cne = trim($cne);
        if ($cne === '') { throw new \InvalidArgumentException('cne requis'); }
        $this->cne = $cne;
    }

    public function getNom(){ return $this->nom; }
    public function setNom(string $nom)
    {
        $nom = trim($nom);
        if ($nom === '') { throw new \InvalidArgumentException('nom requis'); }
        $this->nom = $nom;
    }

    public function getPrenom(){ return $this->prenom; }
    public function setPrenom(string $prenom)
    {
        $prenom = trim($prenom);
        if ($prenom === '') { throw new \InvalidArgumentException('prenom requis'); }
        $this->prenom = $prenom;
    }

    public function getEmail() { return $this->email; }
    public function setEmail(string $email)
    {
        $email = trim($email);
        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException('email invalide');
        }
        $this->email = $email;
    }

    public function getFiliereId(){ return $this->filiereId; }
    public function setFiliereId(int $filiereId)
    {
        if ($filiereId <= 0) { throw new \InvalidArgumentException('filiere_id > 0 requis'); }
        $this->filiereId = $filiereId;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

