<?php
namespace NYPL\Services\Model;

use NYPL\Starter\Model;
use NYPL\Starter\Model\ModelTrait\TranslateTrait;

/**
 * @SWG\Definition(title="ItemDescription", type="object")
 *
 * @package NYPL\Services\Model
 */
class ItemDescription extends Model
{
    use TranslateTrait;

    /**
     * @SWG\Property(example="Some sing, some cry")
     * @var string
     */
    public $title;

    /**
     * @SWG\Property(example="Ifa Bayeza")
     * @var string
     */
    public $author;

    /**
     * @SWG\Property(example="|h*ONPA 84-446")
     * @var string
     */
    public $callNumber;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCallNumber(): string
    {
        return $this->callNumber;
    }

    /**
     * @param string $callNumber
     */
    public function setCallNumber(string $callNumber)
    {
        $this->callNumber = $callNumber;
    }
}
