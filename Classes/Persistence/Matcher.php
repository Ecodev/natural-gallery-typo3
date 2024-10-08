<?php

namespace Fab\NaturalGallery\Persistence;



/**
 * Matcher class for conditions that will apply to a query.
 */
class Matcher
{
    /**
     * The logical OR
     */
    public const LOGICAL_OR = 'logicalOr';

    /**
     * The logical AND
     */
    public const LOGICAL_AND = 'logicalAnd';


    protected string $dataType = '';


    protected string $searchTerm = '';


    protected array $supportedOperators = [
        '=' => 'equals',
        '!=' => 'notEquals',
        'in' => 'in',
        'like' => 'like',
        '>' => 'greaterThan',
        '>=' => 'greaterThanOrEqual',
        '<' => 'lessThan',
        '<=' => 'lessThanOrEqual',
    ];


    protected array $equals = [];


    protected array $notEquals = [];


    protected array $greaterThan = [];


    protected array $greaterThanOrEqual = [];


    protected array $lessThan = [];


    protected array $lessThanOrEqual = [];


    protected array $in = [];


    protected array $like = [];


    protected array $matches = [];


    protected string $defaultLogicalSeparator = self::LOGICAL_AND;


    protected string $logicalSeparatorForEquals = self::LOGICAL_AND;


    protected string $logicalSeparatorForGreaterThan = self::LOGICAL_AND;


    protected string $logicalSeparatorForGreaterThanOrEqual = self::LOGICAL_AND;


    protected string $logicalSeparatorForLessThan = self::LOGICAL_AND;


    protected string $logicalSeparatorForLessThanOrEqual = self::LOGICAL_AND;


    protected string $logicalSeparatorForIn = self::LOGICAL_AND;


    protected string $logicalSeparatorForLike = self::LOGICAL_AND;


    protected string $logicalSeparatorForSearchTerm = self::LOGICAL_OR;

    /**
     * Constructs a new Matcher
     *
     * @param array $matches associative [$field => $value]
     * @param string $dataType which corresponds to an entry of the TCA (table name).
     */
    public function __construct(array $matches = [], string $dataType = '')
    {
        $this->dataType = $dataType;
        $this->matches = $matches;
    }

