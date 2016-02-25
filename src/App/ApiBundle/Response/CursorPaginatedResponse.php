<?php

namespace App\ApiBundle\Response;

class CursorPaginatedResponse extends PaginatedResponseAbstract
{
    /**
     * @var mixed
     */
    protected $before;

    /**
     * @var mixed
     */
    protected $after;

    /**
     * @param $data
     * @param $before
     * @param $after
     * @param $limit
     * @param null $total
     */
    public function __construct($data, $before, $after, $limit, $total = null)
    {
        $this->data   = $data;
        $this->before = $before;
        $this->after  = $after;
        $this->limit  = $limit;
        $this->total  = $total;
    }

    /**
     * @return mixed
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param mixed $before
     */
    public function setBefore($before)
    {
        $this->before = $before;
    }

    /**
     * @return mixed
     */
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * @param mixed $after
     */
    public function setAfter($after)
    {
        $this->after = $after;
    }

    public function toArray()
    {
        $data = [
            'before' => $this->getBefore(),
            'after'  => $this->getAfter(),
            'limit'  => $this->getLimit()
        ];
        if (!is_null($this->getTotal())) {
            $data['total'] = $this->getTotal();
        }

        return $data;
    }
}