<?php


namespace Juff\Controller\Utils;


class PaginatorWIthSorting
{
    private const ITEMS_PER_PAGE = 3;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $itemsCount;

    /**
     * @var string
     */
    private $sortField = 'name';

    /**
     * @var string
     */
    private $order ='asc';

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getSortField(): string
    {
        return $this->sortField;
    }

    /**
     * @param string $sortField
     */
    public function setSortField(string $sortField): void
    {
        $this->sortField = $sortField;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder(string $order): void
    {
        $this->order = $order;
    }

    public function toggleOrder(): string
    {
        return $this->getOrder() == 'asc' ? 'desc' : 'asc';
    }

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * self::ITEMS_PER_PAGE;
    }

    public function getItemsPerPageCount(): int
    {
        return self::ITEMS_PER_PAGE;
    }

    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }

    public function setItemsCount(int $itemsCount): void
    {
        $this->itemsCount = $itemsCount;
    }

    public function pagesCount()
    {
        $count = ceil($this->getItemsCount()/self::ITEMS_PER_PAGE);

        return $count < 1 ? 1 : $count;
    }

    public function getQuery(int $page = null, string $sort = null, string $order = null): string
    {
        return http_build_query([
            'page' => $page ?? $this->getPage(),
            'sort' => $sort ?? $this->getSortField(),
            'order' => $order ?? $this->getOrder(),
        ]);
    }
}