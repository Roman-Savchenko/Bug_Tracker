<?php
namespace Acme\BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Acme\BugBundle\Repository\UserRepository")
 **/
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $time_zone;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="reporter")
     */
    protected $issues_reporter;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="assignee")
     */
    protected $issues_assignee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $full_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $avatar;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\BugBundle\Entity\Project", mappedBy="members")
     */
    protected $projects;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\BugBundle\Entity\Issue", inversedBy="users")
     * @ORM\JoinTable(name="users_issues")
     */
    protected $issues;

    public function __construct()
    {
        parent::__construct();
        $this->projects = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->issues_reporter = new ArrayCollection();
        $this->issues_assignee = new ArrayCollection();
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
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add project
     *
     * @param \Acme\BugBundle\Entity\Project $project
     *
     * @return User
     */
    public function addProject(\Acme\BugBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \Acme\BugBundle\Entity\Project $project
     */
    public function removeProject(\Acme\BugBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add issue
     *
     * @param \Acme\BugBundle\Entity\Issue $issue
     *
     * @return User
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set timeZone
     *
     * @param integer $timeZone
     *
     * @return User
     */
    public function setTimeZone($timeZone)
    {
        $this->time_zone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return integer
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }

    /**
     * Add issuesReporter
     *
     * @param \Acme\BugBundle\Entity\Issue $issuesReporter
     *
     * @return User
     */
    public function addIssuesReporter(\Acme\BugBundle\Entity\Issue $issuesReporter)
    {
        $this->issues_reporter[] = $issuesReporter;

        return $this;
    }

    /**
     * Remove issuesReporter
     *
     * @param \Acme\BugBundle\Entity\Issue $issuesReporter
     */
    public function removeIssuesReporter(\Acme\BugBundle\Entity\Issue $issuesReporter)
    {
        $this->issues_reporter->removeElement($issuesReporter);
    }

    /**
     * Get issuesReporter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssuesReporter()
    {
        return $this->issues_reporter;
    }

    /**
     * Add issuesAssignee
     *
     * @param \Acme\BugBundle\Entity\Issue $issuesAssignee
     *
     * @return User
     */
    public function addIssuesAssignee(\Acme\BugBundle\Entity\Issue $issuesAssignee)
    {
        $this->issues_assignee[] = $issuesAssignee;

        return $this;
    }

    /**
     * Remove issuesAssignee
     *
     * @param \Acme\BugBundle\Entity\Issue $issuesAssignee
     */
    public function removeIssuesAssignee(\Acme\BugBundle\Entity\Issue $issuesAssignee)
    {
        $this->issues_assignee->removeElement($issuesAssignee);
    }

    /**
     * Get issuesAssignee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssuesAssignee()
    {
        return $this->issues_assignee;
    }
}
