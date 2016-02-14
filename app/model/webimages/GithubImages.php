<?php

namespace App\Model\WebImages;

use App\Model\Exceptions\Runtime\InvalidArgumentException;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;

final class GithubImages implements ImageProvider
{

    // Github URL's
    const URL_AVATAR = 'https://github.com/%s';

    /** @var string */
    private $imageDir;

    /**
     * @param string $imageDir
     */
    public function __construct($imageDir)
    {
        $this->imageDir = $imageDir;
        FileSystem::createDir($imageDir);
    }

    /**
     * FACTORIES ***************************************************************
     */

    /**
     * @param string $owner
     */
    protected function createAvatar($owner)
    {
        $image = @file_get_contents(sprintf(self::URL_AVATAR, $owner));
        if ($image) {
            // Save Github avatar to our FS
            $filename = $this->imageDir . '/avatar/' . $owner;
            FileSystem::write($filename, $image);

            // Send image for first request
            $image = Image::fromFile($filename);
            $image->send(Image::PNG);
            exit;
        }

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
