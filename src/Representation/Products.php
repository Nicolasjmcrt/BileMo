<?php


namespace App\Representation;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Pagerfanta\Pagerfanta;

/**
 * Class Products
 *
 * @package App\Representation
 *
 */
class Products
{
    /**
     * @var array
     *
     * @Serializer\Groups({"LIST_PRODUCT"})
     * @Serializer\Type("array<App\Entity\Product>")
     */
    public $data;

    /**
     * Products constructor.
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