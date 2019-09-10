<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\BookInterface;

interface BookRepositoryInterface extends RepositoryInterface
{

    /**
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return mixed
     */
    public function getPage(int $page, int $perPage = 10, array $order = null);

    /**
     * @param string $slug
     * @return BookInterface
     */
    public function getBySlug(string $slug): BookInterface;

}
