<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookMovement
 *
 * @ORM\Table(name="book_movement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookMovementRepository")
 */
class BookMovement
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
     * @ORM\Column(name="in_or_out", type="string", length=10)
     */
    private $inOrOut;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="on_date", type="date")
     */
    private $onDate;

   /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="bookMovements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="bookMovements")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="bookMovements")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $book;

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
     * Set inOrOut
     *
     * @param string $inOrOut
     *
     * @return BookMovement
     */
    public function setInOrOut($inOrOut)
    {
        $this->inOrOut = $inOrOut;

        return $this;
    }

    /**
     * Get inOrOut
     *
     * @return string
     */
    public function getInOrOut()
    {
        return $this->inOrOut;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return BookMovement
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\Student $owner
     *
     * @return BookMovement
     */
    public function setOwner(\AppBundle\Entity\Student $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\Student
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set book
     *
     * @param \AppBundle\Entity\Book $book
     *
     * @return BookMovement
     */
    public function setBook(\AppBundle\Entity\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \AppBundle\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set onDate
     *
     * @param \DateTime $onDate
     *
     * @return BookMovement
     */
    public function setOnDate($onDate)
    {
        $this->onDate = $onDate;

        return $this;
    }

    /**
     * Get onDate
     *
     * @return \DateTime
     */
    public function getOnDate()
    {
        return $this->onDate;
    }
}
