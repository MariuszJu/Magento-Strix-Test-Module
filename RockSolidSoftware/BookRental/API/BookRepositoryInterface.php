<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\BookInterface;

interface BookRepositoryInterface extends RepositoryInterface
{

    /**
     * Get Books with pagination
     *
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return mixed
     */
    public function getPage(int $page, int $perPage = 10, array $order = null);

    /**
     * Get Book by its slug
     *
     * @param string $slug
     * @return BookInterface
     */
    public function getBySlug(string $slug): BookInterface;

}
