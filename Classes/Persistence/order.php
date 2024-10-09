<?php


namespace Fab\NaturalGallery\Persistence;



/**
 * Order class for order that will apply to a query
 */
class Order
{
    /**
     * The orderings
     *
     * @var array
     */
    protected array $orderings = [];

    /**
     * Constructs a new Order
     *
     * @para array $orders
     * @param array $orders
     */
    public function __construct(array $orders = array())
    {
        foreach ($orders as $order => $direction) {
            $this->addOrdering($order, $direction);
        }
    }

    /**
     * Add ordering
     *
     * @param string $order The order
     * @param string $direction ASC / DESC
     * @return void
     */
    public function addOrdering(string $order, string $direction): void
    {
        $this->orderings[$order] = $direction;
    }

    /**
     * Returns the order
     *
     * @return array The order
     */
    public function getOrderings(): array
    {
        return $this->orderings;
    }
}
