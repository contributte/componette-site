<?php

namespace App\Model\Packages\Content;

final class Content
{

    /** @var ContentManager */
    private $cm;

    /**
     * @param ContentManager $manager
     */
    function __construct(ContentManager $manager)
    {
        $this->cm = $manager;
    }

    /**
     * @param string $hash
     */
    public function get($hash)
    {
        return $this->cm->load($hash);
    }

}