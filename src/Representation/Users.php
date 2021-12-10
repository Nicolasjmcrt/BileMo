<?php


namespace App\Representation;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Pagerfanta\Pagerfanta;

/**
 * Class Users
 *
 * @package App\Representation
 *
 */
class Users
{
    /**
     * @var array
     *
     * @Serializer\Groups({"LIST_USER"})
     * @Serializer\Type("array<App\Entity\User>")
     */
    public $data;

    /**
     * Users constructor.
     *
     * @param array $pager
     */
    public function __construct(
        array $pager
    )
    {
        $this->data = $pager;
    }

    
}