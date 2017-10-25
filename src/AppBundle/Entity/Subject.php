<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubjectRepository")
 */
class Subject
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
     * @ORM\Column(name="subject_title", type="string", length=255)
     */
    private $subjectTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="subject_code", type="string", length=255)
     */
    private $subjectCode;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="subject")
     */
    private $exams;

     /**
     * Constructor
     */
    public function __construct()
    {
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set subjectTitle
     *
     * @param string $subjectTitle
     *
     * @return Subject
     */
    public function setSubjectTitle($subjectTitle)
    {
        $this->subjectTitle = $subjectTitle;

        return $this;
    }

    /**
     * Get subjectTitle
     *
     * @return string
     */
    public function getSubjectTitle()
    {
        return $this->subjectTitle;
    }

    /**
     * Set subjectCode
     *
     * @param string $subjectCode
     *
     * @return Subject
     */
    public function setSubjectCode($subjectCode)
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    /**
     * Get subjectCode
     *
     * @return string
     */
    public function getSubjectCode()
    {
        return $this->subjectCode;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Subject
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
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return Subject
     */
    public function addExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exams[] = $exam;

        return $this;
    }

    /**
     * Remove exam
     *
     * @param \AppBundle\Entity\Exam $exam
     */
    public function removeExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exams->removeElement($exam);
    }

    /**
     * Get exams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExams()
    {
        return $this->exams;
    }
}
