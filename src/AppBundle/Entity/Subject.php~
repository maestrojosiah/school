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
     * @ORM\Column(name="out_of", type="string", length=255)
     */
    private $outOf;

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
     * @ORM\OneToMany(targetEntity="ChildSubject", mappedBy="parent")
     */
    private $childSubjects;

     /**
     * Constructor
     */
    public function __construct()
    {
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
        $this->childSubjects = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return $this->subjectTitle;
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

    /**
     * Set outOf
     *
     * @param string $outOf
     *
     * @return Subject
     */
    public function setOutOf($outOf)
    {
        $this->outOf = $outOf;

        return $this;
    }

    /**
     * Get outOf
     *
     * @return string
     */
    public function getOutOf()
    {
        return $this->outOf;
    }

    /**
     * Set parent
     *
     * @param string $parent
     *
     * @return Subject
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set children
     *
     * @param string $children
     *
     * @return Subject
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return string
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add childSubject
     *
     * @param \AppBundle\Entity\ChildSubject $childSubject
     *
     * @return Subject
     */
    public function addChildSubject(\AppBundle\Entity\ChildSubject $childSubject)
    {
        $this->childSubjects[] = $childSubject;

        return $this;
    }

    /**
     * Remove childSubject
     *
     * @param \AppBundle\Entity\ChildSubject $childSubject
     */
    public function removeChildSubject(\AppBundle\Entity\ChildSubject $childSubject)
    {
        $this->childSubjects->removeElement($childSubject);
    }

    /**
     * Get childSubjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildSubjects()
    {
        return $this->childSubjects;
    }
}
