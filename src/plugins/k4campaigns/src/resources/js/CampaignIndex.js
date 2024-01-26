/** global: Campaign */
/** global: Craft */
/** global: Garnish */
/**
 * CampaignIndex class
 */
Campaign.CampaignIndex = Craft.BaseElementIndex.extend(
	{
		k4CampaignsCampaignTypes:null,
		$newCampaignBtnGroup: null,
		$newCampaignBtn: null,

		init: function(elementType, $container, settings)
		{
			this.on('selectSource', $.proxy(this, 'updateButton'));
			this.base(elementType, $container, settings);
		},

		afterInit: function() {

			// Get publishable campaign types
			this.k4CampaignsCampaignTypes = [];

			for (var i = 0; i < Craft.k4CampaignsCampaignTypes.length; i++) {
				var campaignType = Craft.k4CampaignsCampaignTypes[i];

				if (this.getSourceByKey('campaignType:' + campaignType.id)) {
					this.k4CampaignsCampaignTypes.push(campaignType);
				}
			}

			this.base();
		},


		getDefaultSourceKey: function() {

			return this.base();
		},

		updateButton: function() {

			if (!this.$source) {
				return;
			}

			var handle;

			// Get the handle of the selected source
			handle = this.$source.data('handle');

			// Update the New campaign button
			// ---------------------------------------------------------------------

			if (this.k4CampaignsCampaignTypes.length) {
				// Remove the old button, if there is one
				if (this.$newCampaignTypeBtnGroup) {
					this.$newCampaignTypeBtnGroup.remove();
				}

				// Determine if they are viewing a campaign
				var selectedCampaignType;

				if (handle) {
					for (var i = 0; i < this.k4CampaignsCampaignTypes.length; i++) {
						if (this.k4CampaignsCampaignTypes[i].handle == handle) {
							selectedCampaignType = this.k4CampaignsCampaignTypes[i];
							break;
						}
					}
				}

				this.$newCampaignTypeBtnGroup = $('<div class="btngroup submit"/>');
				var $menuBtn;

				// If they are, show a primary "New Coupon" button, and a dropdown of the other campaigns (if any).
				// Otherwise only show a menu button
				if (selectedCampaignType) {
					var href = this._getCampaignTypeTriggerHref(selectedCampaignType),
						label = Craft.t('k4-campaigns', 'New {campaignType} campaign', {campaignType: selectedCampaignType.name});

					this.$newCampaignBtn = $('<a class="btn submit add icon" ' + href + '>' + Craft.escapeHtml(label) + '</a>').appendTo(this.$newCampaignTypeBtnGroup);

					this.addListener(this.$newCampaignBtn, 'click', function(ev) {
						window.location.href =  Craft.getUrl('k4-campaigns/campaigns/'+ev.currentTarget.getAttribute('data-handle')+'/new');
						//this._openCreateCampaignModal(ev.currentTarget.getAttribute('data-id'));
					});

					if (this.k4CampaignsCampaignTypes.length > 1) {
						$menuBtn = $('<div class="btn submit menubtn"></div>').appendTo(this.$newCampaignTypeBtnGroup);
					}
				}
				else {
					this.$newCampaignBtn = $menuBtn = $('<div class="btn submit add icon menubtn">' + Craft.t('k4-campaigns', 'New campaign') + '</div>').appendTo(this.$newCampaignTypeBtnGroup);
				}

				if ($menuBtn) {
					var menuHtml = '<div class="menu"><ul>';

					for (var i = 0; i < this.k4CampaignsCampaignTypes.length; i++) {
						var campaignType = this.k4CampaignsCampaignTypes[i];

						if (
							(campaignType != selectedCampaignType)
						) {
							var href = this._getCampaignTypeTriggerHref(campaignType),
								label = Craft.t('k4-campaigns', 'New {campaignType} campaign', {campaignType: campaignType.name});
							menuHtml += '<li><a ' + href + '">' + Craft.escapeHtml(label) + '</a></li>';
						}
					}

					menuHtml += '</ul></div>';

					var $menu = $(menuHtml).appendTo(this.$newCampaignTypeBtnGroup),
						menuBtn = new Garnish.MenuBtn($menuBtn);

					menuBtn.on('optionSelect', $.proxy(function(ev) {

						window.location.href =  Craft.getUrl('k4-campaigns/campaigns/'+ev.option.getAttribute('data-handle')+'/new');
						//this._openCreateCampaignModal(ev.option.getAttribute('data-id'));
					}, this));

				}

				this.addButton(this.$newCampaignTypeBtnGroup);
			}

			// Update the URL if we're on the Coupon index
			// ---------------------------------------------------------------------

			if (typeof history !== 'undefined') {
				var uri = 'k4-campaigns/campaigns';

				if (handle) {
					uri += '?campaignType=' + handle;
				}

				history.replaceState({}, '', Craft.getUrl(uri));
			}
		},

		_getCampaignTypeTriggerHref: function(campaignType) {

			return 'data-id="' + campaignType.id + '" data-handle="'+ campaignType.handle +'"';

		},

		_openCreateCampaignModal: function(campaignTypeId) {
			if (this.$newCampaignBtn.hasClass('loading')) {
				return;
			}

			// Find the campaign type
			var campaignType;

			for (var i = 0; i < this.k4CampaignsCampaignTypes.length; i++) {
				if (this.k4CampaignsCampaignTypes[i].id == campaignTypeId) {
					campaignType = this.k4CampaignsCampaignTypes[i];
					break;
				}
			}
			if (!campaignType) {
				return;
			}

			this.$newCampaignBtn.addClass('inactive');
			var newCampaignBtnText = this.$newCampaignBtn.text();
			this.$newCampaignBtn.text(Craft.t('k4-campaigns', 'New campaign', {campaignType: campaignType.name}));

			Craft.createElementEditor(this.elementType, {
				hudTrigger: this.$newCampaignBtnGroup,
				elementType: 'k4\\k4campaigns\\elements\\CampaignElement',
				attributes: {
					campaignTypeId: campaignTypeId
				},
				onBeginLoading: $.proxy(function() {
					this.$newCampaignBtn.addClass('loading');
				}, this),
				onEndLoading: $.proxy(function() {
					this.$newCampaignBtn.removeClass('loading');
				}, this),
				onHideHud: $.proxy(function() {
					this.$newCampaignBtn.removeClass('inactive').text(newCampaignBtnText);
				}, this),
				onSaveElement: $.proxy(function(response) {
					this.selectElementAfterUpdate(response.id);
					this.updateElements();
				}, this)
			});
		}
	});

// Register it!
Craft.registerElementIndexClass('k4\\k4campaigns\\elements\\CampaignElement', Campaign.CampaignIndex);
