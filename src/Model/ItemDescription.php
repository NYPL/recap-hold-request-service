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
     * @SWG\Property(example="[In Library Use] REFLECTIONS OF CARTIER : THE ART DECO YEARS : NEW YORK EXHIBITION. [RECAP]")
     * @var string
     */
    public $title;

    /**
     * @SWG\Property(example="Cartier, Louis, 1875-1942.   ")
     * @var string
     */
    public $author;

    /**
     * @SWG\Property(example="|hJAX C-3278")
     * @var string
     */
    public $callNumber;

    /**
     * @return string
     */
    public function getTitle()
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
    public function getAuthor()
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
    public function getCallNumber()
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
