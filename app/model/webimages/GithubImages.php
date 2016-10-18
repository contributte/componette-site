<?php

namespace App\Model\WebImages;

use App\Model\Exceptions\Runtime\InvalidArgumentException;
use App\Model\WebServices\Github\GithubService;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;

final class GithubImages implements ImageProvider
{

    /** @var GithubService */
    private $github;

    /** @var string */
    private $imageDir;

    /**
     * @param GithubService $github
     * @param string $imageDir
     */
    public function __construct(GithubService $github, $imageDir)
    {
        $this->github = $github;
        $this->imageDir = $imageDir;
    }

    /**
     * AVATAR ******************************************************************
     */

    /**
     * @param string $owner
     */
    protected function createAvatar($owner)
    {
        $response = $this->github->avatar($owner);
        if ($response->isOk()) {
            // Save Github avatar to our FS
            $filename = $this->imageDir . '/avatar/' . $owner;
            FileSystem::write($filename, $response->getBody());

            // Send image for first request
            $image = Image::fromFile($filename);
            $image->send(Image::PNG);
            exit;
        } else {
            throw new InvalidArgumentException('No data downloaded');
        }
    }

    /**
     * @param string $owner
     */
    protected function removeAvatar($owner)
    {
        FileSystem::delete($this->imageDir . '/avatar/' . $owner);
    }

    /**
     * API *********************************************************************
     */

    /**
     * @param array $args
     */
    public function create(array $args)
    {
        if (!isset($args['type'])) {
            throw new InvalidArgumentException('No type given');
        }

        switch ($args['type']) {
            case 'avatar':
                $this->createAvatar($this->normalize($args['owner']));
                break;

            default:
                throw new InvalidArgumentException('Unknown type "' . $args['type'] . '"given');
        }
    }

    /**
     * @param array $args
     */
    public function remove(array $args)
    {
        if (!isset($args['type'])) {
            throw new InvalidArgumentException('No type given');
        }

        switch ($args['type']) {
            case 'avatar':
                $this->removeAvatar($this->normalize($args['owner']));
                break;

            default:
                throw new InvalidArgumentException('Unknown type "' . $args['type'] . '"given');
        }
    }

    /**
     * HELPERS *****************************************************************
     * *************************************************************************
     */

    /**
     * @param string $image
     * @return string
     */
    private function normalize($image)
    {
        return strtolower(htmlspecialchars($image));
    }

}
