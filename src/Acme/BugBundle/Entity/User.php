<?php
namespace Acme\BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Util\SecureRandom;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *  @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Acme\BugBundle\Repository\UserRepository")
 **/
class User extends BaseUser
{
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_OPERATOR ='ROLE_OPERATOR';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

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
     * @Assert\File(maxSize="2048k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $avatar;

    private $tempProfilePicturePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatarPath;


    /**
     * @ORM\ManyToMany(targetEntity="Acme\BugBundle\Entity\Project", mappedBy="members")
     */
    protected $projects;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\BugBundle\Entity\Issue", inversedBy="users")
     * @ORM\JoinTable(name="users_issues")
     */
    protected $issues;

    /**
     * @var Issue[] $assignedIssue
     *
     * @ORM\OneToMany(targetEntity="Acme\BugBundle\Entity\Issue", mappedBy="assignee")
     **/
    protected $assignedIssue;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        $this->assignedIssue = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * Sets the file used for profile picture uploads
     *
     * @param UploadedFile $file
     * @return object
     */
    public function setAvatar(UploadedFile $file = null) {
        // set the value of the holder
        $this->avatar = $file;
        // check if we have an old image path
        if (isset($this->avatarPath)) {
            // store the old name to delete after the update
            $this->tempProfilePicturePath = $this->avatarPath;
            $this->avatarPath = null;
        } else {
            $this->avatarPath = 'initial';
        }

        return $this;
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getAvatar() {

        return $this->avatar;
    }

    /**
     * Set avatarPath
     *
     * @param string $avatarPath
     * @return User
     */
    public function setAvatarPath($avatarPath)
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    /**
     * Get avatarPath
     *
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }

    /**
     * Get the absolute path of the avatarPath
     */
    public function getAvatarAbsolutePath() {
        return null === $this->avatarPath
            ? null
            : $this->getUploadRootDir().'/'.$this->avatarPath;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir($type='profilePicture') {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir($type);
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir($type='profilePicture') {
        // the type param is to change these methods at a later date for more file uploads
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/user/profilepics';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebAvatarPath() {

        return '/'.$this->getUploadDir().'/'.$this->getAvatarPath();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadProfilePicture() {
        if (null !== $this->getAvatar()) {
            // a file was uploaded
            // generate a unique filename
            $filename = $this->generateRandomProfilePictureFilename();
            $this->setAvatarPath($filename.'.'.$this->getAvatar()->guessExtension());
        }
    }

    /**
     * Generates a 32 char long random filename
     *
     * @return string
     */
    public function generateRandomProfilePictureFilename() {
        $count   =   0;
        do {
            $generator = new SecureRandom();
            $random = $generator->nextBytes(16);
            $randomString = bin2hex($random);
            $count++;
        }
        while(file_exists($this->getUploadRootDir().'/'.$randomString.'.'.$this->getAvatar()->guessExtension()) && $count < 50);

        return $randomString;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadProfilePicture() {
        // check there is a profile pic to upload
        if ($this->getAvatar() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getAvatar()->move($this->getUploadRootDir(), $this->getAvatarPath());

        // check if we have an old image
        if (isset($this->tempProfilePicturePath) && file_exists($this->getUploadRootDir().'/'.$this->tempProfilePicturePath)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempProfilePicturePath);
            // clear the temp image path
            $this->tempProfilePicturePath = null;
        }
        $this->avatar = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        if ($file = $this->getAvatarAbsolutePath() && file_exists($this->getAvatarAbsolutePath())) {
            unlink($file);
        }
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

    /**
     * Add comment
     *
     * @param \Acme\BugBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\Acme\BugBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Acme\BugBundle\Entity\Comment $comment
     */
    public function removeComment(\Acme\BugBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add assignedIssue
     *
     * @param \Tracker\IssueBundle\Entity\Issue $assignedIssue
     *
     * @return User
     */
    public function addAssignedIssue(\Tracker\IssueBundle\Entity\Issue $assignedIssue)
    {
        $this->assignedIssue[] = $assignedIssue;

        return $this;
    }

    /**
     * Remove assignedIssue
     *
     * @param \Tracker\IssueBundle\Entity\Issue $assignedIssue
     */
    public function removeAssignedIssue(\Tracker\IssueBundle\Entity\Issue $assignedIssue)
    {
        $this->assignedIssue->removeElement($assignedIssue);
    }

    /**
     * Get assignedIssue
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignedIssue()
    {
        return $this->assignedIssue;
    }
}
