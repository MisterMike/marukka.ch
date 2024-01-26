/** global: Craft */
/** global: Garnish */
/**
 * Preview File Modal
 */
Campaign.PrepareModal = Garnish.Modal.extend(
	{
		assetId: null,
		$spinner: null,
		elementSelect: null,
		type: null,
		loaded: null,
		requestId: 0,
		campaignId: null,

		/**
		 * Initialize the preview file modal.
		 * @returns {*|void}
		 */
		init: function(campaignId,settings) {

			this.campaignId = campaignId;
			//settings = $.extend(this.defaultSettings, settings);

			//settings.onHide = this._onHide.bind(this);

			//Craft.PrepareModal.openInstance = this;
			//this.elementSelect = elementSelect;

			this.$container = $('<div class="modal preparemodal loading"/>').appendTo(Garnish.$bod);

			this.base(this.$container, $.extend({
				resizable: false
			}, settings));

			this._resizeContainer(800,600);

			var self = this;

			Craft.postActionRequest('k4-campaigns/campaigns/prepare-modal', {campaignId: campaignId}, function(response, textStatus) {

				self.$container.removeClass('loading');
				self.loaded = true;

				self.$container.html(response.html);

				self.$cancelBtn = self.$container.find('.cancel:first');
				self.addListener(self.$cancelBtn, 'click', 'cancel');

				self.$closeBtn = self.$container.find('.close:first');
				self.addListener(self.$closeBtn, 'click', 'reload');

				self.$sendBtn = self.$container.find('.send-campaign:first');
				self.addListener(self.$sendBtn, 'click', 'sendCampaign');


				// Cut the flicker, just show the nice person the preview.
				if (self.$container) {
					self.$container.velocity('stop');
					self.$container.show().css('opacity', 1);

					self.$shade.velocity('stop');
					self.$shade.show().css('opacity', 1);
				}


			});


		},

		/**
		 * When hiding, remove all traces and focus last focused element.
		 * @private
		 */
		_onHide: function () {
			Craft.PreviewFileModal.openInstance = null;
			if (this.elementSelect) {
				this.elementSelect.focusItem(this.elementSelect.$focusedItem);
			}

			this.$shade.remove();

			return this.destroy();
		},

		/**
		 * Disappear immediately forever.
		 * @returns {boolean}
		 */
		selfDestruct: function () {
			var instance = Craft.PreviewFileModal.openInstance;

			instance.hide();
			instance.$shade.remove();
			instance.destroy();

			Craft.PreviewFileModal.openInstance = null;

			return true;
		},

		/**
		 * Resize the container to specified dimensions
		 * @param containerWidth
		 * @param containerHeight
		 * @private
		 */
		_resizeContainer: function (containerWidth, containerHeight) {
			this.$container.css({
				'width': containerWidth,
				'min-width': containerWidth,
				'max-width': containerWidth,
				'height': containerHeight,
				'min-height': containerHeight,
				'max-height': containerHeight,
				'top': (Garnish.$win.height() - containerHeight) / 2,
				'left': (Garnish.$win.width() - containerWidth) / 2
			});
		},

		cancel: function() {
			this.hide();

			if (this.message) {
				this.message.modal = null;
			}
		},
		reload: function() {
			location.reload();
		},
		sendCampaign: function()
		{
			var self = this;

			var sendButtonHtml = this.$sendBtn.prop('disabled',true);

			self.$container.find('.regular').addClass('hidden');
			self.$container.find('.loading').removeClass('hidden');

			Craft.postActionRequest('k4-campaigns/campaigns/send', {campaignId: this.campaignId}, function(response, textStatus) {

				self.$container.find('.loading').addClass('hidden');
				self.$sendBtn.addClass('hidden');
				self.$cancelBtn.addClass('hidden');
				self.$closeBtn.removeClass('hidden');


				if (textStatus != "success" || response.success == false) {
					self.$container.find('.failure').removeClass('hidden');
					self.$container.find('.failure .failure-text').html(response.error);
					self.$container.find('.failure .content-pane').html(response.trace);
					self.$sendBtn.remove();
				}
				else {
					self.$container.find('.success').removeClass('hidden');
				}

			});
		},

	},
	{
		defaultSettings: {
			startingWidth: null,
			startingHeight: null
		}
	}
);
