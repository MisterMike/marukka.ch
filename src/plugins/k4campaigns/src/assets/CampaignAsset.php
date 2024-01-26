<?php

	
	namespace k4\k4campaigns\assets;
	
	use craft\web\AssetBundle;
	use craft\web\assets\cp\CpAsset;
	
	class CampaignAsset extends AssetBundle
	{
		/**
		 * @inheritdoc
		 */
		public function init()
		{
			$this->sourcePath = '@k4/k4campaigns/resources';
			
			$this->depends = [
				CpAsset::class,
			];
			
			// define the relative path to CSS/JS files that should be registered with the page when this asset bundle is registered
			$this->css = [
                'css/Campaign.css',
			];
			$this->js = [
				'js/Campaign.js',
				'js/CampaignIndex.js',
                'js/CampaignPrepareModal.js',
			];
			
			parent::init();
		}
	}
