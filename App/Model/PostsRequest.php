<?php

namespace App\Model;

class PostsRequest
{
    public const MAX_PAGE = 10;

    private string $slToken;
    private int $page = 1; // Default first page

    /**
     * @return string
     */
    public function getSlToken(): string
    {
        return $this->slToken;
    }

    /**
     * @param string $slToken
     * @return PostsRequest
     */
    public function setSlToken(string $slToken): self
    {
        $this->slToken = $slToken;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return PostsRequest
     */
    public function setPage(int $page): self
    {
        if ($page > self::MAX_PAGE) {
            $page = self::MAX_PAGE;
        }

        if ($page < 1) {
            $page = 1;
        }

        $this->page = $page;
        return $this;
    }
}
