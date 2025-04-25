<?php

class Post
{
    private $id;
    private $title;
    private $content;
    private $author;
    private $createdAt;

    public function __construct($title, $content, $author, $id = null, $createdAt = null)
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setAuthor($author);
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    // Getter Methods
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getContent() { return $this->content; }
    public function getAuthor() { return $this->author; }
    public function getCreatedAt() { return $this->createdAt; }

    public function setTitle($title)
    {
        if (!empty($title)) {
            $this->title = $title;
        }
    }

    public function setContent($content)
    {
        if (!empty($content)) {
            $this->content = $content;
        }
    }

    public function setAuthor($author)
    {
        if (!empty($author)) {
            $this->author = $author;
        }
    }

    // Validations (optionnelles, si nécessaire)
    public function validate()
    {
        $errors = [];
        
        if (empty($this->title)) {
            $errors[] = "Le titre ne peut pas être vide.";
        }

        if (empty($this->content)) {
            $errors[] = "Le contenu ne peut pas être vide.";
        }

        return $errors;
    }
}
