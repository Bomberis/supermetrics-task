<?php

namespace App\Service;

use App\Model\Post;
use App\Model\PostsRequest;

/**
 *
 * Class could be remade to return each calculation separately, but intent was to have less cycles
 *
 * Class StatsProvider
 * @package App\Service
 */
class StatsProvider
{
    private array $totalPostsPerWeek = [];
    private array $longestCharLengthPostPerMonth = [];
    private array $totalCharLengthPerMonth = [];
    private array $averageCharLengthPostPerMonth = [];
    private array $totalNumberOfPostsPerUserPerMonth = [];
    private array $averageNumberOfPostsPerUserInMonth = [];

    private SupermetricsApi $api;

    public function __construct(SupermetricsApi $api)
    {
        $this->api = $api;
    }

    /**
     * @param string $token
     * @param int $page
     * @return array
     */
    public function generate(string $token, int $page): array
    {
        $request = new PostsRequest();
        $request
            ->setSlToken($token)
            ->setPage($page)
        ;

        $posts = $this->api->getPosts($request);

        foreach ($posts as $post) {
            $this->totalPostsPerWeek($post);
            $this->longestCharLengthPostPerMonth($post);
            $this->totalCharLengthPerMonth($post);
            $this->totalNumberOfPostsPerUserPerMonth($post);
        }

        $this->averageCharLengthPostPerMonth();
        $this->averageNumberOfPostsPerUserInMonth();

        return [
            'totalPostsPerWeek' => $this->totalPostsPerWeek,
            'longestCharLengthPostPerMonth' => $this->longestCharLengthPostPerMonth,
            'averageCharLengthPostPerMonth' => $this->averageCharLengthPostPerMonth,
            'averageNumberOfPostsPerUserInMonth' => $this->averageNumberOfPostsPerUserInMonth,
        ];
    }

    /**
     * @param Post $post
     * @return void
     */
    private function totalCharLengthPerMonth(Post $post): void
    {
        $month = (int) $post->getCreatedTime()->format('n');

        if (!isset($this->totalCharLengthPerMonth[$month])) {
            $this->totalCharLengthPerMonth[$month] = [
                'postCount' => 0,
                'charCount' => 0,
            ];
        }

        $this->totalCharLengthPerMonth[$month] = [
            'postCount' => $this->totalCharLengthPerMonth[$month]['postCount'] + 1,
            'charCount' => $this->totalCharLengthPerMonth[$month]['charCount'] + strlen($post->getMessage()),
        ];
    }

    /**
     * @return void
     */
    private function averageCharLengthPostPerMonth(): void
    {
        foreach ($this->totalCharLengthPerMonth as $month => $stats) {
            $this->averageCharLengthPostPerMonth[$month] = $stats['charCount'] / $stats['postCount'];
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    private function longestCharLengthPostPerMonth(Post $post): void
    {
        $month = (int) $post->getCreatedTime()->format('n');

        if (!isset($this->longestCharLengthPostPerMonth[$month])) {
            $this->longestCharLengthPostPerMonth[$month] = [
                'id' => null,
                'length' => 0,
            ];
        }

        $messageLength = strlen($post->getMessage());

        if ($this->longestCharLengthPostPerMonth[$month]['length'] < $messageLength) {
            $this->longestCharLengthPostPerMonth[$month] = [
                'id' => $post->getId(),
                'length' => $messageLength,
            ];
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    private function totalPostsPerWeek(Post $post): void
    {
        $week = (int) $post->getCreatedTime()->format('W');
        $this->totalPostsPerWeek[$week] += 1;
    }

    /**
     * @return void
     */
    private function averageNumberOfPostsPerUserInMonth(): void
    {
        foreach ($this->totalNumberOfPostsPerUserPerMonth as $user => $userPosts) {
            $mothCount = count($userPosts);
            $postCount = 0;
            foreach ($userPosts as $post) {
                $postCount += $post['posts'];
            }
            $this->averageNumberOfPostsPerUserInMonth[$user] = $postCount / $mothCount;
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    private function totalNumberOfPostsPerUserPerMonth(Post $post): void
    {
        $user = $post->getFromId();
        $month = (int) $post->getCreatedTime()->format('n');

        if (!isset($this->totalNumberOfPostsPerUserPerMonth[$user][$month])) {
            $this->totalNumberOfPostsPerUserPerMonth[$user][$month]['posts'] = 0;
        }

        $this->totalNumberOfPostsPerUserPerMonth[$user][$month]['posts'] += 1;
    }
}
