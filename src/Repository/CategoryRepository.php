<?php

namespace Repository;

use Entity\Category;

class CategoryRepository extends RepositoryAbstract 
{
    protected function getTable() 
    {
        return 'category';
    }

    
    public function findAll() 
    {
        $dbCategories = $this->db->fetchAll('SELECT * FROM category');
        
        // On déclare un tableau vide avant le foreach
        $categories = [];
        foreach($dbCategories AS $dbCategory)
        {
            /*
            // A chaque tour de boucle on crée un nouvel objet à partir de la calsse Catégory présente dans Entity
            $category = new Category();
            
            // On attribue des valeurs à l'objet en récupérant les données en BDD par $dbCategory['indice']
            $category
                    ->setId($dbCategory['id'])
                    ->setName($dbCategory['name'])
            ;
            
            */
            // On factorise les lignes de code ci-dessus grâce à la méthode buildEntity();
            $category = $this->buildEntity($dbCategory);
            
            // On rajoute chaque nouvel objet dans le tableau $categories
            $categories[] = $category;
        }
        // Après la fin du foreach, on renvoie le tableau $categories et ses valeurs 
        return $categories;
    }
    
    public function find($id) 
    {
        $dbCategory = $this->db->fetchAssoc(
            "SELECT * FROM category WHERE id = :id",
            [
                ':id' => $id
            ]
        );
        if(!empty($dbCategory)) 
        { 
            // On construit un objet Category grâce à la méthode buildEntity sur les valeurs récupérés en BDD contenues dans la variable $dbCategory
            return $this->buildEntity($dbCategory);
        }
    }
    
    public function delete(Category $category) 
    {
        // Méthode delete de Doctrine, 1er argument le nom de la table, deuxieme argument le champ correspondant pour entrainer la suppression
        $this->db->delete(
            'category',
            ['id' => $category->getId()]
        );
    }
    
    
    /**
     * Crée un objet Entity\Category
     * à partir d'un tableau de données venant de la BDD
     * @param array $data
     * @return Category
     */
    private function buildEntity(array $data)
    {
        // On crée un nouvel objet à partir de la calsse Catégory présente dans Entity
        $category = new Category();

        // On attribue des valeurs à l'objet en récupérant les données en BDD suite à l'instanciation de la classe category ci-dessus
        $category
                ->setId($data['id'])
                ->setName($data['name'])
        ;

        // Après on renvoie l'objet $category et ses valeurs
        return $category;
    }
    
    public function save(Category $category) 
    {
        // $ data, les données à enregister en BDD récupérer depuis les getters de l'objet Category
        $data = ['name' => $category->getName()];
        
        // Si category a un id, on est en update
        $where = !empty($category->getId())
                 ? ['id' => $category->getId()]
                 // Sinon on insert
                 : null
            ;
        
        // Appel à la méthode de RepositoryAbstract qui EXECUTERA L'INSERTION OU LA MODIFICATION
        $this->persist($data, $where);
        
        if(empty($category->getId())) 
        {
            // On set l'id récupéré dans la BDD à l'objet category dont on a récupérer le name
            $category->setId($this->db->lastInsertId());
        }
    }
    
    
    
    
}
