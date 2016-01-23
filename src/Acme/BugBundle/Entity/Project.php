<?php
namespace Acme\BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="project")
 **/
class Project implements GenerateCodeInterface
{
    const STATUS_DISABLED = 0;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $label;

    /**
     * @ORM\Column(type="string", length=300)
     */
    protected $summary;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $code= self::STATUS_DISABLED;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\BugBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinTable(name="members_projects")
     */
    protected $members;

    /**
     * @ORM\OneToMany(targetEntity="Acme\BugBundle\Entity\Issue", mappedBy="project")
     **/
    protected $issues;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->issues = new ArrayCollection();
    }




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Project
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Project
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Project
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add member
     *
     * @param \Acme\BugBundle\Entity\User $member
     *
     * @return Project
     */
    public function addMember(\Acme\BugBundle\Entity\User $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \Acme\BugBundle\Entity\User $member
     */
    public function removeMember(\Acme\BugBundle\Entity\User $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add issue
     *
     * @param \Acme\BugBundle\Entity\Issue $issue
     *
     * @return Project
     */
    public function addIssue(\Acme\BugBundle\Entity\Issue $issue)
    {
        $this->issues[] = $issue;

        return $this;
    }

    /**
     * Remove issue
     *
     * @param \Acme\BugBundle\Entity\Issue $issue
     */
    public function removeIssue(\Acme\BugBundle\Entity\Issue $issue)
    {
        $this->issues->removeElement($issue);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
