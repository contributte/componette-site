<?php

namespace App\Modules\Front\Portal;

use App\Model\WebImages\GithubImages;

final class WebImagePresenter extends BasePresenter
{

    /** @var GithubImages @inject */
    public $githubImages;

    /**
     * Create and send user avatar from Github
     *
     * @param string $owner
     * @param string $ext
     */
    public function actionAvatar($owner, $ext)
    {
        $this->githubImages->create([
            'type' => 'avatar',
            'owner' => $owner,
        ]);
    }

}
