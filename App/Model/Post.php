<?php

namespace App\Model;

use DateTimeInterface;

class Post
{
    private string $id;
    private string $fromName;
    private string $fromId;
    private string $message;
    private string $type;
    private DateTimeInterface $createdTime;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Post
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     * @return Post
     */
    public function setFromName(string $fromName): self
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromId(): string
    {
        return $this->fromId;
    }

    /**
     * @param string $fromId
     * @return Post
     */
    public function setFromId(string $fromId): self
    {
        $this->fromId = $fromId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Post
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Post
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedTime(): DateTimeInterface
    {
        return $this->createdTime;
    }

    /**
     * @param DateTimeInterface $createdTime
     * @return Post
     */
    public function setCreatedTime(DateTimeInterface $createdTime): self
    {
        $this->createdTime = $createdTime;
        return $this;
    }
}