    /**
     * @param string $searchTerm
     * @return \Fab\Vidi\Persistence\Matcher
     */
    public function setSearchTerm($searchTerm): Matcher
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    /**
     * @return array
     */
    public function getEquals(): array
    {
        return $this->equals;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function equals($fieldNameAndPath, $operand): self
    {
        $this->equals[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getNotEquals(): array
    {
        return $this->notEquals;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function notEquals($fieldNameAndPath, $operand): self
    {
        $this->notEquals[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getGreaterThan(): array
    {
        return $this->greaterThan;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function greaterThan($fieldNameAndPath, $operand): self
    {
        $this->greaterThan[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getGreaterThanOrEqual(): array
    {
        return $this->greaterThanOrEqual;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function greaterThanOrEqual($fieldNameAndPath, $operand): self
    {
        $this->greaterThanOrEqual[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getLessThan(): array
    {
        return $this->lessThan;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function lessThan($fieldNameAndPath, $operand): self
    {
        $this->lessThan[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getLessThanOrEqual(): array
    {
        return $this->lessThanOrEqual;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function lessThanOrEqual($fieldNameAndPath, $operand): self
    {
        $this->lessThanOrEqual[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getLike(): array
    {
        return $this->like;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @return $this
     */
    public function in($fieldNameAndPath, $operand): self
    {
        $this->in[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $operand];
        return $this;
    }

    /**
     * @return array
     */
    public function getIn(): array
    {
        return $this->in;
    }

    /**
     * @param $fieldNameAndPath
     * @param $operand
     * @param bool $addWildCard
     * @return $this
     */
    public function like($fieldNameAndPath, $operand, bool $addWildCard = true): self
    {
        $wildCardSymbol = $addWildCard ? '%' : '';
        $this->like[] = ['fieldNameAndPath' => $fieldNameAndPath, 'operand' => $wildCardSymbol . $operand . $wildCardSymbol];
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultLogicalSeparator(): string
    {
        return $this->defaultLogicalSeparator;
    }

    /**
     * @param string $defaultLogicalSeparator
     * @return $this
     */
    public function setDefaultLogicalSeparator(string $defaultLogicalSeparator): self
    {
        $this->defaultLogicalSeparator = $defaultLogicalSeparator;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForEquals(): string
    {
        return $this->logicalSeparatorForEquals;
    }

    /**
     * @param string $logicalSeparatorForEquals
     * @return $this
     */
    public function setLogicalSeparatorForEquals(string $logicalSeparatorForEquals): self
    {
        $this->logicalSeparatorForEquals = $logicalSeparatorForEquals;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForGreaterThan(): string
    {
        return $this->logicalSeparatorForGreaterThan;
    }

    /**
     * @param string $logicalSeparatorForGreaterThan
     * @return $this
     */
    public function setLogicalSeparatorForGreaterThan(string $logicalSeparatorForGreaterThan): self
    {
        $this->logicalSeparatorForGreaterThan = $logicalSeparatorForGreaterThan;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForGreaterThanOrEqual(): string
    {
        return $this->logicalSeparatorForGreaterThanOrEqual;
    }

    /**
     * @param string $logicalSeparatorForGreaterThanOrEqual
     * @return $this
     */
    public function setLogicalSeparatorForGreaterThanOrEqual(string $logicalSeparatorForGreaterThanOrEqual): self
    {
        $this->logicalSeparatorForGreaterThanOrEqual = $logicalSeparatorForGreaterThanOrEqual;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForLessThan(): string
    {
        return $this->logicalSeparatorForLessThan;
    }

    /**
     * @param string $logicalSeparatorForLessThan
     * @return $this
     */
    public function setLogicalSeparatorForLessThan(string $logicalSeparatorForLessThan): self
    {
        $this->logicalSeparatorForLessThan = $logicalSeparatorForLessThan;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForLessThanOrEqual(): string
    {
        return $this->logicalSeparatorForLessThanOrEqual;
    }

    /**
     * @param string $logicalSeparatorForLessThanOrEqual
     * @return $this
     */
    public function setLogicalSeparatorForLessThanOrEqual(string $logicalSeparatorForLessThanOrEqual): self
    {
        $this->logicalSeparatorForLessThanOrEqual = $logicalSeparatorForLessThanOrEqual;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForIn(): string
    {
        return $this->logicalSeparatorForIn;
    }

    /**
     * @param string $logicalSeparatorForIn
     * @return $this
     */
    public function setLogicalSeparatorForIn(string $logicalSeparatorForIn): self
    {
        $this->logicalSeparatorForIn = $logicalSeparatorForIn;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForLike(): string
    {
        return $this->logicalSeparatorForLike;
    }

    /**
     * @param string $logicalSeparatorForLike
     * @return $this
     */
    public function setLogicalSeparatorForLike(string $logicalSeparatorForLike): self
    {
        $this->logicalSeparatorForLike = $logicalSeparatorForLike;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogicalSeparatorForSearchTerm(): string
    {
        return $this->logicalSeparatorForSearchTerm;
    }

    /**
     * @param string $logicalSeparatorForSearchTerm
     * @return $this
     */
    public function setLogicalSeparatorForSearchTerm(string $logicalSeparatorForSearchTerm): self
    {
        $this->logicalSeparatorForSearchTerm = $logicalSeparatorForSearchTerm;
        return $this;
    }

    /**
     * @return array
     */
    public function getSupportedOperators(): array
    {
        return $this->supportedOperators;
    }

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     * @return $this
     */
    public function setDataType(string $dataType): self
    {
        $this->dataType = $dataType;
        return $this;
    }
}
