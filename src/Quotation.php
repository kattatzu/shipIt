<?php namespace Kattatzu\ShipIt;

class Quotation
{

    /**
     * @var array items de la cotización
     */
    private $items = [];

    /**
     * Agrega un item a la cotización
     *
     * @param $item
     * @return void
     */
    public function add($item)
    {
        $this->items[] = new QuotationItem($item);
    }


    /**
     * Retorna un array de los items incluídos en la cotización.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Retorna los datos de la cotización en un array
     *
     * @return array
     */
    public function toArray()
    {
        $items = array();
        foreach ($this->items as $item) {
            $items[] = $item->toArray();
        }

        return $items;
    }

}
