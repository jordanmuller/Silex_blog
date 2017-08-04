<?php

namespace Entity;

class Article 
{
    private $id;
    private $title;
    private $header;
    private $content;
    private $category;
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getContent() {
        return $this->content;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setHeader($header) {
        $this->header = $header;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function getCategory() {
        return $this->category;
    }

    public function setCategory(Category $category) {
        $this->category = $category;
        return $this;
    }

    public function getCategoryId() 
    {
        // Si la propriété catégory qui contient un objet $categry de la classe Category n'est pas null
        if(!is_null($this->category))
        {
            // On récupère l'id grâce à la méthode getId() de la class Category
            return $this->category->getId();
        }
    }
    
    public function getCategoryName() 
    {
        // Si la propriété catégory qui contient un objet $categry de la classe Category n'est pas null
        if(!is_null($this->category))
        {
            // On récupère le name grâce à la méthode getName() de la class Category
            return $this->category->getName();
        }
        
        return '';
    }
}
