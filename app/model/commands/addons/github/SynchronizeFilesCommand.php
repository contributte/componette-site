<?php

namespace App\Model\Commands\Addons\Github;

use App\Model\Commands\BaseCommand;
use App\Model\Facade\Cli\Commands\AddonFacade;
use App\Model\ORM\Addon\Addon;
use App\Model\WebServices\Github\GithubService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeFilesCommand extends BaseCommand
{

    /** @var AddonFacade */
    private $addonFacade;

    /** @var GithubService */
    private $github;

    /**
     * @param AddonFacade $addonFacade
     * @param GithubService $github
     */
    public function __construct(AddonFacade $addonFacade, GithubService $github)
    {
        parent::__construct();
        $this->addonFacade = $addonFacade;
        $this->github = $github;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('addons:github:sync:files')
            ->setDescription('Synchronize github files (composer.json, bower.json)');

        $this->addArgument(
            'type',
            InputOption::VALUE_REQUIRED,
            'What type should be synchronized',
            'all'
        );

        $this->addOption(
            'rest',
            NULL,
            InputOption::VALUE_NONE,
            'Should synchronize only queued addons?'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $addons = $this->addonFacade->find($input);

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {
            // Composer
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_COMPOSER])) {
                $response = $this->github->composer($addon->owner, $addon->name);
                $body = $response->getJsonBody();

                if ($body) {
                    if ($addon->type !== Addon::TYPE_COMPOSER) {
                        $addon->type = Addon::TYPE_COMPOSER;
                    }

                    $addon->github->extra->append('github', ['composer' => $body]);

                    if (($url = $addon->github->extra->get(['github', 'composer', 'download_url'], NULL))) {
                        if (($content = @file_get_contents($url))) {
                            $composer = @json_decode($content, TRUE);
                            $addon->github->extra->set('composer', $composer);
                        } else {
                            $output->writeln('Skip (composer) [invalid composer.json]: ' . $addon->fullname);
                        }
                    } else {
                        $output->writeln('Skip (composer) [can not download composer.json]: ' . $addon->fullname);
                    }
                } else {
                    $output->writeln('Skip (composer): ' . $addon->fullname);
                }
            }

            // Bower
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_BOWER])) {
                $response = $this->github->bower($addon->owner, $addon->name);
                $body = $response->getJsonBody();

                if ($body) {
                    if ($addon->type !== Addon::TYPE_BOWER) {
                        $addon->type = Addon::TYPE_BOWER;
                    }

                    $addon->github->extra->append('github', ['bower' => $body]);

                    if (($url = $addon->github->extra->get(['github', 'bower', 'download_url'], NULL))) {
                        if (($content = @file_get_contents($url))) {
                            $composer = @json_decode($content, TRUE);
                            $addon->github->extra->set('bower', $composer);
                        } else {
                            $output->writeln('Skip (bower) [invalid bower.json]: ' . $addon->fullname);
                        }
                    } else {
                        $output->writeln('Skip (bower) [can not download bower.json]: ' . $addon->fullname);
                    }
                } else {
                    $output->writeln('Skip (bower): ' . $addon->fullname);
                }
            }

            // Untype
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN])) {
                $addon->type = Addon::TYPE_UNTYPE;
            }

            // Persist
            $this->addonFacade->save($addon);

            // Increase counting
            $counter++;
        }

        $output->writeln(sprintf('Updated %s addons files', $counter));
    }

}
