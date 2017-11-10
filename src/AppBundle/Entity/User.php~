<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="f_name", type="string", length=255)
     */
    private $fName;

    /**
     * @var string
     *
     * @ORM\Column(name="l_name", type="string", length=255)
     */
    private $lName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=255)
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="Config", mappedBy="user")
     */
    private $configs;

    /**
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="user")
     */
    private $subjects;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="user")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity="Classs", mappedBy="user")
     */
    private $classses;

    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="user")
     */
    private $exams;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="user")
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="ChildSubject", mappedBy="user")
     */
    private $childSubjects;

    /**
     * @ORM\OneToMany(targetEntity="BookMovement", mappedBy="user")
     */
    private $bookMovements;

    public function __construct()
    {
        $this->active = true;
        $this->configs = new ArrayCollection();    
        $this->subjects = new ArrayCollection();    
        $this->students = new ArrayCollection();    
        $this->classses = new ArrayCollection();
        $this->exams = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->childSubjects = new ArrayCollection();
        $this->bookMovements = new ArrayCollection();
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
     * Set fName
     *
     * @param string $fName
     *
     * @return User
     */
    public function setFName($fName)
    {
        $this->fName = $fName;

        return $this;
    }

    /**
     * Get fName
     *
     * @return string
     */
    public function getFName()
    {
        return $this->fName;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->active
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->active
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    /**
     * Set lName
     *
     * @param string $lName
     *
     * @return User
     */
    public function setLName($lName)
    {
        $this->lName = $lName;

        return $this;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Get lName
     *
     * @return string
     */
    public function getLName()
    {
        return $this->lName;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set active
     *
     * @param string $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }    

    /**
     * Add config
     *
     * @param \AppBundle\Entity\Config $config
     *
     * @return User
     */
    public function addConfig(\AppBundle\Entity\Config $config)
    {
        $this->configs[] = $config;

        return $this;
    }

    /**
     * Remove config
     *
     * @param \AppBundle\Entity\Config $config
     */
    public function removeConfig(\AppBundle\Entity\Config $config)
    {
        $this->configs->removeElement($config);
    }

    /**
     * Get configs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * Add subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return User
     */
    public function addSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param \AppBundle\Entity\Subject $subject
     */
    public function removeSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Add student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return User
     */
    public function addStudent(\AppBundle\Entity\Student $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Student $student
     */
    public function removeStudent(\AppBundle\Entity\Student $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add classs
     *
     * @param \AppBundle\Entity\Classs $classs
     *
     * @return User
     */
    public function addClasss(\AppBundle\Entity\Classs $classs)
    {
        $this->classses[] = $classs;

        return $this;
    }

    /**
     * Remove classs
     *
     * @param \AppBundle\Entity\Classs $classs
     */
    public function removeClasss(\AppBundle\Entity\Classs $classs)
    {
        $this->classses->removeElement($classs);
    }

    /**
     * Get classses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClassses()
    {
        return $this->classses;
    }

    /**
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return User
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
     * Add childSubject
     *
     * @param \AppBundle\Entity\ChildSubject $childSubject
     *
     * @return User
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

    /**
     * Add bookMovement
     *
     * @param \AppBundle\Entity\BookMovement $bookMovement
     *
     * @return User
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

    /**
     * Add document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return User
     */
    public function addDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \AppBundle\Entity\Document $document
     */
    public function removeDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
