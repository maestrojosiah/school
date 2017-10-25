<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExamRepository")
 */
class Exam
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
     * @var int
     *
     * @ORM\Column(name="marks", type="integer")
     */
    private $marks;

    /**
     * @var int
     *
     * @ORM\Column(name="term", type="integer")
     */
    private $term;

    /**
     * @ORM\ManyToOne(targetEntity="Classs", inversedBy="exams")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $classs;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="exams")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="ExamCompany", inversedBy="exams")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $examCompany;

    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="exams")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="exams")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    public function __toString() {
        return $this->examCompany->getId().$this->term;
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
     * Set marks
     *
     * @param integer $marks
     *
     * @return Exam
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Get marks
     *
     * @return int
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set term
     *
     * @param integer $term
     *
     * @return Exam
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return int
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set classs
     *
     * @param \AppBundle\Entity\Classs $classs
     *
     * @return Exam
     */
    public function setClasss(\AppBundle\Entity\Classs $classs = null)
    {
        $this->classs = $classs;

        return $this;
    }

    /**
     * Get classs
     *
     * @return \AppBundle\Entity\Classs
     */
    public function getClasss()
    {
        return $this->classs;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Exam
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return Exam
     */
    public function setSubject(\AppBundle\Entity\Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \AppBundle\Entity\Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Exam
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
     * Set examCompany
     *
     * @param \AppBundle\Entity\examCompany $examCompany
     *
     * @return Exam
     */
    public function setExamCompany(\AppBundle\Entity\examCompany $examCompany = null)
    {
        $this->examCompany = $examCompany;

        return $this;
    }

    /**
     * Get examCompany
     *
     * @return \AppBundle\Entity\examCompany
     */
    public function getExamCompany()
    {
        return $this->examCompany;
    }
}
