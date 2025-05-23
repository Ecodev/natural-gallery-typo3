<?php

namespace Fab\NaturalGallery\Domain\Model;



use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Representation of a Selection
 */
class Selection extends AbstractEntity
{
    public const VISIBILITY_EVERYONE = 0;
    public const VISIBILITY_PRIVATE = 1;
    public const VISIBILITY_ADMIN_ONLY = 2;

    /**
     * @var int
     */
    protected int $visibility;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $dataType;

    /**
     * @var string
     */
    protected string $query;

    /**
     * @var string
     */
    protected string $speakingQuery;

    /**
     * @var int
     */
    protected int $owner;

    /**
     * @param string $dataType
     * @return $this
     */
    public function setDataType(string $dataType): static
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @param string $query
     * @return $this
     */
    public function setQuery(string $query): static
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getSpeakingQuery(): string
    {
        return $this->speakingQuery;
    }

    /**
     * @param string $speakingQuery
     * @return $this
     */
    public function setSpeakingQuery(string $speakingQuery): static
    {
        $this->speakingQuery = $speakingQuery;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $visibility
     * @return $this
     */
    public function setVisibility(int $visibility): static
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return int
     */
    public function getVisibility(): int
    {
        return $this->visibility;
    }

    /**
     * @return int
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return $this
     */
    public function setOwner(int $owner): static
    {
        $this->owner = $owner;
        return $this;
    }
}
