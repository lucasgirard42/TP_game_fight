<?php


class PersonnagesManager
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

//-------------------------------------------------------------------------------------//
    public function count()
    {
        return $this->pdo->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }

//------------------------------------------------------------------------------------------//
    
public function get($info)
    {
        if (is_int($info))
        {
          $infoPerso = $this->pdo->query('SELECT id, nom, degats FROM personnages WHERE id = '.$info);
          $donnees = $infoPerso->fetch(PDO::FETCH_ASSOC);
          
          return new Personnage($donnees);
        }
        else
        {
          $infoPerso = $this->pdo->prepare('SELECT id, nom, degats FROM personnages WHERE nom = :nom');
          $infoPerso->execute([':nom' => $info]);
        
          return new Personnage($infoPerso->fetch(PDO::FETCH_ASSOC));
        }
    }


 //---------------------------------------------------------------------------------------------//
   

    
    public function getListPerso($nom)

    {
        $persos = [];
    
        $listPerso = $this->pdo->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
        $listPerso->execute([':nom' => $nom]);
    
        while ($donnees = $listPerso->fetch(PDO::FETCH_ASSOC))
        {
        $persos[] = new Personnage($donnees);
        }
    
        return $persos;
    }
    


//--------------------------------------------------------------------------------------------//
    


    public function create(Personnage $perso)
    {
        $newPerso = $this->pdo->prepare('INSERT INTO personnages(nom) VALUES(:nom)');
        $newPerso->bindValue(':nom', $perso->nom());
        $newPerso->execute();
    
        $perso->hydrate([
        'id' => $this->pdo->lastInsertId(),
        'degats' => 0,
        ]);
    }


//----------------------------------------------------------------------------------------------//
    
    public function update(Personnage $perso)
    {
        $updatePerso = $this->pdo->prepare('UPDATE personnages SET degats = :degats WHERE id = :id');
    
        $updatePerso->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
        $updatePerso->bindValue(':id', $perso->id(), PDO::PARAM_INT);
    
        $updatePerso->execute();
    }



//----------------------------------------------------------------------------------------------//
    public function delete(Personnage $perso)
    {
        $this->pdo->exec('DELETE FROM personnages WHERE id = '.$perso->id());
    }


//----------------------------------------------------------------------------------------------//
    


    public function exists($info)
  {
    if (is_int($info)) 
    {
      return (bool) $this->pdo->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    }
    
    
    $exist = $this->pdo->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $exist->execute([':nom' => $info]);
    
    return (bool) $exist->fetchColumn();
  }


  //----------------------------------------------------------------------------------------------//
    
  
  
    public function setPdo(PDO $pdo)
  {
    $this->pdo = $pdo;
  }
}



