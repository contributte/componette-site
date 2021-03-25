<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList\Statistics;

trait StatisticsComponent
{

	private ControlFactory $statisticsControlFactory;

	public function injectStatisticsControlFactory(ControlFactory $controlFactory): void
	{
		$this->statisticsControlFactory = $controlFactory;
	}

	public function getStatisticsComponent(): Control
	{
		return $this['statistics'];
	}

	protected function createComponentStatistics(): Control
	{
		return $this->statisticsControlFactory->create();
	}

	protected function attachComponentStatistics(Control $component): void
	{
		$this->addComponent($component, 'statistics');
	}

}
