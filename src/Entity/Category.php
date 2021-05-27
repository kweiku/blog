<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category.
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * Id.
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * Posts.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Post[] $posts Posts
     *
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="category")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Posts.
     *
     * @return Collection|Post[] Post
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * Add post.
     *
     * @param Post $post
     * @return $this
     */
    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }
    }

    /**
     * Remove post.
     *
     * @param Post $post
     * @return $this
     */
    public function removePost(Post $post): void
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }
    }
}