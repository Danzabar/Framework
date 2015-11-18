<?php

namespace Wasp\Entity;

use Wasp\Entity\EntityCollection;
use Wasp\Exceptions\Entity\PaginationTemplateNotSet;

/**
 * Collection class for returning paginated results
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
class PaginatedEntityCollection extends EntityCollection
{
    /**
     * The total number of records
     *
     * @var Integer
     */
    protected $total;

    /**
     * The total amount of pages
     *
     * @var Integer
     */
    protected $totalPages;

    /**
     * The current page number
     *
     * @var Integer
     */
    protected $pageNo;

    /**
     * The active query limit
     *
     * @var Integer
     */
    protected $pageSize;

    /**
     * Set up class dependencies
     *
     * @param Array $collectable
     * @param Integer $total
     * @param Integer $totalPages
     * @param Integer $pageNo
     * @param Integer $pageSize
     */
    public function __construct(Array $collectable = array(), $total = 0, $totalPages = 0, $pageNo = 0, $pageSize = 0)
    {
        parent::__construct($collectable);

        $this->total = $total;
        $this->totalPages = $totalPages;
        $this->pageNo = $pageNo;
        $this->pageSize = $pageSize;
    }

    /**
     * Renders the pagination template with variables available
     *
     * @return String
     * @throws PaginationTemplateNotSet
     */
    public function pagination()
    {
        $settings = $this->DI->get('profile')->getSettings();

        if (isset($settings['database']['pagination_template'])) {
            return $this->DI->get('template')->render(
                $settings['database']['pagination_template'],
                [
                    'total'         => $this->total,
                    'totalPages'    => $this->totalPages,
                    'pageNo'        => $this->pageNo,
                    'pageSize'      => $this->pageSize
                ]
            );
        }

        throw new PaginationTemplateNotSet();
    }

    /**
     * Returns the value for the Total var
     *
     * @return Integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns the value for total pages
     *
     * @return Integer
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Returns the value for page number
     *
     * @return Integer
     */
    public function getPageNo()
    {
        return $this->pageNo;
    }

    /**
     * Returns the value for page size
     *
     * @return Integer
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }
} // END class PaginatedEntityCollection extends EntityCollection
