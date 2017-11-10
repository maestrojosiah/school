<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
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
     * @ORM\Column(name="book_title", type="string", length=255)
     */
    private $bookTitle;

    /**
     * @ORM\OneToMany(targetEntity="BookMovement", mappedBy="book")
     */
    private $bookMovements;


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
     * Set bookTitle
     *
     * @param string $bookTitle
     *
     * @return Book
     */
    public function setBookTitle($bookTitle)
    {
        $this->bookTitle = $bookTitle;

        return $this;
    }

    /**
     * Get bookTitle
     *
     * @return string
     */
    public function getBookTitle()
    {
        return $this->bookTitle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bookMovements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bookMovement
     *
     * @param \AppBundle\Entity\BookMovement $bookMovement
     *
     * @return Book
     */
    public function addBookMovement(\AppBundle\Entity\BookMovement $bookMovement)
    {
        $this->bookMovements[] = $bookMovement;

        return $this;
    }

    /**
     * Remove bookMovement
     *
     * @param \AppBundle\Entity\BookMovement $bookMovement
     */
    public function removeBookMovement(\AppBundle\Entity\BookMovement $bookMovement)
    {
        $this->bookMovements->removeElement($bookMovement);
    }

    /**
     * Get bookMovements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookMovements()
    {
        return $this->bookMovements;
    }
}
