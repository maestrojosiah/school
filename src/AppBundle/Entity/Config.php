<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConfigRepository")
 */
class Config
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
     * @ORM\Column(name="school_name", type="string", length=255)
     */
    private $schoolName;

    /**
     * @var string
     *
     * @ORM\Column(name="school_address", type="string", length=255)
     */
    private $schoolAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="document_header", type="string", length=255)
     */
    private $documentHeader;

    /**
     * @var string
     *
     * @ORM\Column(name="document_footer", type="string", length=255)
     */
    private $documentFooter;

    /**
     * @var string
     *
     * @ORM\Column(name="results_per_page", type="string", length=255)
     */
    private $resultsPerPage;

    /**
     * @var string
     *
     * @ORM\Column(name="school_telephone", type="string", length=255)
     */
    private $schoolTelephone;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="configs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

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
     * Set schoolName
     *
     * @param string $schoolName
     *
     * @return Config
     */
    public function setSchoolName($schoolName)
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    /**
     * Get schoolName
     *
     * @return string
     */
    public function getSchoolName()
    {
        return $this->schoolName;
    }

    /**
     * Set schoolAddress
     *
     * @param string $schoolAddress
     *
     * @return Config
     */
    public function setSchoolAddress($schoolAddress)
    {
        $this->schoolAddress = $schoolAddress;

        return $this;
    }

    /**
     * Get schoolAddress
     *
     * @return string
     */
    public function getSchoolAddress()
    {
        return $this->schoolAddress;
    }

    /**
     * Set documentHeader
     *
     * @param string $documentHeader
     *
     * @return Config
     */
    public function setDocumentHeader($documentHeader)
    {
        $this->documentHeader = $documentHeader;

        return $this;
    }

    /**
     * Get documentHeader
     *
     * @return string
     */
    public function getDocumentHeader()
    {
        return $this->documentHeader;
    }

    /**
     * Set documentFooter
     *
     * @param string $documentFooter
     *
     * @return Config
     */
    public function setDocumentFooter($documentFooter)
    {
        $this->documentFooter = $documentFooter;

        return $this;
    }

    /**
     * Get documentFooter
     *
     * @return string
     */
    public function getDocumentFooter()
    {
        return $this->documentFooter;
    }

    /**
     * Set resultsPerPage
     *
     * @param string $resultsPerPage
     *
     * @return Config
     */
    public function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }

    /**
     * Get resultsPerPage
     *
     * @return string
     */
    public function getResultsPerPage()
    {
        return $this->resultsPerPage;
    }

    /**
     * Set schoolTelephone
     *
     * @param string $schoolTelephone
     *
     * @return Config
     */
    public function setSchoolTelephone($schoolTelephone)
    {
        $this->schoolTelephone = $schoolTelephone;

        return $this;
    }

    /**
     * Get schoolTelephone
     *
     * @return string
     */
    public function getSchoolTelephone()
    {
        return $this->schoolTelephone;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Config
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
}
