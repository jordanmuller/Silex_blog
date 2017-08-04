<?php

namespace Repository;

use Entity\Article;
use Entity\Category;

/**
 * Description of ArticleRepository
 *
 * @author Hello
 */
class ArticleRepository extends RepositoryAbstract 
{
    protected function getTable() 
    {
        return 'article';
    }
    
    private function buildEntity(array $data)
    {
        $category = new Category();
        
        $category 
            ->setId($data['category_id'])
            ->setName($data['name'])
        ;
        // On crée un nouvel objet à partir de la calsse Catégory présente dans Entity
        $article = new Article();

        // On attribue des valeurs à l'objet en récupérant les données en BDD suite à l'instanciation de la classe category ci-dessus
        $article
            ->setId($data['id'])
            ->setTitle($data['title'])
            ->setHeader($data['header'])
            ->setContent($data['content'])
            ->setCategory($category)
        ;

        // Après on renvoie l'objet $category et ses valeurs
        return $article;
    }
    
    public function findAll() 
    {
        $query = 'SELECT a.*, c.name FROM article a '
                . 'JOIN category c On a.category_id = c.id'
        ;
                
        $dbArticles = $this->db->fetchAll($query);
        
        $articles = [];
        
        foreach($dbArticles AS $dbArticle)
        {
            $article = $this->buildEntity($dbArticle);
            
            $articles[] = $article;
        }
        return $articles;
    }
    
    public function find($id) 
    {
        $dbArticle = $this->db->fetchAssoc(
            "SELECT a.*, c.name FROM article a"
                . " JOIN category c ON a.category_id = c.id"
                . " WHERE a.id = :id",
            [
                ':id' => $id
            ]
        );
        
        if(!empty($dbArticle))
        {
            return $this->buildEntity($dbArticle);
        }
    }
    
    public function delete(Article $article) 
    {
        // Méthode delete de Doctrine, 1er argument le nom de la table, deuxieme argument le champ correspondant pour entrainer la suppression
        $this->db->delete(
            'article',
            ['id' => $article->getId()]
        );
    }
    
    public function save(Article $article) 
    {
        // $ data, les données à enregister en BDD récupérer depuis les getters de l'objet Article
        $data = [
                    'title' => $article->getTitle(), 
                    'header' => $article->getHeader(),
                    'content' => $article->getContent(),
                    'category_id' => $article->getCategoryId()
                ];
        
        // Si article a un id, on est en update
        $where = !empty($article->getId())
                 ? ['id' => $article->getId()]
                 // Sinon on insert
                 : null
            ;
        
        // Appel à la méthode de RepositoryAbstract qui EXECUTERA L'INSERTION OU LA MODIFICATION
        $this->persist($data, $where);
        
        if(empty($article->getId())) 
        {
            // On set l'id récupéré dans la BDD à l'objet article dont on a récupérer le title, le header et le content
            $article->setId($this->db->lastInsertId());
        }
    }
}
