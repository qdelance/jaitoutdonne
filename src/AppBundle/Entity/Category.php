<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Anecdote", mappedBy="category")
     */
    private $anecdotes;

    public function __construct()
    {
        $this->anecdotes = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add anecdote
     *
     * @param \AppBundle\Entity\Anecdote $anecdote
     *
     * @return Category
     */
    public function addAnecdote(\AppBundle\Entity\Anecdote $anecdote)
    {
        $this->anecdotes[] = $anecdote;

        return $this;
    }

    /**
     * Remove anecdote
     *
     * @param \AppBundle\Entity\Anecdote $anecdote
     */
    public function removeAnecdote(\AppBundle\Entity\Anecdote $anecdote)
    {
        $this->anecdotes->removeElement($anecdote);
    }

    /**
     * Get anecdotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnecdotes()
    {
        return $this->anecdotes;
    }
}
