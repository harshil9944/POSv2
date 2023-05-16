Vue.component("vue-multiselect", window.VueMultiselect.default);
Vue.mixin({
	methods: {
		getSum: function (records, field) {
			var total = 0;
			if (records.length) {
				total = records.reduce(function (total, row) {
					return Number(total) + Number(row[field]);
				}, total);
			}
			return total;
		},
		dtFormat: function (date) {
			return moment(date).format("DD/MM/YYYY");
		},
		beautifyDate: function (value) {
			return this.$options.filters.beautifyDate(value);
		},
		beautifyCurrency: function (value) {
			return this.$options.filters.beautifyCurrency(value);
		},
		toTwoDecimal: function (value) {
			return this.$options.filters.toTwoDecimal(value);
		},
		handleOpenModal: function (modal) {
			this.$bvModal.show(modal);
		},
		handleCloseModal: function (modal) {
			this.$bvModal.hide(modal);
		},
		showDialog: function () {
			this.handleOpenModal(this.modal.id);
		},
		hideDialog: function () {
			this.handleCloseModal(this.modal.id);
		},
		generateOrderItemId: function (itemId) {
			return itemId + "-" + Math.floor(Math.random() * 100) + 1;
		},
		getDiscountAmount: function (item, type, discountValue, calculateQuantity) {
			if (typeof calculateQuantity === "undefined") {
				calculateQuantity = false;
			}
			var amount = 0;
			if (type === "f") {
				amount = Number(discountValue);
			} else if (type === "p") {
				amount = Number(
					(Number(discountValue) * Number(item.rate)) / 100,
				).toFixed(2);
			} else if (type === "free") {
				amount = Number(item.rate);
			}
			if (calculateQuantity) {
				amount = Number(amount) * Number(item.quantity);
			}
			return amount;
		},
		getIconClass: function (id) {
			var icon = this.icons.find(function (s) {
				return s.id === id;
			});
			return icon ? icon.title : "fas fa-utensils";
		},
		isOpenItem: function (itemId) {
			return Number(itemId) === Number(_s("openItemId"));
		},
	},
});
Vue.filter("imagePath", function (src) {
	if (src !== "" && src != null) {
		return _s("imgCacheUrl") + src;
	} else {
		return _s("noImgUrl");
	}
});
var promotionMixin = {
	data: function () {
		return {
			masters: {
				promotions: _s("promotions"),
				cachedItems: [],
			},
		};
	},
	computed: {
		hasPromotion: function () {
			return this.order.promotions.available.length > 0;
		},
		hasAppliedPromotion: function () {
			return this.order.promotions.applied.length > 0;
		},

		getAppliedPromotionsText: function () {
			var self = this;
			var text = "Applied Promotions: ";
			if (this.order.promotions.applied.length > 0) {
				this.order.promotions.applied.forEach(function (id, index) {
					var p = self.getPromotion(id);
					if (index === 0) {
						text += typeof p === "Object" ? p.title : "";
					} else {
						text += typeof p === "Object" ? ", " + p.title : "";
					}
				});
			}
			return text;
		},
		getCriteriaInclude: function () {
			if (typeof this.sp.criteria !== "undefined") {
				return this.sp.criteria.include;
			}
			return [];
		},
		getRewardInclude: function () {
			if (typeof this.sp.reward !== "undefined") {
				return this.sp.reward.include;
			}
			return [];
		},
	},
	methods: {
		isCartItem: function (itemId) {
			var item = this.order.cart.items.findIndex(function (i) {
				return i.itemId === itemId;
			});
			return item !== -1;
		},
		getItem: function (itemId) {
			return this.masters.cachedItems.find(function (i) {
				return i.id === itemId;
			});
		},
		getItemName: function (itemId) {
			var item = this.getItem(itemId);
			if (item.type === "single") {
				return item.title;
			} else {
				var variation = item.variations.find(function (v) {
					return v.itemId === itemsId;
				});
				return item.title + " (" + variation.title + ")";
			}
		},
		getItemPriceText: function (itemId) {
			var price = 0;
			var item = this.getItem(itemId);
			if (item.type === "group") {
				var variation = item.variations.find(function (ip) {
					return ip.id === id;
				});
				price = typeof variation.rata !== "undefined" ? variation.rate : 0;
			} else {
				price = item.rate;
			}
			return this.beautifyCurrency(this.toTwoDecimal(price));
		},
		getItemDiscountedPriceText: function (itemId) {
			var price = 0;
			var item = this.getItem(itemIndex);
			if (item.type === "group") {
				var variation = item.variations.find(function (ip) {
					return ip.id === id;
				});
				price = typeof variation.rate !== "undefined" ? variation.rate : 0;
			} else {
				price = item.rate;
			}
			var reward = this.sp.reward;
			item.rate = price;
			var discount = this.getDiscountAmount(
				item,
				reward.discountType,
				reward.discountValue,
			);
			price = Number(price) - Number(discount);
			return this.beautifyCurrency(this.toTwoDecimal(price));
		},
		getPromotion: function (id) {
			return this.masters.promotions.find(function (p) {
				return p.id === id;
			});
		},
		getAvailablePromotions: function (itemId) {
			var self = this;
			var promotions = [];
			this.masters.promotions.forEach(function (s) {
				var addPromotion = false;
				if (s.offerType !== "basic") {
					var criteria = s.criteria ? s.criteria : false;
					var reward = s.reward ? s.reward : false;
					if (criteria) {
						var criteriaIncludes = criteria.include.length
							? criteria.include
							: false;
						if (criteriaIncludes) {
							criteriaIncludes.forEach(function (cInc) {
								if (cInc.itemId.toString() === itemId.toString()) {
									addPromotion = true;
								}
							});
						}
					}
					if (reward) {
						var rewardIncludes = reward.include.length ? reward.include : false;
						if (rewardIncludes) {
							rewardIncludes.forEach(function (rInc) {
								if (rInc.itemId.toString() === itemId.toString()) {
									addPromotion = true;
								}
							});
						}
					}
				}

				//Check Time for promotion starts
				if(!self.isValidPromotion(s)) {
					addPromotion = false;
				}
				//Check Time for promotion ends

				if (addPromotion) {
					promotions.push(s);
				}
			});
			return promotions;
		},
		isValidPromotion: function(s) {
			if(s.startTime !== null || s.endTime !== null) {
				var timezone = _s("timezone");
				var startTimeArr = s.startTime.split(":");
				var endTimeArr = s.endTime.split(":");
				var startTime = moment.utc(new Date()).tz(timezone.tz).set({
					'hour': startTimeArr[0],
					'minute': startTimeArr[1],
					'second': startTimeArr[2],
				});
				var endTime = moment.utc(new Date()).tz(timezone.tz).set({
					'hour': endTimeArr[0],
					'minute': endTimeArr[1],
					'second': endTimeArr[2],
				});
				return moment.utc(new Date()).tz(timezone.tz).isBetween(startTime, endTime);
			}
			return true;
		},
		getBasicPromotions: function () {
			var self = this;
			var promotions = [];
			this.masters.promotions.forEach(function (s) {
				if (s.offerType === "basic" && self.isValidPromotion(s)) {
					promotions.push(s);
				}
			});
			return promotions;
		},
		getAdvancePromotions: function () {
			var self = this;
			var promotions = [];
			this.masters.promotions.forEach(function (s) {
				if (s.offerType !== "basic" && self.isValidPromotion(s)) {
					promotions.push(s);
				}
			});
			return promotions;
		},
		getBasicPromotionTotal: function () {
			var self = this;
			var total = 0;
			if (this.cart.items.length) {
				var basicPromotions = this.getBasicPromotions();
				if (basicPromotions.length) {
					basicPromotions.forEach(function (bp) {
						var addBp = false;
						var reward = bp.reward;
						var productType = reward.productType;
						self.cart.items.forEach(function (item, index) {
							if (productType === "all") {
								addBp = true;
								total += Number(
									self.getDiscountAmount(
										item,
										bp.reward.discountType,
										bp.reward.discountValue,
										true,
									),
								);
							} else if (productType === "include") {
								if (self.hasPromoItem(item.itemId, reward.include)) {
									addBp = true;
									total += Number(
										self.getDiscountAmount(
											item,
											bp.reward.discountType,
											bp.reward.discountValue,
											true,
										),
									);
								}
							}
						});
						if (addBp) {
							if (self.order.promotions.applied.indexOf(bp.id) === -1) {
								self.order.promotions.applied.push(bp.id);
							}
						}
					});
				}
			}
			return total;
		},
		getAdvancePromotionTotal: function () {
			var self = this;
			var total = 0;
			if (this.cart.items.length) {
				var cartItems = JSON.parse(JSON.stringify(this.cart.items));
				var advancePromotions = this.getAdvancePromotions();
				if (advancePromotions.length) {
					advancePromotions.forEach(function (ap) {
						var criteria = ap.criteria;
						var reward = ap.reward;
						var criteriaProductType = criteria.productType;
						var rewardProductType = reward.productType;
						var criteriaMatches = false;
						var rewardMatches = false;
						var criteriaQuantity = 0;
						var rewardQuantity = 0;
						cartItems.forEach(function (item) {
							if (criteria) {
								if (criteriaProductType === "include") {
									if (self.hasPromoItem(item.itemId, criteria.include)) {
										criteriaMatches = true;
										criteriaQuantity += Number(item.quantity);
									}
								}
							}
							if (reward) {
								if (rewardProductType === "include") {
									if (self.hasPromoItem(item.itemId, reward.include)) {
										rewardMatches = true;
										var rewardTotal = Number(
											self.getDiscountAmount(
												item,
												ap.reward.discountType,
												ap.reward.discountValue,
											),
										);
										rewardQuantity += Number(item.quantity);
										total += Number(rewardTotal);
									}
								}
							}
						});
						if (criteriaMatches && rewardMatches) {
							if (criteriaQuantity > 0 && rewardQuantity > 0) {
								var qty =
									criteriaQuantity > rewardQuantity
										? rewardQuantity
										: criteriaQuantity;
								total = Number(total) * Number(qty);
							}
							if (self.order.promotions.applied.indexOf(ap.id) === -1) {
								self.order.promotions.applied.push(ap.id);
							}
						}
					});
				}
			}
			return total;
		},
		hasPromoItem: function (itemId, obj) {
			var index = obj.findIndex(function (s) {
				return s.itemId === itemId;
			});
			return index !== -1;
		},
		showPromoDialog: function () {
			bus.$emit("showPromoDialog", this.order.promotions);
		},
		updatePromotions: function () {
			var self = this;
			return new Promise(async function(resolve) {
				//TODO this function will be responsible for adding auto promotions
				var availablePromotions = await self.getApplicablePromotions();
				self.cart.totals.promotionTotal = 0;
				var promotionTotal = 0;

				var basicTotal = self.getBasicPromotionTotal();
				promotionTotal = Number(promotionTotal) + Number(basicTotal);

				var advanceTotal = self.getAdvancePromotionTotal();
				promotionTotal += Number(advanceTotal);

				self.order.promotions.available = availablePromotions;
				self.cart.totals.promotionTotal = promotionTotal;
				resolve(true);
			});
		},
		getApplicablePromotions: function () {
			var self = this;
			var availablePromotions = [];
			this.cart.items.forEach(function (i) {
				var itemId = i.itemId;

				var itemAvailablePromotions = self.getAvailablePromotions(itemId);
				if (itemAvailablePromotions.length) {
					itemAvailablePromotions.forEach(function (ip) {
						if (availablePromotions.indexOf(ip) === -1) {
							availablePromotions.push(ip);
						}
					});
				}
			});
			return availablePromotions;
		},
	},
	created: function () {
		var self = this;
		bus.$on("cacheItemsLoaded", function (payload) {
			self.masters.cachedItems = payload;
		});
	},
};
var cloverPaymentMixin = {
	data: function () {
		return {
			deviceId: _s("deviceId"),
			remoteApplicationId: _s("remoteApplicationId"),
			merchant_id: _s("merchant_id"),
			access_token: _s("access_token"),
			targetCloverDomain: _s("targetCloverDomain"),
			friendlyId: _s("friendlyId"),
			cloverConnector: null,
			cloverTipSuggestions: _s("cloverTipSuggestions"),
			cloverTipPercentage: _s("cloverTipPercentage"),
			cloverPaymentObj: {},
		};
	},
	computed: {},
	methods: {
		handleCloverConnect: function () {
			var cloverConnectorFactoryConfiguration = {};
			cloverConnectorFactoryConfiguration[
				clover.CloverConnectorFactoryBuilder.FACTORY_VERSION
			] = clover.CloverConnectorFactoryBuilder.VERSION_12;
			var cloverConnectorFactory =
				clover.CloverConnectorFactoryBuilder.createICloverConnectorFactory(
					cloverConnectorFactoryConfiguration,
				);

			const configBuilder =
				new clover.WebSocketCloudCloverDeviceConfigurationBuilder(
					this.remoteApplicationId,
					this.deviceId,
					this.merchant_id,
					this.access_token,
				);
			configBuilder.setCloverServer(this.targetCloverDomain);
			configBuilder.setFriendlyId(this.friendlyId);
			var cloudConfig = configBuilder.build();

			this.cloverConnector =
				cloverConnectorFactory.createICloverConnector(cloudConfig);
			this.setCloverConnectorListener();
			this.setDisposalHandler();
			this.cloverConnector.initializeConnection();
		},
		setCloverConnectorListener: function () {
			var self = this;
			var CloverConnectorListener = function (connector) {
				clover.remotepay.ICloverConnectorListener();
				self.cloverConnector = connector;
			};
			CloverConnectorListener.prototype = Object.create(
				clover.remotepay.ICloverConnectorListener.prototype,
			);
			CloverConnectorListener.prototype.constructor = CloverConnectorListener;
			CloverConnectorListener.prototype.onDeviceConnected = function () {
				ds_alert("Device is connected!");
			};

			CloverConnectorListener.prototype.onDeviceReady = function () {
				ds_alert("Device is connected and ready!");
			};

			CloverConnectorListener.prototype.onDeviceError = function (
				deviceErrorEvent,
			) {
				window.alert(`Message: ${deviceErrorEvent.getMessage()}`);
			};

			CloverConnectorListener.prototype.onDeviceDisconnected = function () {
				ds_alert("Device is disconnected!");
			};

			this.cloverConnectorListener = new CloverConnectorListener(
				self.cloverConnector,
			);
			self.cloverConnector.addCloverConnectorListener(
				this.cloverConnectorListener,
			);
			CloverConnectorListener.prototype.onVerifySignatureRequest = function (
				verifySignatureRequest,
			) {
				// Clear any previous signatures and draw the current signature.
				var canvas = document.getElementById("verify-signature-canvas");
				var ctx = canvas.getContext("2d");
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				ctx.scale(0.25, 0.25);
				ctx.beginPath();
				for (
					var strokeIndex = 0;
					strokeIndex < verifySignatureRequest.getSignature().strokes.length;
					strokeIndex
				) {
					var stroke =
						verifySignatureRequest.getSignature().strokes[strokeIndex];
					ctx.moveTo(stroke.points[0].x, stroke.points[0].y);
					for (
						var pointIndex = 1;
						pointIndex < stroke.points.length;
						pointIndex
					) {
						ctx.lineTo(
							stroke.points[pointIndex].x,
							stroke.points[pointIndex].y,
						);
						ctx.stroke();
					}
				}
				ctx.scale(4, 4);
				setTimeout(
					function () {
						if (confirm("Would you like to approve this signature?")) {
							// Accept or reject, based on the merchant's input.
							self.cloverConnector.acceptSignature(verifySignatureRequest);
						} else {
							self.cloverConnector.rejectSignature(verifySignatureRequest);
						}
					}.bind(this),
					0,
				);
			};

			CloverConnectorListener.prototype.onConfirmPaymentRequest = function (
				confirmPaymentRequest,
			) {
				for (var i = 0; i < confirmPaymentRequest.getChallenges().length; i++) {
					// Boolean of whether the app is resolving the last challenge in the Challenges array
					var isLastChallenge =
						i === confirmPaymentRequest.getChallenges().length - 1;

					if (confirm(confirmPaymentRequest.getChallenges()[i].getMessage())) {
						if (isLastChallenge) {
							self.cloverConnector.acceptPayment(
								confirmPaymentRequest.getPayment(),
							);
						}
					} else {
						self.cloverConnector.rejectPayment(
							confirmPaymentRequest.getPayment(),
							confirmPaymentRequest.getChallenges()[i],
						);
						return;
					}
				}
			};
			CloverConnectorListener.prototype.onSaleResponse = function (
				saleResponse,
			) {
				if (saleResponse.getSuccess()) {
					var saleResponseAmount = saleResponse.getPayment().getAmount();
					var saleResponseObj = saleResponse.getPayment();
					ds_alert(
						"Sale was successful for $" + saleResponseAmount * 100,
						"info",
					);
					bus.$emit(
						"cloverPaymentClose",
						JSON.parse(JSON.stringify(saleResponseObj)),
					);
				} else {
					ds_alert(saleResponse.getMessage(), "warning");
					bus.$emit("cloverPaymentClose");
				}
			};
			CloverConnectorListener.prototype.onRefundPaymentResponse = function (
				refundResponse,
			) {
				if (refundResponse.getSuccess()) {
					bus.$emit(
						"cloverRefundPaymentClose",
						JSON.parse(JSON.stringify(refundResponse)),
					);
				} else {
					console.log("Not refund");
				}
			};
		},
		setDisposalHandler: function () {
			window.onbeforeunload = function (event) {
				try {
					this.cloverConnector.dispose();
				} catch (e) {
					console.error(e);
				}
			}.bind(this);
		},
		performSale: function (amount) {
			var saleRequest = new clover.remotepay.SaleRequest();
			var tipSuggestions = [];
			var self = this;
			if (this.cloverTipSuggestions.length) {
				var tipSuggestion = null;
				self.cloverTipSuggestions.forEach(function (tip) {
					tipSuggestion = new clover.sdk.merchant.TipSuggestion();
					if (self.cloverTipPercentage) {
						tipSuggestion.setPercentage(tip.value);
					} else {
						tipSuggestion.setAmount(tip.value);
					}
					tipSuggestion.setIsEnabled(true);
					tipSuggestion.setName(tip.title);
					tipSuggestions.push(tipSuggestion);
				});
			}

			saleRequest.setTipSuggestions(tipSuggestions);
			saleRequest.setTipMode("ON_SCREEN_BEFORE_PAYMENT");
			saleRequest.setAmount(amount);
			saleRequest.setExternalId(clover.CloverID.getNewId());
			window.localStorage.setItem("lastTransactionRequestAmount", amount);
			this.cloverConnector.sale(saleRequest);
		},
		makeFullRefund: function () {
			var refundPaymentRequest = new clover.remotepay.RefundPaymentRequest();
			refundPaymentRequest.setPaymentId(this.cloverPaymentObj.id);
			refundPaymentRequest.setOrderId(this.cloverPaymentObj.order.id);
			refundPaymentRequest.setFullRefund(true);
			this.cloverConnector.refundPayment(refundPaymentRequest);
		},
	},
	mounted: function () {
		if (_s("allowCloverPayment")) {
			this.handleCloverConnect();
		}
	},
	created: function () {
		//this.handleCloverConnect();
		var self = this;
		/* bus.$on("initCloverConnect", function (payload) {
			self.handleCloverConnect();
		}); */
		bus.$on("cloverRefundPayment", function (payload) {
			self.cloverPaymentObj = payload;
			self.makeFullRefund();
		});
	},
};
Vue.component("clover-payment", {
	template: "#clover-payment-template",
	data: function () {
		return {
			modal: {
				id: "clover-payment-modal",
				isLoading: false,
				active: false,
			},
			cloverPaymentMessage: _s("cloverPaymentMessage"),
		};
	},
	computed: {},
	watch: {},
	methods: {
		onInitCloverPayment: function () {
			this.showDialog();
			this.startCloverPaymentLoader();
		},
		startCloverPaymentLoader() {
			this.modal.isLoading = true;
			Codebase.blocks("#clover-payment-block", "state_loading");
			bus.$emit("posBusyStart", true);
		},
		stopCloverPaymentLoader() {
			this.modal.isLoading = false;
			Codebase.blocks("#clover-payment-block", "state_normal");
		},
		closeCloverPaymentModal: function () {
			this.stopCloverPaymentLoader();
			this.hideDialog();
			bus.$emit("posBusyStop", true);
		},
	},
	created: function () {
		var self = this;
		bus.$on("initCloverPayment", function (payload) {
			self.onInitCloverPayment(payload);
		});
		bus.$on("cloverPaymentClose", function (payload) {
			self.closeCloverPaymentModal(payload);
		});
	},
});
Vue.component("promotion-dialog", {
	template: "#promotion-dialog-template",
	mixins: [promotionMixin],
	props: ["order"],
	data: function () {
		return {
			modal: {
				id: "promotion-dialog-modal",
				title: "Available Promotions",
			},
			spi: 0,
			sp: {},
			promotions: {
				available: [],
				applied: [],
			},
		};
	},
	methods: {
		initPromoDialog: function (payload) {
			this.promotions = payload;
			this.selectSp(0);
			this.showDialog();
		},
		selectSp: function (i) {
			this.spi = i;
			this.sp = this.promotions.available[this.spi];
		},
	},
	created: function () {
		var self = this;
		bus.$on("showPromoDialog", function (payload) {
			self.initPromoDialog(payload);
		});
	},
});
Vue.component("cart", {
	template: "#cart-template",
	mixins: [promotionMixin],
	props: [
		"cart",
		"orderType",
		"isPaymentAllowed",
		"isEditable",
		"order",
		"allowGratuity",
		"isTabletMode",
	],
	data: function () {
		return {
			activeListType: "cart-table-view",
			customers: [],
			discountAllowed: true,
			isHoldAllowed: false,
			orderPlacingProgress: false,
			enableSplitOrders: _s("enableSplitOrders"),
			gratuityPersons: _s("gratuityPersons"),
			allowGratuityChange: _s("allowGratuityChange"),
		};
	},
	watch: {
		"cart.totals.freightTotal": {
			handler: function (newValue, oldValue) {
				if (isNaN(newValue)) {
					this.cart.totals.freightTotal = oldValue;
				}
				this.updateTotals();
			},
			deep: true,
		},
		orderType: {
			handler: function (newValue, oldValue) {
				if (newValue === "p") {
					this.cart.totals.freightTotal = 0;
				}
				this.updateTotals();
			},
			deep: false,
		},
		"cart.items": {
			handler: function (values, oldValues) {
				this.updateTotals();
			},
			deep: true,
		},
	},
	computed: {
		displayGratuity: function () {
			return (
				this.allowGratuity &&
				this.order.seatUsed >= this.gratuityPersons &&
				this.orderType === "dine"
			);
		},
		orderPlacing: function () {
			return this.orderPlacingProgress === true;
		},
		grandTotal: function () {
			return this.cart.totals.grandTotal;
		},
		hasCartItems: function () {
			return this.cart.items.length > 0;
		},
		canPay: function () {
			var orderStatuses = ["Draft", "Confirmed"];
			orderStatuses.indexOf(this.order.orderStatus);
			return (
				!this.hasCartItems ||
				orderStatuses.indexOf(this.order.orderStatus) === -1 ||
				this.orderPlacing ||
				this.order.splitType !== "none"
			);
		},
		canSplit: function () {
			var minSplitAmount = _s("minSplitAmount");
			var orderStatuses = ["Draft", "Confirmed"];
			return (
				!this.hasCartItems ||
				orderStatuses.indexOf(this.order.orderStatus) === -1 ||
				this.orderPlacing ||
				this.grandTotal < minSplitAmount
			);
		},
		canHold: function () {
			return !!(this.hasCartItems && this.isHoldAllowed && !this.orderPlacing);
		},
	},
	methods: {
		getClass: function () {
			var innerHeight = window.innerHeight;
			if (innerHeight >= 937) {
				return "mh-450p mnh-450p";
			} else if (innerHeight > 768 && innerHeight < 937) {
				return "mh-325p mnh-325p";
			} else {
				return "mh-263p mnh-263p";
			}
		},
		updateTotals: async function () {
			await this.updatePromotions();

			var self = this;
			var subTotal = 0;
			var taxableTotal = 0;
			self.cart.items.forEach(function (item, index) {
				self.cart.items[index].quantity = Math.ceil(
					Number(self.cart.items[index].quantity),
				);
				if (item.taxable === "1") {
					taxableTotal = Number(
						Number(taxableTotal) + Number(item.quantity) * Number(item.rate),
					).toFixed(2);
				}
				subTotal = Number(
					Number(subTotal) + Number(item.quantity) * Number(item.rate),
				).toFixed(2);
			});
			var deliveryTotal = self.cart.totals.freightTotal;
			var promotionTotal = this.cart.totals.promotionTotal;
			var mixTotal = Number(taxableTotal) + Number(deliveryTotal);
			mixTotal -= Number(promotionTotal);
			mixTotal -= Number(self.cart.totals.discount);
			gratuityTotal = 0;
			if (self.allowGratuity) {
				if (
					self.order.tableId &&
					Number(self.order.seatUsed) >= Number(self.gratuityPersons)
				) {
					gratuityTotal =
						(Number(subTotal) * Number(self.cart.totals.gratuityRate)) / 100;
				}
			}
			var taxTotal =
				(Number(mixTotal + gratuityTotal) * Number(self.cart.totals.taxRate)) /
				Number(100);
			mixTotal =
				Number(mixTotal + gratuityTotal) +
				(Number(subTotal) - Number(taxableTotal));

			self.cart.totals.subTotal = subTotal;
			self.cart.totals.taxTotal = taxTotal;
			self.cart.totals.gratuityTotal = gratuityTotal;
			self.cart.totals.grandTotal = Number(
				Number(mixTotal) + Number(taxTotal),
			).toFixed(2);
		},
		handlePayment: function () {
			var self = this;
			if (self.isValidOrder()) {
				//if (self.cart.totals.grandTotal > 0) {
				bus.$emit("payBoxInit", true);
			}
		},
		isValidOrder: function () {
			var validOrder = true;

			if (Number(this.cart.totals.grandTotal) > 0) {
				validOrder = true;
			}
			if (this.order.type === "p" && _s("pickupContactMandatory")) {
				var customer_id = this.order.customer.id;
				if (!customer_id) {
					validOrder = false;
					ds_alert("Customer is mandatory for Pick up", "warning");
				}
			}
			return validOrder;
		},
		handleOpenDiscountDialog: function () {
			if (this.cart.items.length > 0 || this.isEditable) {
				bus.$emit("initDiscountDialog", true);
			}
		},
		handleOpenGratuityDialog: function () {
			if (this.cart.items.length > 0 || this.isEditable) {
				bus.$emit("initGratuityDialog", true);
			}
		},
		handleClearDiscount: function () {
			this.cart.totals.discount = 0;
			this.cart.totals.discountValue = "";
			this.cart.totals.discountType = "p";
			this.updateTotals();
		},
		handleUpdateOrder: function () {
			if (this.isEditable) {
				this.order.print = true;
				bus.$emit("saveOrder", true);
			} else {
				bus.$emit("printOrder", true);
			}
		},
		handleTabletOrder: function () {
			if (this.isEditable) {
				bus.$emit("saveOrder", { addToPrintQueue: true });
			}
		},
		handlePutOnHold: function () {
			bus.$emit("saveAsDraft", true);
		},
		handleResetOrder: function () {
			bus.$emit("resetOrder", true);
		},
		handleSplitOrder: function () {
			this.order.print = false;
			//bus.$emit("initSplitOrder", true);
			bus.$emit("saveOrder", { saveAndLoad: true, loadSplitDialog: true });
		},
		handleAddToCart: function (item) {
			var existingItemIndex = this.getExistingItemIndex(item);

			if (existingItemIndex === -1) {
				var cartItem = {
					id: null,
					orderItemId: this.generateOrderItemId(item.id),
					type: item.type,
					itemId: item.id,
					unitId: item.unitId,
					taxable: item.taxable,
					quantity: item.quantity,
					unitQuantity: item.unitQuantity,
					title: item.title,
					orderItemNotes: item.orderItemNotes,
					printLocation: item.printLocation,
					hasSpiceLevel: item.hasSpiceLevel,
					isPriceEditable: item.isPriceEditable,
					isNameEditable: item.isNameEditable,
					spiceLevel: item.spiceLevel,
					addons: item.addons,
					rate: item.rate,
					selectedNotes: item.selectedNotes,
					parentId: item.parentId,
					data: item,
				};
				/*  if (!item.variations.length) {
                     cartItem.rate = item.rate;
                 } else {
                     cartItem.rate = cartItem.unitRate = item.rate;
                 } */
				this.cart.items.push(cartItem);
			} else {
				this.cart.items[existingItemIndex].quantity += Number(1);
			}
		},
		allowHold: function () {
			this.isHoldAllowed = false;
			if (this.isEditable) {
				if (!this.orderPlacingProgress) {
					if (
						this.cart.items.length < 1 ||
						this.order.orderStatus === "Preparing" ||
						this.order.orderStatus === "Ready" ||
						this.order.orderStatus === "Closed"
					) {
						this.isHoldAllowed = false;
					} else {
						this.isHoldAllowed = true;
					}
				}
			}
		},
		getExistingItemIndex: function (item) {
			var itemId = item.id;
			if (Number(itemId) === Number(_s("openItemId"))) {
				return -1;
			} else {
				var spiceLevel = item.spiceLevel;
				var enabledAddons = item.addons.filter(function (ea) {
					return ea.enabled === true;
				});
				var enabledAddonIds = [];
				enabledAddons.forEach(function (ea) {
					enabledAddonIds.push({
						id: ea.itemId,
						qty: ea.quantity,
					});
				});
				return this.cart.items.findIndex(function (i) {
					var existingEnabledAddons = i.addons.filter(function (ea) {
						return ea.enabled === true;
					});
					var existingEnabledAddonIds = [];
					existingEnabledAddons.forEach(function (ea) {
						existingEnabledAddonIds.push({
							id: ea.itemId,
							qty: ea.quantity,
						});
					});
					return (
						i.itemId === itemId &&
						i.spiceLevel === spiceLevel &&
						JSON.stringify(existingEnabledAddonIds) ===
							JSON.stringify(enabledAddonIds) &&
						JSON.stringify(item.selectedNotes) ===
							JSON.stringify(i.selectedNotes) &&
						item.orderItemNotes === i.orderItemNotes
					);
				});
			}
		},
		getExistingItem: function (itemId) {
			return this.cart.items.find(function (item) {
				return item.itemId === itemId;
			});
		},
		handleRemoveItem: function (index) {
			this.cart.items.splice(index, 1);
		},
	},
	created: function () {
		var self = this;
		bus.$on("addToCart", function (item) {
			self.handleAddToCart(item);
			self.allowHold();
		});
		bus.$on("posBusyStart", function (payload) {
			self.orderPlacingProgress = true;
		});
		bus.$on("posBusyStop", function (payload) {
			self.orderPlacingProgress = false;
		});
		bus.$on("existingOrderLoaded", function () {
			self.allowHold();
		});
		bus.$on("updateCartTotal", function (payload) {
			self.updateTotals();
		});
	},
});
Vue.component("cart-edit-item", {
	template: "#cart-edit-item-template",
	props: ["items", "isEditable"],
	data: function () {
		return {
			modal: {
				title: null,
			},
			masters: {
				spiceLevels: _s("spiceLevels"),
			},
			itemIndex: -1,
			isPriceEditable: false,
			isNameEditable: false,
			obj: {
				hasSpiceLevel: false,
				orderItemNotes: "",
				spiceLevel: _s("defaultSpiceLevel"),
				rate: 0,
				title: "",
				addons: [],
				notes: [],
				selectedNotes: [],
			},
			showError: false,
			message: "",
			originalPrice: 0,
			itemId: null,
		};
	},
	watch: {
		"obj.addons": {
			handler: function (after, before) {
				this.calculatePrice();
			},
			deep: true,
		},
		"obj.rate": function (newRate, oldRate) {
			if (isNaN(newRate)) {
				this.obj.rate = oldRate;
			}
		},
	},
	computed: {
		getButtonTitle: function () {
			return _s("currencySign") + " " + Number(this.obj.rate).toFixed(2);
		},
		getGroupedAddons: function () {
			var addons = [];
			this.obj.addons.forEach(function (a) {
				var index = addons.findIndex(function (fa) {
					return a.itemId === fa.itemId;
				});
				if (index === -1) {
					addons.push(a);
				}
			});
			return addons;
		},
	},
	methods: {
		calculatePrice: function () {
			var selectedAddons = this.obj.addons.filter(function (single) {
				return single.enabled;
			});
			var addonPrice = 0;
			addonPrice = selectedAddons.reduce(function (total, selectedAddon) {
				if (selectedAddon) {
					return (
						Number(total) +
						Number(selectedAddon.rate) * Number(selectedAddon.quantity)
					);
				}
				return Number(total);
			}, addonPrice);
			if (Number(this.itemId) === Number(_s("openItemId"))) {
				var rate = this.obj.rate;
			} else {
				var rate = this.originalPrice;
			}
			this.obj.rate = Number(rate) + Number(addonPrice);
		},
		handleCartItemEditInit: function () {
			this.obj = {
				hasSpiceLevel: false,
				orderItemNotes: "",
				rate: 0,
				spiceLevel: _s("defaultSpiceLevel"),
				title: "",
				addons: [],
				notes: [],
				selectedNotes: [],
			};
			var item = JSON.parse(JSON.stringify(this.items[this.itemIndex]));
			var itemId = item.itemId;
			this.itemId = itemId;
			this.isPriceEditable = Number(itemId) === Number(_s("openItemId"));
			this.isNameEditable = Number(itemId) === Number(_s("openItemId"));
			this.obj.hasSpiceLevel = item.hasSpiceLevel;
			this.obj.addons = item.addons;
			this.obj.rate = item.rate;
			var variation = item.data.variations.find(function (i) {
				return itemId === i.id;
			});
			this.originalPrice = variation.originalPrice;
			this.obj.title = item.title;
			this.obj.notes = item.data.notes;
			this.obj.selectedNotes = item.selectedNotes;
			this.obj.orderItemNotes = item.orderItemNotes;
			if (typeof item.spiceLevel !== "undefined") {
				this.obj.spiceLevel = item.spiceLevel;
			}
			this.modal.title = "Edit Cart Item (" + item.title + ")";
			this.handleModalOpen();
		},
		handleModalOpen: function () {
			this.$bvModal.show("cart-edit-item-modal");
		},
		handleModalClose: function () {
			this.$bvModal.hide("cart-edit-item-modal");
		},
		handleConfirm: function () {
			var rate = this.obj.rate;
			if (isNaN(rate) || rate === "") {
				this.initError("Amount is invalid");
				return false;
			}

			var item = this.items[this.itemIndex];

			item.spiceLevel = this.obj.spiceLevel;
			item.addons = this.obj.addons;
			item.selectedNotes = this.obj.selectedNotes;
			item.orderItemNotes = this.obj.orderItemNotes;
			//if (this.isPriceEditable) {
			item.rate = this.obj.rate;
			//}
			if (this.isNameEditable) {
				item.title = this.obj.title;
			}

			this.handleModalClose();
		},
		initError: function (message) {
			var self = this;
			this.message = message;
			this.showError = true;
			setTimeout(function () {
				self.hideError();
			}, 3000);
		},
		hideError: function () {
			this.message = "";
			this.showError = false;
		},
		handleIncrement: function (addonItemId) {
			this.obj.addons.forEach(function (ia) {
				if (ia.itemId === addonItemId) {
					ia.quantity++;
				}
			});
			//this.item.addons[index].quantity++;
		},
		handleDecrement: function (addonItemId) {
			this.obj.addons.forEach(function (ia) {
				if (ia.itemId === addonItemId) {
					if (ia.quantity > 1) {
						ia.quantity--;
					} else {
						ia.quantity = 1;
						ia.enabled = false;
					}
				}
			});
		},
		resetValues: function () {
			this.originalPrice = 0;
			this.itemId = null;
			this.showError = false;
			this.message = "";
			this.itemIndex = -1;
			this.isPriceEditable = false;
			this.isNameEditable = false;
		},
		updateAddonSelection: function (e) {
			var itemId = e.target.getAttribute("data-item-id");
			var self = this;
			this.obj.addons.forEach(function (ia, index) {
				if (itemId === ia.itemId) {
					self.obj.addons[index].enabled = e.target.checked;
				}
			});
			var selectedAddons = JSON.parse(
				JSON.stringify(
					this.obj.addons.filter(function (sa) {
						return sa.enabled === true;
					}),
				),
			);
			selectedAddons.forEach(function (sa) {
				self.obj.addons.forEach(function (a) {
					if (a.itemId === sa.itemId) {
						a.enabled = true;
					}
				});
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("initItemEdit", function (index) {
			self.resetValues();
			self.itemIndex = index;
			self.handleCartItemEditInit();
		});
	},
});
Vue.component("cart-accordian-view", {
	template: "#cart-accordian-view-template",
	props: ["cart", "isEditable"],
});
Vue.component("cart-table-view", {
	template: "#cart-table-view-template",
	props: ["cart", "isEditable", "orderMode"],
	computed: {
		cartItems: function () {
			return this.cart.items.filter(function (i) {
				return Number(i.quantity) > 0;
			});
		},
	},
	methods: {
		hasAddons: function (addons) {
			var has = false;
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						has = true;
					}
				});
			}
			return has;
		},
		getAddons: function (addons) {
			var string = "";
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						if (string !== "") {
							string += ", " + addon.title;
						} else {
							string += addon.title;
						}
					}
				});
			}
			return string;
		},
		getNotes: function (notes) {
			if (typeof notes === "object") {
				var string = "";
				if (notes.length) {
					notes.forEach(function (note) {
						if (string !== "") {
							string += ", " + note.title;
						} else {
							string += note.title;
						}
					});
				}
				return string;
			} else {
				return notes;
			}
		},
		handleEditItem: function (index) {
			bus.$emit("initItemEdit", index);
		},
		getAmount: function (rate, quantity) {
			return Number(rate) * Number(quantity);
		},
		handleIncrement: function (index) {
			this.cartItems[index].quantity++;
		},
		handleDecrement: function (index) {
			var self = this;
			var item = self.cartItems[index];
			var minQty = null;
			var editMode = false;
			if (!_s("allowVoidItem")) {
				if (typeof item.originalQty !== "undefined") {
					minQty = item.originalQty;
					editMode = true;
				} else {
					minQty = 1;
				}
				if (self.cartItems[index].quantity > minQty) {
					self.cartItems[index].quantity--;
				} else {
					if (!editMode) {
						self.cartItems.splice(index, 1);
					}
				}
			} else {
				if (self.cartItems[index].quantity > 1) {
					self.cartItems[index].quantity--;
				} else {
					if (self.cartItems[index].id === null) {
						var itemIdx = self.cart.items.findIndex(function (s) {
							return Number(s.itemId) === Number(item.itemId);
						});
						if (itemIdx !== -1) {
							self.cart.items.splice(itemIdx, 1);
						}
					} else {
						self.cartItems[index].quantity = 0;
					}
				}
			}
		},
	},
});
Vue.component("item-list", {
	template: "#item-template",
	props: ["cart", "isEditable"],
	data: function () {
		return {
			module: "pos",
			activeListType: "item-list-view",
			items: [],
			categories: [],
			categoryId: "",
			filteredItems: [],
			itemSearchString: "",
			itemCaching: _s("itemCaching"),
			showItemSearch: _s("showItemSearch"),
			showItemDisplayType: _s("showItemDisplayType"),
			preloadCacheItems: _s("preloadCacheItems"),
			cachedItems: [],
			icons: [],
		};
	},
	watch: {
		itemSearchString: function (newValue, oldValue) {
			this.categoryId = "";
			if (newValue !== "") {
				this.filteredItems = this.items.filter(function (item) {
					var searchTags = item.tags.toLowerCase();
					return searchTags.indexOf(newValue.toLowerCase()) !== -1;
				});
			} else {
				this.filteredItems = JSON.parse(JSON.stringify(this.items));
			}
		},
	},
	methods: {
		getClass: function () {
			var innerHeight = window.innerHeight;
			if (innerHeight >= 937) {
				return "mh-730p";
			} else if (innerHeight > 768 && innerHeight < 937) {
				return "mh-650p";
			} else {
				return "mh-475p";
			}
		},
		handlePreloadCacheItems: function () {
			var self = this;
			if (self.items.length) {
				var ids = [];
				self.items.forEach(function (single) {
					ids.push(single.id);
				});
				if (ids.length) {
					var data = {
						module: self.module,
						method: "cache_items",
						ids: ids,
					};
					var request = submitRequest(data, "get");
					request.then(function (response) {
						if (response.status === "ok") {
							self.cachedItems = response.cacheItems;
							bus.$emit("cacheItemsLoaded", response.cacheItems);
						}
					});
				}
			}
		},
		populateMeta: function (type) {
			Codebase.blocks("#product-container", "state_loading");
			var self = this;
			var data = {
				module: self.module,
				method: "populate_items",
				category: "",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					if (type !== "refresh") {
						self.categories = response.categories;
						self.icons = response.icons;
					}
					self.items = response.items;
					self.filterCategoryItems();
					if (self.preloadCacheItems) {
						self.handlePreloadCacheItems();
					}
				}
				Codebase.blocks("#item-container", "state_normal");
			});
		},
		filterCategoryItems: function () {
			Codebase.blocks("#product-container", "state_loading");
			var self = this;
			if (!self.items.length) {
				self.populateMeta("refresh");
				/*var data = {
                    module: self.module,
                    method: 'category_items',
                    category: this.categoryId
                };
                var request = submitRequest(data, 'get');
                request.then(function (response) {
                    if (response.status === 'ok') {
                        self.items = response.items;
                        Codebase.blocks('#product-container', 'state_normal');
                    }
                });*/
			} else {
				if (self.categoryId !== "") {
					self.filteredItems = self.items.filter(function (item) {
						return item.categoryId === self.categoryId;
					});
				} else {
					self.filteredItems = JSON.parse(JSON.stringify(self.items));
				}
			}
		},
		handleChangeCategory: function (id) {
			this.categoryId = id;
			this.filterCategoryItems();
		},
		handleItemClick: function (index) {
			if (this.isEditable) {
				var self = this;
				Codebase.blocks("#item-container", "state_loading");
				var item = this.filteredItems[index];

				var existingItemIndex = this.cart.items.findIndex(function (single) {
					return single.itemId === item.id && single.type === "product";
				});

				if (existingItemIndex !== -1) {
					var existingItem = this.cart.items[existingItemIndex];
					if (
						existingItem.data.addons.length ||
						existingItem.data.hasSpiceLevel ||
						existingItem.data.notes.length
					) {
						existingItemIndex = -1;
					}
				}

				//if item not found in the cart
				if (existingItemIndex === -1) {
					var cachedItem = false;
					if (this.itemCaching) {
						var cachedItemIndex = this.cachedItems.findIndex(function (single) {
							return single.id === item.id;
						});
						if (cachedItemIndex !== -1) {
							cachedItem = this.cachedItems[cachedItemIndex];
							this.emitItemLoaded(cachedItem);
							Codebase.blocks("#item-container", "state_normal");
							return true;
						}
					}

					var data = {
						module: this.module,
						method: "single_item",
						id: item.id,
					};
					var request = submitRequest(data, "get");
					request.then(function (response) {
						if (response.status === "ok") {
							var details = response.obj;
							self.cachedItems.push(details);
							self.emitItemLoaded(details);
						}
						Codebase.blocks("#item-container", "state_normal");
					});
				} else {
					var existingItem = this.cart.items[existingItemIndex];
					if (
						!existingItem.data.addons.length &&
						!existingItem.data.notes.length
					) {
						if (existingItem.type === "product") {
							this.cart.items[existingItemIndex].quantity =
								Number(this.cart.items[existingItemIndex].quantity) + 1;
						}
					} else {
						bus.$emit("initGroupItemDetails", existingItem);
					}
					Codebase.blocks("#item-container", "state_normal");
				}
			}
		},
		emitItemLoaded: function (details) {
			var self = this;
			//TODO 1==1 means get global settings for popup
			//if (details.type === 'group' || details.addons.length || details.notes.length) {
			if (
				details.variations.length ||
				details.hasSpiceLevel ||
				details.addons.length ||
				details.notes.length
			) {
				bus.$emit("initGroupItemDetails", details);
			} else if (1 === 1 && self.hasMultipleUnits(details)) {
				bus.$emit("initItemDetails", details);
			} else {
				bus.$emit("addToCart", details);
			}
		},
		hasMultipleUnits: function (item) {
			if (item.saleUnit === item.unit) {
				return false;
			} else {
				return true;
			}
		},
		handleItemCountUpdate: function (newItemCount) {
			if (this.items.length !== newItemCount) {
				this.populateMeta();
			}
		},
		handleClearSearch: function () {
			this.itemSearchString = "";
		},
	},
	created: function () {
		var self = this;
		bus.$on("itemCountUpdate", function (newItemCount) {
			self.handleItemCountUpdate(newItemCount);
		});
		//self.populateMeta();
	},
});
Vue.component("item-thumb-view", {
	template: "#item-thumb-view-template",
	props: ["filteredItems", "isEditable"],
	methods: {
		handleItemClick: function (index) {
			this.$emit("itemSelected", index);
		},
	},
});
Vue.component("item-list-view", {
	template: "#item-list-view-template",
	props: ["filteredItems", "isEditable", "cachedItems", "icons"],
	data: function () {
		return {
			showItemVegNVeg: _s("showItemVegNVeg"),
			showItemIcons: _s("showItemIcons"),
		};
	},
	methods: {
		hasVegNVeg: function (id, type) {
			var value = "1";
			if (type === "nveg") {
				value = "0";
			}
			var item = this.cachedItems.find(function (i) {
				return id === i.id;
			});
			if (typeof item !== "undefined") {
				if (!item.variations.length) {
					return item.isVeg === value;
				} else {
					var vegItems = item.variations.filter(function (itm) {
						return itm.isVeg === value;
					});
					return vegItems.length > 0;
				}
			}
			return false;
		},
		handleItemClick: function (index) {
			this.$emit("itemSelected", index);
		},
	},
});
Vue.component("item-detail", {
	template: "#item-detail-template",
	props: ["isEditable"],
	data: function () {
		return {
			module: "pos",
			item: {},
			units: [],
			features: [],
		};
	},
	methods: {
		rateCalculation: function (type) {
			var self = this;

			var unitPrice = this.item.prices.find(function (price) {
				return price.unitId === self.item.saleUnit;
			});

			var price = this.item.prices.find(function (price) {
				return price.unitId === self.item.unit;
			});

			this.item.unitRate = Number(unitPrice.salePrice);
			this.item.rate = Number(price.salePrice);
			this.item.unitQuantity = Number(this.item.unitQuantity);

			if (type === "unitQuantity") {
				var qtyInBaseUnit =
					Number(this.item.unitQuantity) / Number(unitPrice.conversionRate);
				this.item.quantity = Math.ceil(qtyInBaseUnit);
				this.item.unitQuantity = this.item.unitQuantity.toFixed(3);
			} else if (type === "quantity") {
				this.item.quantity = Math.ceil(this.item.quantity);
				var qtyInSaleUnit =
					Number(this.item.quantity) * Number(unitPrice.conversionRate);
				this.item.unitQuantity = qtyInSaleUnit.toFixed(3);
			}
		},
		handleItemDetailInit: function () {
			this.populateMeta();
			this.$bvModal.show("item-detail-modal");
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate_item_detail",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.features = response.features;
					self.units = response.units;
				}
				self.rateCalculation("quantity");
			});
		},
		getFeatureLabel: function (id) {
			if (this.features.length) {
				var feature = this.features.find(function (single) {
					return single.id === id;
				});
				return feature ? feature.value : "";
			}
			return "";
		},
		getUnitLabel: function (id) {
			if (this.units.length) {
				var unit = this.units.find(function (single) {
					return single.id === id;
				});
				return unit ? unit.value : "";
			}
			return "";
		},
		handleAddToCart: function () {
			bus.$emit("addToCart", this.item);
			this.$bvModal.hide("item-detail-modal");
		},
	},
	created: function () {
		var self = this;
		bus.$on("initItemDetails", function (obj) {
			self.item = obj;
			self.handleItemDetailInit();
		});
	},
});
Vue.component("group-item-detail", {
	template: "#group-item-detail-template",
	props: ["isEditable"],
	data: function () {
		return {
			module: "pos",
			masters: {
				spiceLevels: _s("spiceLevels"),
			},
			item: {
				addons: [],
				notes: [],
				variations: [],
			},
			customPrice: 0,
			isNameEditable: false,
			isPriceEditable: false,
		};
	},
	watch: {
		"item.addons": {
			handler: function (after, before) {
				this.calculatePrice();
			},
			deep: true,
		},
		customPrice: {
			handler: function (after, before) {
				this.item.variations[0].rate = after;
			},
		},
	},
	computed: {
		getGroupedAddons: function () {
			var addons = [];
			this.item.addons.forEach(function (a) {
				var index = addons.findIndex(function (fa) {
					return a.itemId === fa.itemId;
				});
				if (index === -1) {
					addons.push(a);
				}
			});
			return addons;
		},
	},
	methods: {
		calculatePrice: function () {
			var selectedAddons = this.item.addons.filter(function (single) {
				return single.enabled;
			});
			this.item.variations.forEach(function (variation) {
				var addonPrice = 0;
				addonPrice = selectedAddons.reduce(function (total, selectedAddon) {
					if (Number(selectedAddon.parent) === Number(variation.parent)) {
						return (
							Number(total) +
							Number(selectedAddon.rate) * Number(selectedAddon.quantity)
						);
					}
					return Number(total);
				}, addonPrice);

				variation.salePrice =
					Number(variation.originalPrice) + Number(addonPrice);
			});
		},
		handleGroupItemDetailInit: function () {
			if (this.item.variations.length) {
				if (typeof this.item.variations === "undefined") {
					this.item.variations = this.item.data.variations;
				}
				if (typeof this.item.baseName === "undefined") {
					this.item.baseName = this.item.title;
				}
				this.item.variations.forEach(function (single) {
					single.originalPrice = single.rate;
					single.parentId = single.parent;
				});
			} else {
				this.item.variations = [];
				var variation = {
					isVeg: this.item.isVeg,
					id: this.item.id,
					originalPrice: this.item.rate,
					salePrice: this.item.rate,
					title: this.item.title,
					unitId: this.item.unit,
					parent: this.item.id,
					type: this.item.type,
				};
				this.item.variations.push(variation);
				this.item.baseName = this.item.title;
				this.customPrice = variation.salePrice;
				this.isPriceEditable = this.isNameEditable = this.isOpenItem(
					this.item.id,
				);
			}
			this.calculatePrice();
			this.handleModalOpen();
		},
		handleAddToCart: function (index) {
			var variation = this.item.variations[index];
			this.item.id = variation.id;
			this.item.rate = variation.salePrice;
			/*  this.item.addons.forEach(function(ai) {
                   if (Number(ai.itemId) !== 0) {
                      if (ai.itemId !== variation.parent) {
                          ai.enabled = false;
                      }
                  }
             }); */
			if (variation.type === "variant") {
				this.item.title = this.item.baseName + " - " + variation.title;
			} else {
				this.item.title = this.item.baseName;
			}
			if (this.isOpenItem(this.item.id)) {
				this.item.rate = this.customPrice;
			}
			if (this.isOpenItem(this.item.id)) {
				this.item.title = this.item.baseName;
			}
			this.item.parentId = variation.parentId;
			this.item.type = variation.type;
			bus.$emit("addToCart", this.item);
			this.handleModalClose();
		},
		getButtonTitle: function (variant) {
			return (
				variant.title +
				" - " +
				_s("currencySign") +
				" " +
				Number(variant.salePrice).toFixed(2)
			);
		},
		handleModalOpen: function () {
			this.$bvModal.show("group-item-detail-modal");
		},
		handleModalClose: function () {
			this.$bvModal.hide("group-item-detail-modal");
		},
		handleIncrement: function (id) {
			this.item.addons.forEach(function (ia) {
				if (ia.itemId === id) {
					ia.quantity++;
				}
			});
			//this.item.addons[index].quantity++;
		},
		handleDecrement: function (id) {
			this.item.addons.forEach(function (ia) {
				if (ia.itemId === id) {
					if (ia.quantity > 1) {
						ia.quantity--;
					} else {
						ia.quantity = 1;
						ia.enabled = false;
					}
				}
			});
		},
		updateAddonSelection: function (e) {
			var itemId = e.target.getAttribute("data-item-id");
			var self = this;
			this.item.addons.forEach(function (ia, index) {
				if (itemId === ia.itemId) {
					self.item.addons[index].enabled = e.target.checked;
				}
			});
			var selectedAddons = JSON.parse(
				JSON.stringify(
					this.item.addons.filter(function (sa) {
						return sa.enabled === true;
					}),
				),
			);
			selectedAddons.forEach(function (sa) {
				self.item.addons.forEach(function (a) {
					if (a.itemId === sa.itemId) {
						a.enabled = true;
					}
				});
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("initGroupItemDetails", function (obj) {
			self.item = JSON.parse(JSON.stringify(obj));
			self.handleGroupItemDetailInit();
		});
	},
	mounted: function () {},
});
Vue.component("customer", {
	template: "#customer-template",
	props: ["customer", "isEditable", "cart"],
	data: function () {
		return {
			selectedCustomer: {},
			queryCustomers: [],
			searchString: "",
			isLoading: false,
			allowCustomerGroup: _s("allowCustomerGroup"),
		};
	},
	computed: {
		discountApplyBtn: function () {
			return (
				this.cart.items.length > 0 &&
				this.isEditable &&
				Number(this.cart.totals.discount) === 0
			);
		},
		isCustomerLoaded: function () {
			var loaded = false;
			if (typeof this.customer !== "undefined") {
				loaded = typeof this.customer.id !== "undefined";
			}
			return loaded;
		},
		customerLoaded: function () {
			var loaded = false;
			if (typeof this.customer !== "undefined") {
				loaded = typeof this.customer.id !== "undefined";
			}
			if (this.customer.displayName == "Walk-in") {
				loaded = false;
			}
			return loaded;
		},
		addressList: function () {
			return this.customer.addresses;
		},
		getClassCustomer: function () {
			var color = "";
			if (this.customer.notes != "" && _s("allowCustomerNotes")) {
				color = "flash-icon";
			}
			return color;
		},
	},
	methods: {
		handleDiscountDialog: function () {
			var self = this;
			self.cart.totals.discountType = "p";
			self.cart.totals.discountValue = self.customer.group.posDiscount;
			bus.$emit("initDiscountDialog", true);
		},
		queryCustomer: function (query) {
			if (query.length > 2) {
				this.searchString = query;
				this.isLoading = true;
				var self = this;

				var data = {
					module: "contacts/customers",
					method: "query",
					query: query,
					compact: true,
				};
				var request = submitRequest(data, "get");
				request.then(function (response) {
					self.queryCustomers = response.customers;
					self.isLoading = false;
				});
			}
		},
		getDetails: function (selected) {
			var self = this;
			var customer = selected;
			var data = {
				id: customer.id,
				module: "contacts/customers",
				method: "single",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					bus.$emit("onCustomerSelect", response.obj);
				}
			});
		},
		handleNewCustomer: function () {
			bus.$emit("initAddCustomer", { string: this.searchString });
			this.searchString = "";
		},
		handleInfoCustomer: function () {
			bus.$emit("initInfoCustomer", this.customer.id);
		},
		clearSearch: function () {
			this.selectedCustomer = {};
			this.queryCustomers = [];
			this.searchString = "";
			this.isLoading = false;
			bus.$emit("clearCustomer", true);
		},
	},
	created: function () {
		var self = this;
		bus.$on("resetOrder", function () {
			self.clearSearch();
		});
	},
});
Vue.component("order-detail", {
	template: "#order-detail-template",
	props: ["orderId"],
	data: function () {
		return {
			order: {
				cart: {
					items: [],
					totals: [],
				},
			},
		};
	},
	watch: {
		orderId: function (newValue, oldValue) {
			if (newValue !== null) {
				this.populateOrder();
			}
		},
	},
	methods: {
		hasAddons: function (addons) {
			var has = false;
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						has = true;
					}
				});
			}
			return has;
		},
		getAddons: function (addons) {
			var string = "";
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						if (string !== "") {
							string += ", " + addon.title;
						} else {
							string += addon.title;
						}
					}
				});
			}
			return string;
		},
		getNotes: function (notes) {
			if (typeof notes === "object") {
				var string = "";
				if (notes.length) {
					notes.forEach(function (note) {
						if (string !== "") {
							string += ", " + note.title;
						} else {
							string += note.title;
						}
					});
				}
				return string;
			} else {
				return notes;
			}
		},
		populateOrder: function () {
			var self = this;
			var data = {
				module: "pos",
				method: "order_load",
				id: self.orderId,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.order = response.obj;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
	},
	created: function () {},
});
Vue.component("online-order-detail", {
	template: "#online-order-detail-template",
	props: ["session", "register"],
	data: function () {
		return {
			modal: {
				id: "online-order-detail-modal",
			},
			orderId: null,
		};
	},
	methods: {
		initOnlineOrderDetail: function () {
			this.handleOpenModal(this.modal.id);
		},
		handleAcceptOnlineOrder: function () {
			if (this.orderId !== null) {
				var self = this;
				var data = {
					module: "pos",
					method: "accept_online_order",
					id: this.orderId,
					sessionId: this.session,
					registerId: this.register,
				};
				var request = submitRequest(data, "post");
				request.then(function (response) {
					if (response.status === "ok") {
						bus.$emit("updateOnlineOrderList", true);
						bus.$emit("printOrder", { id: self.orderId });
						self.handleCloseModal(self.modal.id);
					}
				});
			}
		},
	},
	created: function () {
		var self = this;
		bus.$on("initOnlineOrderDetail", function (payload) {
			self.orderId = payload.id;
			self.initOnlineOrderDetail();
		});
		this.$root.$on("bv::modal::hide", function (bvEvent, modalId) {
			if (modalId === self.modal.id) {
				self.orderId = null;
			}
		});
	},
});
Vue.component("add-customer", {
	template: "#add-customer-template",
	props: ["mode", "isEditable"],
	mixins: [customerCustomFieldsMixin],
	data: function () {
		return {
			module: "contacts/customers",
			modal: {
				id: "add-customer-modal",
			},
			masters: {
				countries: [],
				states: [],
				groups: [],
				cities: [],
			},
			priceLists: [],
			paymentTerms: [],
			customer: {
				address: {},
			},
			metaDataLoaded: false,
			initialBillingStateLoad: false,
			initialShippingStateLoad: false,
			limitedDisplayMode: _s("limitedContactDisplay"),
			autofillField: _s("customerAutofillSearchField"),
			allowCustomerGroup: _s("allowCustomerGroup"),
			allowCustomerNotes: _s("allowCustomerNotes"),
		};
	},
	computed: {
		isLimitedDisplayMode: function () {
			return this.limitedDisplayMode;
		},
	},
	watch: {
		"customer.address.stateId": {
			handler: function (after, before) {
				if (after !== "" && before !== after) {
					this.populateCites(after);
				}
			},
			deep: true,
		},
	},
	methods: {
		populateMeta: async function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.countries = response.countries;
				self.masters.states = response.states;
				self.masters.groups = response.groups;
				self.customer.customerId = response.newCustomerId;
				self.metaDataLoaded = true;
			}
			return true;
		},
		populateCites: function (id) {
			var self = this;
			var data = {
				state_id: id,
				country_id: this.customer.address.countryId,
				module: "core/cities",
				method: "select_data",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.masters.cities = response.cities;
				}
			});
		},
		onCompanyName: function () {
			if (this.customer.displayName === "") {
				this.customer.displayName = this.customer.companyName;
			}
		},
		onName: function () {
			if (
				this.customer.displayName.trim() === "" ||
				this.customer.displayName.trim() === this.customer.firstName.trim() ||
				this.customer.displayName.trim() === this.customer.lastName.trim()
			) {
				this.customer.displayName =
					this.customer.firstName.trim() + " " + this.customer.lastName.trim();
			}
		},
		handleSubmit: function () {
			var error = false;
			var self = this;
			var form = $("#frm-add-customer");

			if (!form.parsley().validate()) {
				error = true;
			}
			if (this.customer.email.length) {
				if (this.isEmailDuplicate()) {
					var email_field = this.$refs.email;
					$(email_field)
						.parsley()
						.addError("email_duplicate", { message: "Email already exists." });
					error = true;
				}
			}
			if (this.customer.phone.length) {
				if (this.isPhoneDuplicate()) {
					var phone_field = this.$refs.phone;
					$(phone_field)
						.parsley()
						.addError("phone_duplicate", { message: "Mobile already exists." });
					error = true;
				}
			}

			if (!error) {
				var method = "";
				if (this.mode === "add") {
					method = "put";
				} else if (this.mode === "edit") {
					method = "post";
				}

				var data = {
					module: this.module,
					obj: this.customer,
				};
				if (method) {
					var request = submitRequest(data, method);
					request.then(function (response) {
						if (response.status === "ok") {
							bus.$emit("newCustomerAdded", response.customer);
							self.hideDialog();
						}
					});
				} else {
					alert("Something went wrong!");
				}
			}
		},
		blankCustomerObj: function () {
			this.customer = {
				customerId: "",
				groupId: 1,
				firstName: "",
				lastName: "",
				displayName: "",
				email: "",
				phone: "",
				memberNumber: "",
				fullVaccinated: 0,
				notes: "",
				address: {
					id: "",
					title: "",
					address1: "",
					address2: "",
					cityId: _s("defaultCityId") ? _s("defaultCityId") : null,
					stateId: _s("defaultStateId") ? _s("defaultStateId") : null,
					zipCode: "",
					countryId: _s("defaultCountryId") ? _s("defaultCountryId") : null,
					customerId: "",
				},
				status: 1,
				defaultAddressId: "",
			};
		},
		handleCancel: function () {
			this.blankCustomerObj();
			this.hideDialog();
			bus.$emit("onNewCustomerCancel", true);
			//this.$emit('addCustomerCancel','');
		},
		onInitAddCustomer: function (payload) {
			this.blankCustomerObj();
			var string = payload.string;
			if (typeof this.customer[this.autofillField] !== "undefined") {
				this.customer[this.autofillField] = string;
			}
			this.populateMeta();
			this.showDialog();
		},
		isEmailDuplicate: function () {
			var result = false;
			var string = this.customer.email;
			var field = this.$refs.email;
			$(field).parsley().removeError("email_duplicate");
			var url =
				_s("action") +
				"?module=" +
				this.module +
				"&method=duplicate_email&email=" +
				string;

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.status === 200) {
					var response = JSON.parse(this.responseText);
					result = response.result;
				}
			};
			xhttp.open("GET", url, false);
			xhttp.send();
			return result;
		},
		isPhoneDuplicate: function () {
			var result = false;
			var string = this.customer.phone;
			var field = this.$refs.phone;
			$(field).parsley().removeError("phone_duplicate");
			var url =
				_s("action") +
				"?module=" +
				this.module +
				"&method=duplicate_phone&phone=" +
				string;

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.status === 200) {
					var response = JSON.parse(this.responseText);
					result = response.result;
				}
			};
			xhttp.open("GET", url, false);
			xhttp.send();
			return result;
		},
	},
	created: function () {
		var self = this;
		bus.$on("initAddCustomer", function (payload) {
			self.onInitAddCustomer(payload);
		});
		bus.$on("resetAddCustomerMetaData", function (payload) {
			self.masters = {
				countries: [],
				states: [],
				groups: [],
				cities: [],
			};
			self.metaDataLoaded = false;
		});
		this.customerCustomFields = _s("customerCustomFields");
	},
});
Vue.component("payment", {
	template: "#payment-template",
	props: ["order", "isEditable"],
	mixins: [cloverPaymentMixin],
	data: function () {
		return {
			module: "pos",
			modal: {
				id: "payment-modal",
				isLoading: false,
				active: false,
			},
			paymentMethods: [],
			message: "",
			showError: false,
			errorTimeout: 3000,
			enableExtOrderNo: _s("enableExtOrderNo"),
			paymentBtnDisabled: false,
			confirmBtnDisabled: true,
			tipConverted: false,
			inputElement: document.getElementById("input-amount"),
			payment: {},
			split: null,
			printers: [],
			total: {
				change: 0,
				discount: 0,
				discountType: "p",
				discountValue: "",
				freightTotal: 0,
				grandTotal: 0,
				payments: [],
				subTotal: 0,
				taxRate: _s("defaultTaxRate"),
				taxTotal: 0,
				tip: 0,
			},
			allowCardPaymentChange: _s("allowCardPaymentChange"),
		};
	},
	computed: {
		isTipAllow: function () {
			var result = true;
			if (!this.allowCardPaymentChange) {
				var payments = this.total.payments.filter(function (s) {
					return s.cash !== true;
				});
				result = payments.length < 1;
				/* this.total.payments.forEach(function (p) {
                	if (
                		Number(p.paymentMethodId) === Number(_s("posCardPaymentMethodId"))
                	) {
                		result = false;
                	}
                }); */
			}
			return Number(this.total.tip) > 0 && result;
		},
		visibleTip: function () {
			if (!this.tipConverted) {
				return this.hasCashTransaction();
			}
			return false;
		},
		canConvertToTip: function () {
			var changeAmount = this.getChangeAmount();
			var tip = this.total.tip;
			return (
				Number(this.getTotalPaid()) > Number(changeAmount) &&
				Number(changeAmount) > Number(tip)
			);
		},
		canRemoveTip: function () {
			return this.hasCashTransaction();
		},
		isSplitPayment: function () {
			return this.order.splitType !== "none";
		},
		getPaymentMethods: function () {
			if (this.split === null) {
				return this.paymentMethods;
			} else {
				return this.paymentMethods.filter(function (s) {
					return s.type === "manual";
				});
			}
		},
		isLoading: function () {
			return this.modal.isLoading === true;
		},
		isActive: function () {
			return this.modal.active === true;
		},
	},
	watch: {
		"total.tip": {
			handler: function (after, before) {
				var totalPaid = Number(this.getTotalPaid());
				var grandTotal = Number(this.total.grandTotal);
				if (totalPaid > 0 && totalPaid > grandTotal) {
					if (after > this.getChangeAmount()) {
						this.total.tip = before;
					}
					this.total.change = Math.abs(
						Number(this.getChangeAmount()) - Number(this.total.tip),
					);
				}
			},
			deep: true,
		},
	},
	methods: {
		getOutstanding:function(){
			return Number(this.total.grandTotal) - Number(this.getTotalPaid());
		},
		getPrinters: function () {
			var printer = [];
			var selectedPrinter = this.printers.filter(function (p) {
				return p.selected === true;
			});
			selectedPrinter.forEach(function (p) {
				printer.push(p.value);
			});
			return printer;
		},
		punchChar: function (char) {
			if (char === "." && this.payment.amount.search(char) === -1) {
				if (this.payment.amount.length === 0) {
					this.payment.amount = 0;
				}
				this.payment.amount += char;
			} else {
				this.payment.amount += char;
			}
		},
		removeChar: function () {
			var str = this.payment.amount;
			this.payment.amount = str.substring(0, str.length - 1);
		},
		clearChars: function () {
			this.payment.amount = "";
		},
		hasCashTransaction: function () {
			if (this.total.payments.length) {
				var payments = this.total.payments.findIndex(function (payment) {
					return payment.cash === true;
				});
				return payments !== -1;
			}
			return false;
		},
		handlePayBoxInit: function () {
			this.populateMeta();
			this.$bvModal.show("payment-modal");
			this.modal.active = true;
		},
		handleFullPayment: function () {
			var paidTotal = this.getTotalPaid();
			this.payment.amount = this.total.grandTotal - paidTotal;
			this.handlePayment();
		},
		handlePayment: function () {
			this.clearMessage();
			if (!this.payment.amount || isNaN(this.payment.amount)) {
				this.setMessage("Invalid amount");
				this.payment.amount = "";
			} else if (!this.payment.paymentMethodId) {
				this.setMessage("Please select payment method first");
				this.payment.amount = "";
			} else {
				var existingPaymentIndex = this.getExistingPaymentIndex(
					this.payment.paymentMethodId,
				);
				if (existingPaymentIndex !== -1) {
					var existingAmount = this.total.payments[existingPaymentIndex].amount;
					this.total.payments[existingPaymentIndex].amount =
						Number(this.payment.amount) + Number(existingAmount);
				} else {
					this.total.payments.push(this.payment);
				}
				this.updateChangeAmount();

				var grandTotal = this.total.grandTotal;
				if (
					!this.hasCashTransaction() &&
					Number(this.getTotalPaid()) > Number(grandTotal)
				) {
					this.handleConvertToTip();
				}
				this.resetPaymentObj();
			}
		},
		handleConvertToTip: function () {
			this.tipConverted = true;
			this.total.tip = this.getChangeAmount();
			this.total.change = 0;
		},
		reverseTip: function () {
			this.tipConverted = false;
			this.total.tip = 0;
			this.total.change = 0;
			this.updateChangeAmount();
		},
		handleRemovePayment: function (index) {
			var toBeRemoved = this.total.payments[index];
			var paymentMethod = this.paymentMethods.find(function (p) {
				return p.id === toBeRemoved.paymentMethodId;
			});
			this.clearAutoDiscount(paymentMethod);
			this.total.payments.splice(index, 1);
			this.order.sourceId = _s("posSourceId");
			this.reverseTip();
			this.paymentBtnUpdate();
			this.confirmBtnUpdate();
			//this.updateChangeAmount();
		},
		updatePaymentMethod: function (index) {
			var self = this;
			var paymentMethod = this.paymentMethods[index];
			this.payment.cash = paymentMethod.cash;
			this.applyAutoDiscount(paymentMethod);
			this.order.sourceId = paymentMethod.sourceId;
			if (paymentMethod.autofill === true) {
				setTimeout(function () {
					self.handleFullPayment();
				}, 100);
			}
		},
		applyAutoKitchenPrint:function(boolean){
            var printer = this.printers.find(function(p){
                return p.value ==='kitchen'
            })
            printer.selected = boolean;
            return true;
        },
		applyAutoDiscount: function (paymentMethod) {
			if (Number(paymentMethod.autoDiscountValue) > 0) {
				var autoDiscountValue = Number(paymentMethod.autoDiscountValue);
				var mixTotal =
					Number(this.order.cart.totals.subTotal) -
					Number(this.order.cart.totals.promotionTotal);
				var discount = Number(
					(autoDiscountValue * Number(mixTotal)) / 100,
				).toFixed(2);

				this.order.cart.totals.discountType = "p";
				this.order.cart.totals.discountValue = autoDiscountValue;
				this.order.cart.totals.discount = discount;
				if(_s('defaultKitchenPrintInAutoDiscount')){
                    this.applyAutoKitchenPrint(true);
                }
				bus.$emit("updateCartTotal", true);
				return true;
			}
		},
		clearAutoDiscount: function (paymentMethod) {
			if (Number(paymentMethod.autoDiscountValue) > 0) {
				this.order.cart.totals.discountType = "p";
				this.order.cart.totals.discountValue = 0;
				this.order.cart.totals.discount = 0;
				if(_s('defaultKitchenPrintInAutoDiscount')){
                    this.applyAutoKitchenPrint(false);
                }
				bus.$emit("updateCartTotal", true);
				return true;
			}
		},
		getExistingPaymentIndex: function (methodId) {
			return this.total.payments.findIndex(function (payment) {
				return payment.paymentMethodId === methodId;
			});
		},
		getTotalPaid: function () {
			var totalPaid = this.total.payments.reduce(function (total, payment) {
				return Number(total) + Number(payment.amount);
			}, 0);
			return Number(totalPaid).toFixed(2);
		},
		getTotalCashPaid: function () {
			var totalCashPaid = this.total.payments.reduce(function (total, payment) {
				if (payment.cash === true) {
					return Number(total) + Number(payment.amount);
				}
				return Number(total);
			}, 0);
			return Number(totalCashPaid).toFixed(2);
		},
		clearMessage: function () {
			this.message = "";
			this.showError = false;
		},
		paymentBtnUpdate: function () {
			var totalPaid = this.getTotalPaid();
			this.paymentBtnDisabled =
				Number(totalPaid) >= Number(this.total.grandTotal);
		},
		confirmBtnUpdate: function () {
			var totalPaid = this.getTotalPaid();
			this.confirmBtnDisabled =
				Number(totalPaid) < Number(this.total.grandTotal);
		},
		setMessage: function (message) {
			var self = this;
			self.message = message;
			self.showError = true;
			setTimeout(function () {
				self.showError = false;
			}, self.errorTimeout);
		},
		resetPaymentObj: function () {
			this.payment = {
				paymentMethodId: "",
				cash: false,
				amount: "",
			};
			this.paymentBtnUpdate();
			this.confirmBtnUpdate();
			this.updateChangeAmount();
		},
		updateChangeAmount: function () {
			var totalPaid = this.getTotalPaid();
			this.total.change = 0;
			if (totalPaid > 0) {
				var change = Number(totalPaid) - Number(this.total.grandTotal);
				if (change >= 0) {
					this.total.change = Math.abs(change);
				}
			}
		},
		getChangeAmount: function () {
			return Math.abs(
				Number(this.total.grandTotal) - Number(this.getTotalPaid()),
			).toFixed(2);
		},
		getPaymentMethod: function (id) {
			if (this.paymentMethods.length) {
				var paymentMethod = this.paymentMethods.find(function (method) {
					return Number(method.id) === Number(id);
				});
				return paymentMethod.value;
			}
		},
		populateMeta: function () {
			if (this.paymentMethods.length === 0) {
				var self = this;
				var data = {
					module: self.module,
					method: "populate_payment",
				};
				var request = submitRequest(data, "get");
				request.then(function (response) {
					if (response.status === "ok") {
						self.paymentMethods = response.paymentMethods;
					}
				});
			}
			this.resetPaymentObj();
		},
		handleConfirmClose: function () {
			this.order.close = true;
			this.handleConfirm(true);
		},
		handleConfirm: function (close) {
			var self = this;
			if (typeof close === "undefined") {
				close = false;
			}
			this.order.close = close === true;
			var totalPaid = Number(this.getTotalPaid());
			var grandTotal = Number(this.total.grandTotal);
			var result = false;
			if (this.order.splitType === "none") {
				if (_s("allowCloverPayment") && this.checkAnyCardPayment()) {
					if (ds_confirm("Do you want clover payment")) {
						this.handleCloverPayment();
					} else {
						bus.$emit("saveOrder", { directPrint: self.getPrinters() });
					}
				} else {
					if (totalPaid === 0) {
						result = ds_confirm("Continue without Payment?");
						if (result === true) {
							this.order.close = false;
							bus.$emit("saveOrder", true);
						}
					} else if (totalPaid < grandTotal) {
						result = ds_confirm("Continue with Partial Payment?");
						if (result === true) {
							this.order.close = false;
							bus.$emit("saveOrder", true);
						}
					} else {
						bus.$emit("saveOrder", { directPrint: self.getPrinters() });
					}
				}
			} else {
				if (_s("allowCloverPayment") && this.checkAnyCardPayment()) {
					this.handleCloverPayment();
				} else {
					this.handleSplitPayment();
				}
			}
		},
		handleCloverPayment: function () {
			var amount = this.total.grandTotal - this.getTotalCashPaid();
			var cloverAmount = 100 * Number(amount);
			this.performSale(cloverAmount);
			bus.$emit("initCloverPayment", true);
		},
		handleModalHidden: function () {
			this.modal.active = false;
			this.stopPaymentLoader();
			this.handleCloseModal(this.modal.id);
			this.printers = [];
		},
		startPaymentLoader() {
			this.modal.isLoading = true;
			Codebase.blocks("#payment-modal-block", "state_loading");
		},
		stopPaymentLoader() {
			this.modal.isLoading = false;
			Codebase.blocks("#payment-modal-block", "state_normal");
		},
		checkAnyCardPayment: function () {
			var result = false;
			if (this.paymentMethods.length) {
				this.total.payments.forEach(function (p) {
					if (Number(p.paymentMethodId) === _s("cardPaymentId")) {
						result = true;
					}
				});
			}
			return result;
		},
		cloverPaymentDone: function (payload) {
			if (typeof payload !== "undefined") {
				var tipAmount = payload.tipAmount / 100;
				this.order.cart.totals.tip = this.order.cart.totals.tip + tipAmount;
				bus.$emit("saveOrder", {
					directPrint: this.getPrinters(),
					cloverPayment: payload,
				});
			} else {
				ds_alert("Something went to Wrong..", "Warning");
			}
		},
		handleSplitPayment: function (cloverPayment) {
			if (typeof cloverPayment === "undefined") {
				cloverPayment = null;
			}
			var self = this;
			var payments = JSON.parse(JSON.stringify(this.total.payments));
			payments.forEach(function (p) {
				p.changeTotal = p.cash === true ? self.total.change : 0;
				p.tipTotal = p.cash === false ? self.total.tip : 0;
			});
			var data = {
				module: this.module,
				method: "update_split_payment",
				split_id: this.split.id,
				payments: payments,
				order_id: this.order.id,
				cloverPayment: cloverPayment,
			};
			var request = submitRequest(data, "post");
			request.then(function (res) {
				bus.$emit("splitPaymentCompleted",{split:res.split , printers: self.getPrinters()});
				self.handleModalHidden();
			});
		},
		getCashierPrinter:function(){
			return {
				selected: _s("defaultCashierPrint"),
				value: "cashier",
				title: "Cashier",
			}
		},
		getKitchenPrinter:function(){
			return {
				selected: _s("defaultKitchenPrint"),
				value: "kitchen",
				title: "Kitchen",
			}
		}
	},
	created: function () {
		var self = this;
		bus.$on("posBusyStart", function (param) {
			if (self.isActive) {
				self.startPaymentLoader();
			}
		});
		bus.$on("posBusyStop", function (param) {
			self.stopPaymentLoader();
		});
		bus.$on("payBoxInit", function (payload) {
			self.split = null;
			if (self.order.splitType !== "none") {
				self.split = payload.split;
				self.printers.push(self.getCashierPrinter());
			}else{
				self.printers.push(self.getCashierPrinter());
				self.printers.push(self.getKitchenPrinter());
			}
			if (typeof payload.totals !== "undefined") {
				self.total = payload.totals;
			} else {
				self.total = self.order.cart.totals;
			}
			self.handlePayBoxInit();
		});
		bus.$on("cloverPaymentClose", function (payload) {
			if (self.order.splitType !== "none") {
				self.handleSplitPayment(payload);
			} else {
				self.cloverPaymentDone(payload);
			}
		});
	},
});
Vue.component("order-history", {
	template: "#order-history-template",
	props: ["session", "isTabletMode", "employeeId","registerId"],
	data: function () {
		return {
			modalOpen: false,
			complexOrderStatus: false,
			orders: [],
			onHoldOrders: [],
			confirmOrders: [],
			preparingOrders: [],
			readyOrders: [],
			closedOrders: [],
			cancelledOrders: [],
			refundedOrders: [],
			empOrders: [],
			allowRefund: _s("allowRefund"),
			empOrderFields: [
				{
					key: "sessionOrderNo",
					label: "Order #",
				},
				{
					key: "type",
					label: "Type",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
				{
					key: "paymentStatus",
					label: "Payment",
					class: "text-center",
				},
				{
					key: "id",
					label: "Action",
					class: "text-center",
				},
			],
			confirmFields: [
				{
					key: "sessionOrderNo",
					label: "Order #",
				},
				{
					key: "type",
					label: "Type",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
				{
					key: "paymentStatus",
					label: "Payment",
					class: "text-center",
				},
				{
					key: "id",
					label: "Action",
					class: "text-center",
				},
			],
			readyFields: [
				{
					key: "sessionOrderNo",
					label: "Order #",
				},
				{
					key: "type",
					label: "Type",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
				{
					key: "paymentStatus",
					label: "Payment",
					class: "text-center",
				},
				{
					key: "id",
					label: "Action",
					class: "text-center",
				},
				{
					key: "closeOrder",
					label: "Close",
					class: "text-center",
				},
			],
			fields: [
				{
					key: "sessionOrderNo",
					label: "Order #",
				},
				{
					key: "type",
					label: "Type",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
				{
					key: "id",
					label: "Action",
					class: "text-center",
				},
			],
			cancelledFields: [
				{
					key: "sessionOrderNo",
					label: "Order #",
				},
				{
					key: "type",
					label: "Type",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
			],
			refundedFields: [
				{
					key: "sessionOrderNo",
					label: "Order #",
				},
				{
					key: "type",
					label: "Type",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
			],
		};
	},
	methods: {
		handleOrderDetails: function (id) {
			bus.$emit("initOrderDetails", { id: id });
		},
		formatCurrency: function (value) {
			return this.$options.filters.beautifyCurrency(value);
		},
		populateMeta: function () {
			Codebase.blocks("#order-history-block", "state_loading");
			var self = this;
			var data = {
				module: "pos",
				method: "orders",
				session_id: self.session,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.orders = response.orders;
				self.onHoldOrders = self.getFilteredOrders("Draft");
				self.confirmOrders = self.getFilteredOrders("Confirmed");
				self.preparingOrders = self.getFilteredOrders("Preparing");
				self.readyOrders = self.getFilteredOrders("Ready");
				self.closedOrders = self.getFilteredOrders("Closed");
				self.cancelledOrders = self.getFilteredOrders("Cancelled");
				self.refundedOrders = self.getFilteredOrders("Refunded");
				self.empOrders = self.getEmpOrders();
				var partialRefund = self.getFilteredOrders("Partial_refunded");
				if (partialRefund.length) {
					partialRefund.forEach(function (p) {
						self.closedOrders.push(p);
					});
				}
				if (self.modalOpen === false) {
					self.$bvModal.show("order-history-modal");
					self.modalOpen = true;
				}
				Codebase.blocks("#order-history-block", "state_normal");
			});
		},
		getFilteredOrders: function (status) {
			return this.orders.filter(function (order) {
				return order.orderStatus === status;
			});
		},
		getEmpOrders: function () {
			var self = this;
			return self.orders.filter(function (order) {
				return Number(order.employeeId) === Number(self.employeeId);
			});
		},
		handleOpenOrder: function (e) {
			bus.$emit("loadExistingOrder", e);
		},
		handelHoldOrderCancel: function (id) {
			if (ds_confirm("Are you sure to cancel this Order?")) {
				this.holdOrderCancel(id);
			}
		},
		handleSetPreparing: function (id) {
			this.handleChangeStatus(id, "Preparing");
		},
		handleSetReady: function (id) {
			this.handleChangeStatus(id, "Ready");
		},
		handleSetClose: function (id) {
			this.handleChangeStatus(id, "Closed");
		},
		handleSetCancelled: function (id) {
			var result = ds_confirm("Are you sure to cancel this Order?");
			if (result === true) {
				this.handleChangeStatus(id, "Cancelled");
			}
		},
		handleSetRefunded: function (id) {
			//this.handleChangeStatus(id, "Refunded");
			//this.handleRefunded(id);
			bus.$emit("initRefunded", { id: id });
		},
		holdOrderCancel: function (id) {
			var self = this;
			var data = {
				module: "pos",
				method: "hold_order_cancel",
				orderId: id,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					self.populateMeta();
					bus.$emit("resetOrder", { orderId: id });
				}
			});
		},
		handleChangeStatus: function (id, status) {
			var self = this;
			var data = {
				module: "pos",
				method: "order_status",
				sessionId: self.session,
				registerId: self.registerId,
				id: id,
				orderStatus: status,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					self.populateMeta();
					bus.$emit("resetOrder", { orderId: id });
				}
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("initOrderHistory", function () {
			self.populateMeta();
		});
		this.$root.$on("bv::modal::hide", function (bvEvent, modalId) {
			if (modalId === "order-history-modal") {
				self.modalOpen = false;
			}
		});
	},
});
Vue.component("online-order-history", {
	template: "#online-order-history-template",
	props: ["orders"],
	data: function () {
		return {
			modal: {
				id: "online-order-history-modal",
			},
			modalOpen: false,
			complexOrderStatus: false,
			confirmFields: [
				{
					key: "orderNo",
					label: "Order #",
				},
				{
					key: "date",
					label: "Order Date",
				},
				{
					key: "billingName",
					label: "Customer",
				},
				{
					key: "grandTotal",
					label: "Total",
					class: "text-right",
				},
				{
					key: "orderStatus",
					label: "Status",
					class: "text-center",
					tdClass: "font-weight-700",
				},
				{
					key: "paymentStatus",
					label: "Payment",
					class: "text-center",
				},
			],
		};
	},
	methods: {
		handleSetClose: function (id) {
			this.handleChangeStatus(id, "Closed");
		},
		handleViewOrderDetails: function (orderId) {
			bus.$emit("initOnlineOrderDetail", { id: orderId });
		},
		handleSetCancelled: function (id) {
			var result = ds_confirm("Are you sure to cancel this Order?");
			if (result === true) {
				this.handleChangeStatus(id, "Cancelled");
			}
		},
		handleChangeStatus: function (id, status) {
			var self = this;
			var data = {
				module: "pos",
				method: "accept_online_order",
				sessionId: self.session,
				id: id,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				bus.$emit("updateOnlineOrderList", true);
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("initOnlineOrderList", function () {
			self.handleOpenModal(self.modal.id);
		});
	},
});
Vue.component("session-summary", {
	template: "#session-summary-template",
	props: ["id", "employeeId", "registerId", "registerSessionId","objectId"],
	data: function () {
		return {
			module: "pos",
			session: {
				registersDetail: [],
			},
			allowRefund: _s("allowRefund"),
			allowGratuity: _s("allowGratuity"),
			allowDiscountInSummary: _s("allowDiscountInSummary"),
			mode: null,
			type: null,
			allowSummaryCashEmployeeTakeOut: _s("allowSummaryCashEmployeeTakeOut"),
			employeeName : '',
			limitedShow :true,
			printers:{
				selected: false,
				value: "summary",
				title: "Print",
			}
		};
	},
	watch: {
		"session.closingCash": {
			handler: function (newValue, oldValue) {
				if (isNaN(newValue) || newValue > this.session.expectedClosingCash) {
					this.session.closingCash = oldValue;
				}
				this.session.takeOut =
					Number(this.session.expectedClosingCash) -
					Number(this.session.closingCash);
			},
		},
	},
	computed: {
		modalId: function () {
			return "session-summary-modal-" + this.objectId;
		},
		isEmployeeType: function () {
			return this.type === "employee";
		},
		isRegisterType: function () {
			return this.type === "register";
		},
		isSessionType: function () {
			return this.type === "session";
		},
		getTitle: function ( ){
			var  title = ''
			if(this.isEmployeeType){
                title = 'Shift'
			}else if(this.isRegisterType){
				title = 'Register'
			}else if(this.isSessionType){
				title = 'Session'
			}
			if(this.limitedShow){
				title = 'Close '+title
			}
			return title;
		},
		showPrinter: function () {
			return this.limitedShow ? (this.isRegisterType || this.isSessionType) : false
		}
	},
	methods: {
		populateMeta: function () {
			var obj = {
				sessionId: this.id,
				employeeId: this.type === "employee" ? this.employeeId : null,
				registerId: this.type === "register" ? this.registerId : null,
				registerSessionId: null, //this.registerSessionId,
				type: this.type,
			};

			Codebase.blocks("#session-summary-block", "state_loading");
			var self = this;
			var data = {
				module: this.module,
				method: "close_session_summary",
				obj: obj,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.session = response.summary;
				}
				Codebase.blocks("#session-summary-block", "state_normal");
			});
		},
		closeModal: function() {
		   // bus.$off("showSessionSummary-"+ this.objectId);
			this.$bvModal.hide('session-summary-modal-' + this.objectId);
		},
		handleCloseRegister: function () {
			var obj = this.prepObject();
			if (this.type !== "register") {
				bus.$emit("closeRegister", obj);
			} else {
				bus.$emit("showUserLogin", { type: "Close" ,obj });
			}
			//bus.$off("showSessionSummary-"+ this.objectId);
		},
		prepObject: function () {
			var closingCash = !isNaN(this.session.closingCash)
				? this.session.closingCash
				: 0;
			return (obj = {
				id: this.session.id,
				closingCash: closingCash,
				takeOut: this.session.takeOut,
				closingNote: this.session.closingNote,
				type: this.type,
				printers:this.printers.selected ? [ this.printers.value ] : [],
			});
		},
		setPrinter:function(){
			if(this.type === "register"){
                this.printers.selected = _s("defaultRegisterPrint");
			}else if(this.type === "session"){
				this.printers.selected = _s("defaultSummaryPrint");
			}
		}
	},
	/* beforeDestroy: function (){
		//bus.$off("showSessionSummary-"+ this.objectId);
	}, */
	created: function () {
		var self = this;
		bus.$on("showSessionSummary-"+ this.objectId, function (payload) {
			self.type = payload.mode;
			self.limitedShow = payload.show;
			if(self.type === 'employee'){
               self.employeeName = localStorage.getItem("employeeName")
			}
			self.setPrinter();
			self.populateMeta();
			self.$bvModal.show("session-summary-modal-" + self.objectId);
		});
		bus.$on("setUserLogin", function (payload) {
			if (payload.type === "Close") {
				var obj = self.prepObject();
				bus.$emit("closeRegister", obj);
			}
		});
	},
});
Vue.component("print-server-dialog", {
	template: "#print-server-dialog-template",
	data: function () {
		return {
			modal: "print-server-dialog-modal",
			printers: [],
			printData: {},
		};
	},
	methods: {
		initComponent: function () {
			this.showDialog();
		},
		initPrintOrderQueue(queue) {
			var self = this;
			bus.$emit('setPrintQueueBusy', true);
			this.printOrderQueue(queue)
			.then(function (res) {
				var data = {
					module: "pos",
					method: "clear_printed_queue",
					queueIds: res,
				};
				return submitRequest(data, "post");
			})
			.finally(function() {
				bus.$emit('setPrintQueueBusy', false);
			});
		},
		printOrderQueue: function (queue) {
			var self = this;
			return new Promise(function (resolve) {
				var printed = [];
				self.printers = ["kitchen"];
				asyncForEach(queue, async (q) => {
					/* queue.forEach(function (q) { */
					self.printData = q.order;
					self.printToServer();
					printed.push(q.id);
				});
				resolve(printed);
			});
		},
		/*printOrderQueue: function (queue) {
			var self = this;
			return new Promise(function (resolve) {
				var printed = [];
				self.printers = ["kitchen"];
				queue.forEach(function (q) {
					self.printData = q.order;
					self.printToServer();
					printed.push(q.id);
				});
				resolve(printed);
			});
		},*/
		printToServer: function () {
			var self = this;
			return new Promise(function (resolve) {
				var data = {
					data: self.printData,
					printers: self.printers,
				};
				$.ajax({
					url: _s("printServerUrl") + "api/print",
					type: "POST",
					crossDomain: true,
					dataType: "json",
					data: data,
					success: function (response) {
						if (self.printers.includes("kitchen")) {
							var updateData = {
								module: "pos",
								method: "set_printed",
								orderId: self.printData.id,
							};
							submitRequest(updateData, "post").then(function (res) {
								resolve(res);
							});
						}
					},
				});
			});
		},
		handleCashierPrinter: function () {
			this.printers = ["cashier"];
			this.printToServer();
			this.hideDialog();
		},
		handleKitchenPrinter: function () {
			this.printers = ["kitchen"];
			this.printToServer();
			this.hideDialog();
		},
		handleBothPrinters: function () {
			this.printers = ["cashier", "kitchen"];
			this.printToServer();
			this.hideDialog();
		},
		handleDirectPrint: function (payload) {
			var printers = payload.directPrint;
			if (typeof printers !== "undefined") {
				this.printData = payload.printData;
				this.printers = printers;
				this.printToServer();
			}
		},
		showDialog: function () {
			this.handleOpenModal(this.modal);
		},
		hideDialog: function () {
			this.handleCloseModal(this.modal);
		},
	},
	/* beforeDestroy: function(){
		bus.$off("initDirectPrint");
		bus.$off("initQueuePrint");
		bus.$off("initPrintServerDialog");
	}, */
	created: function () {
		var self = this;
		bus.$on("initPrintServerDialog", function (payload) {
			self.printData = payload;
			self.initComponent();
		});
		bus.$on("initDirectPrint", function (payload) {
			self.handleDirectPrint(payload);
		});
		bus.$on("initQueuePrint", function (payload) {
			self.initPrintOrderQueue(payload);
		});
	},
});
Vue.component("table-list", {
	template: "#table-list-template",
	props: ["session"],
	data: function () {
		return {
			modal: {
				id: "table-list-modal",
				isOpen: false,
			},
			masters: {
				areas: [],
				tables: [],
			},
			filtered: {
				tables: [],
			},
			listColClass: _s("tableListColClass"),
			areaId: "",
			timerInterval: "",
			tableSelected: false,
			isActive: false,
			allowReleaseTable: _s("allowReleaseTable"),
			mode: null,
			changeTableId: null,
		};
	},
	computed: {
		modelTitle: function () {
			var message = "";
			if (this.mode === "select") {
				message = "Select Table";
			} else if (this.mode === "change") {
				message = "Change Table";
			}
			return message;
		},
	},
	methods: {
		populateMeta: function () {
			Codebase.blocks("#table-list-block", "state_loading");
			var self = this;
			var data = {
				module: "areas",
				method: "populate",
			};
			var request = submitRequest(data, "get");
			request
				.then(function (response) {
					if (response.status === "ok") {
						self.masters.areas = response.areas;
						self.masters.tables = response.tables;
						if (self.masters.areas.length) {
							self.switchArea(self.masters.areas[0].id);
							self.liveDuration();
						}
					}
				})
				.finally(function () {
					Codebase.blocks("#table-list-block", "state_normal");
				});
		},
		switchArea: function (id) {
			this.areaId = id;
			this.filtered.tables = this.masters.tables.filter(function (table) {
				return Number(table.areaId) === Number(id);
			});
		},
		getTable: function (id) {
			return this.masters.tables.filter(function (table) {
				return Number(table.id) === Number(id);
			});
		},
		handleTableSelection: function (table) {
			if (table.status === "available") {
				if (this.mode === "select") {
					bus.$emit("initTableSelection", table);
				} else if (ds_confirm("Are you sure to change table?")) {
					bus.$emit("initTableSelection", table);
				}
			} else if (table.status === "engaged" && this.allowReleaseTable) {
				this.handleTableRelease(table, true);
			}
		},
		handleTableRelease: function (table, type) {
			var result = true;
			if (type) {
				result = confirm("Are you sure to release " + table.title + "?");
			}
			if (result) {
				var self = this;
				var data = {
					module: "areas/tables",
					method: "release",
					tableId: table.id,
					sessionId: table.sessionId,
				};
				var request = submitRequest(data, "post");
				request.then(function (response) {
					if (response.status === "ok") {
						self.populateMeta();
					}
				});
			}
		},
		liveDuration: function () {
			if (this.isActive) {
				clearInterval(this.timerInterval);
				this.masters.tables.forEach(function (table) {
					if (table.status === "engaged") {
						if (moment(table.useSince).isValid()) {
							var duration = moment.duration(moment().diff(table.useSince));
							table.durationSince = moment
								.utc(duration.asMilliseconds())
								.format("HH:mm:ss");
						}
					}
				});
				this.timerInterval = setInterval(this.liveDuration, 1000);
			}
		},
		resetDialog: function () {
			this.isActive = false;
			this.tableSelected = false;
			this.areaId = "";
			this.timerInterval = "";
		},
		handleClosing: function () {
			if (!this.tableSelected && this.mode === "select") {
				bus.$emit("setOrderType", "p");
			}
			this.resetDialog();
		},
	},
	created: function () {
		var self = this;
		bus.$on("initTableList", function (payload) {
			self.populateMeta();
			self.isActive = true;
			self.showDialog();
			self.mode = payload.mode;
			self.changeTableId =
				typeof payload.tableId != "undefined" ? payload.tableId : null;
		});
		bus.$on("tableSelected", function (payload) {
			self.tableSelected = true;
			if (self.mode === "change") {
				var table = self.getTable(self.changeTableId);
				self.handleTableRelease(table[0], false);
			}
			self.hideDialog();
		});
	},
});
Vue.component("table-dialog", {
	template: "#table-dialog-template",
	data: function () {
		return {
			modal: {
				id: "table-dialog-modal",
				isOpen: false,
			},
			table: {},
		};
	},
	methods: {
		punchChar: function (char) {
			if (this.table.seatUsed === "0") {
				this.table.seatUsed = "";
			}
			this.table.seatUsed += char;
		},
		removeChar: function () {
			var str = this.table.seatUsed;
			this.table.seatUsed = str.substring(0, str.length - 1);
		},
		clearChars: function () {
			this.table.seatUsed = "";
		},
		getTableStatus: function (status) {
			return status === "available" ? "Available" : "Engaged";
		},
		handleReserve: function () {
			/* var self = this;
            var data = {
            	module: "areas/tables",
            	method: "reserve",
            	tableId: this.table.id,
            	seatUsed: this.table.seatUsed,
            };
            var request = submitRequest(data, "post");
            request.then(function (response) {
            	if (response.status === "ok") {
            		bus.$emit("tableSelected", {
            			id: self.table.id,
            			seatUsed: self.table.seatUsed,
            		});
            		self.hideDialog();
            	}
            }); */
			bus.$emit("tableSelected", {
				id: this.table.id,
				seatUsed: this.table.seatUsed,
			});
			this.hideDialog();
		},
	},
	created: function () {
		var self = this;
		bus.$on("initTableSelection", function (payload) {
			self.table = payload;
			self.showDialog();
		});
	},
});
Vue.component("discount-dialog", {
	template: "#discount-dialog-template",
	props: ["cart", "isEditable"],
	data: function () {
		return {
			modal: {
				id: "discount-dialog-modal",
				title: "Apply Discount",
			},
			discount: {
				type: "p",
				value: 0,
				total: 0,
			},
			maxDiscountAllowed: 100,
		};
	},
	watch: {
		"discount.value": {
			handler: function (newValue, oldValue) {
				if (isNaN(newValue)) {
					this.discount.value = oldValue;
				} else {
					var result = this.updateDiscount(newValue, oldValue);
					if (result !== false) {
						this.discount.value = result;
					}
				}
			},
			deep: true,
		},
		"discount.type": {
			handler: function (values, oldValues) {
				this.updateDiscountType();
			},
			deep: true,
		},
	},
	methods: {
		updateDiscount: function (newValue, oldValue) {
			if (this.discount.type === "f") {
				if (Number(newValue) > Number(this.cart.totals.subTotal)) {
					return oldValue;
				} else {
					this.discount.total = Number(newValue);
					return false;
				}
			} else if (this.discount.type === "p") {
				if (newValue > 100 || newValue > this.maxDiscountAllowed) {
					return Number(oldValue);
				} else {
					var mixTotal =
						Number(this.cart.totals.subTotal) -
						Number(this.cart.totals.promotionTotal);
					this.discount.total = Number(
						(Number(newValue) * Number(mixTotal)) / 100,
					).toFixed(2);
					return false;
				}
			}
		},
		updateDiscountType: function () {
			var value = Number(this.discount.value);
			if (this.discount.type === "f") {
				if (value > Number(this.cart.totals.subTotal)) {
					this.discount.value = 0;
				} else {
					this.discount.total = value;
					return false;
				}
			} else if (this.discount.type === "p") {
				if (value > 100) {
					this.discount.value = 0;
				} else {
					var mixTotal =
						Number(this.cart.totals.subTotal) -
						Number(this.cart.totals.promotionTotal);
					this.discount.total = Number(
						(Number(value) * Number(mixTotal)) / 100,
					).toFixed(2);
				}
			}
		},
		handleSubmit: function () {
			this.cart.totals.discountType = this.discount.type;
			this.cart.totals.discountValue = this.discount.value;
			this.cart.totals.discount = this.discount.total;
			bus.$emit("updateCartTotal", true);
			this.hideDialog();
		},
		handleCancel: function () {
			this.cart.totals.discountType = "p";
			this.cart.totals.discountValue = "";
			this.cart.totals.discount = 0;
			bus.$emit("updateCartTotal", true);
			this.hideDialog();
		},
		handleInitDiscountDialog: function (payload) {
			this.discount.type = this.cart.totals.discountType;
			this.discount.value = this.cart.totals.discountValue;
			this.discount.total = this.cart.totals.discount;
			this.maxDiscountAllowed = _s("maxDiscountAllowed");
			this.updateDiscountType();
			this.showDialog();
		},
	},
	created: function () {
		var self = this;
		bus.$on("initDiscountDialog", function (payload) {
			self.handleInitDiscountDialog(payload);
		});
	},
});
Vue.component("gratuity-dialog", {
	template: "#gratuity-dialog-template",
	props: ["cart", "isEditable"],
	data: function () {
		return {
			modal: {
				id: "gratuity-dialog-modal",
				title: "Change Gratuity Rate",
			},
			gratuity: {
				gratuityTotal: 0,
				gratuityRate: 0,
			},
		};
	},
	watch: {
		"gratuity.gratuityRate": {
			handler: function (newValue, oldValue) {
				if (isNaN(newValue)) {
					this.gratuity.gratuityRate = oldValue;
				} else {
					var result = this.updateCalculation(newValue, oldValue);
					if (result !== false) {
						this.gratuity.gratuityRate = result;
					}
				}
			},
			deep: true,
		},
	},
	methods: {
		updateCalculation: function (newValue, oldValue) {
			if (newValue > 100) {
				return Number(oldValue);
			} else {
				var mixTotal =
					Number(this.cart.totals.subTotal) -
					Number(this.cart.totals.promotionTotal);
				this.gratuity.gratuityTotal = Number(
					(Number(newValue) * Number(mixTotal)) / 100,
				).toFixed(2);
				return false;
			}
		},
		handleClear() {
			this.gratuity.gratuityTotal = 0;
			this.gratuity.gratuityRate = 0;
			this.handleSubmit();
		},
		handleSubmit: function () {
			this.cart.totals.gratuityRate = this.gratuity.gratuityTotal;
			this.cart.totals.gratuityRate = this.gratuity.gratuityRate;
			bus.$emit("updateCartTotal", true);
			this.hideDialog();
		},
		handleCancel: function () {
			this.hideDialog();
		},
		handleInitGratuityDialog: function (payload) {
			this.gratuity.gratuityTotal = this.cart.totals.gratuityTotal.toFixed(2);
			this.gratuity.gratuityRate = this.cart.totals.gratuityRate;
			this.showDialog();
		},
	},
	created: function () {
		var self = this;
		bus.$on("initGratuityDialog", function (payload) {
			self.handleInitGratuityDialog(payload);
		});
	},
});
Vue.component("split-order", {
	template: "#split-dialog-template",
	props: ["order", "allowGratuity"],
	data: function () {
		return {
			modal: {
				id: "split-dialog-modal",
				title: "Split Order",
			},
			error: {
				show: false,
				message: "",
			},
			activeComponent: "splitTypeSelection",
			activeInvoice: 0,
			canCloseOrder: false,
			gratuityPersons: _s("gratuityPersons"),
			anyPaymentDone: false,
		};
	},
	computed: {
		getParts: function () {
			return this.order.split.length;
		},
		activeInvoiceItems: function () {
			return this.getActiveInvoiceItems();
		},
		isActivePaymentDone: function () {
			var index = this.activeInvoice;
			return this.isPaymentDone(index);
		},
		isSplitPaymentCompleted: function () {
			return this.isSplitPaymentDone();
		},
		isSplitByItemAvailable: function () {
			return this.canSplitByItem();
		},
		nonAddedCartItems: function () {
			return this.getNonAddedCartItems();
		},
		hasNonAddedItems: function () {
			return this.getSum(this.getNonAddedCartItems(), "quantity") > 0;
		},
		remainingItem: function () {
			return this.hasNonAddedItems
				? this.activeInvoice + 1 !== this.order.split.length
				: true;
		},
	},
	methods: {
		initSplit: function () {
			if (this.order.mode === "add" || this.order.splitType === "none") {
				this.activeComponent = "splitTypeSelection";
				this.order.split = [];
				this.activeInvoice = 0;
				for (var i = 0; i < _s("minSplitInvoices"); i++) {
					this.plusInvoice();
				}
				this.calculate();
			} else if (this.order.mode === "edit") {
				if (this.order.splitType === "equal") {
					this.activeComponent = "splitEqualManage";
				} else if (this.order.splitType === "item") {
					this.activeComponent = "splitItemManage";
				}
			}
			this.showDialog();
		},
		hideModal: function () {
			//this.order.splitType = 'none';
			//bus.$emit('loadExistingOrder',this.order.id);
			this.handleCloseModal(this.modal.id);
		},
		canSplitByItem: function () {
			return this.getCartTotalQuantity() > 1;
		},
		getCartTotalQuantity: function () {
			return this.order.cart.items.reduce(function (qty, i) {
				return Number(qty) + Number(i.quantity);
			}, 0);
		},
		getSplitInvoicesItemTotalQty: function (item) {
			var self = this;
			var total = 0;
			if (this.order.split.length) {
				this.order.split.forEach(function (s) {
					var itemTotalQty = 0;
					if (s.items.length) {
						itemTotalQty = self.getItemTotalQty(s.items, item);
					}
					itemTotalQty = isNaN(itemTotalQty) ? 0 : itemTotalQty;
					total += Number(itemTotalQty);
				});
			}
			return total;
		},
		getItemTotalQty: function (items, compareItem) {
			var item = items.find(function (i) {
				return Number(compareItem.id) === Number(i.orderItemId);
			});
			if (typeof item !== "undefined") {
				return Number(item.quantity);
			}
			return 0;
		},
		getActiveInvoiceItems: function () {
			var activeInvoice = this.order.split[this.activeInvoice];
			if (typeof activeInvoice !== "undefined") {
				return typeof activeInvoice.items !== "undefined"
					? activeInvoice.items
					: [];
			}
			return [];
		},
		getNonAddedCartItems: function () {
			var self = this;
			var cartItems = JSON.parse(JSON.stringify(this.order.cart.items));
			cartItems.forEach(function (ci) {
				var totalQuantityAdded = self.getSplitInvoicesItemTotalQty(ci);
				ci.quantity -= Number(totalQuantityAdded);
			});
			return cartItems;
		},
		handleSelectSplitType: function (type) {
			if (type === "item" && !this.canSplitByItem()) {
				alert("Cannot split as only 1 item is added to the cart.");
				return false;
			}
			this.order.splitType = type;
			if (this.order.splitType === "equal") {
				this.activeComponent = "splitEqualManage";
			} else if (this.order.splitType === "item") {
				this.activeComponent = "splitItemManage";
			}
			this.calculate();
		},
		clearSplitOrder: function () {
			var self = this;
			var orderId = this.order.id;
			var data = {
				module: "pos",
				method: "order_none_split_type",
				order_id: orderId,
			};
			var request = submitRequest(data, "post");
			request.then(function (res) {
				bus.$emit("loadExistingOrder", orderId);
				self.hideModal();
			});
		},
		plusInvoice: function (calculate) {
			if (typeof calculate === "undefined") {
				calculate = false;
			}
			var maxSplitInvoices = _s("maxSplitInvoices");
			if (this.order.splitType === "item") {
				var cartTotalQuantity = this.getCartTotalQuantity();
				maxSplitInvoices =
					cartTotalQuantity < maxSplitInvoices
						? cartTotalQuantity
						: maxSplitInvoices;
			}
			if (this.order.split.length < maxSplitInvoices) {
				var invoice = this.blankInvoice();
				this.order.split.push(invoice);
				if (calculate) {
					this.calculate();
				}
			}
		},
		minusInvoice: function () {
			if (this.order.split.length > _s("minSplitInvoices")) {
				this.order.split.splice(this.order.split.length - 1, 1);
				this.calculate();
				this.activeInvoice = this.order.split.length - 1
			}
		},
		blankInvoice: function () {
			var title = "Split #" + (Number(this.order.split.length) + 1);
			return {
				title: title,
				orderNo: "",
				orderId: "",
				customerId: "",
				billingName: "",
				items: [],
				change: 0,
				discountType: "p",
				discountValue: "",
				freightTotal: 0,
				payments: [],
				taxRate: _s("defaultTaxRate"),
				tip: 0,
				subTotal: 0,
				promotionTotal: 0,
				discount: 0,
				taxTotal: 0,
				grandTotal: 0,
				gratuityTotal: 0,
				gratuityRate: _s("gratuityRate"),
			};
		},
		blankItem: function () {
			return {
				orderItemId: "",
				title: "",
				taxable: "1",
				quantity: 0,
				rate: 0,
				amount: 0,
			};
		},
		blankPayment: function () {
			return {
				orderItemId: "",
				quantity: 0,
				rate: 0,
				amount: 0,
				tipTotal: 0,
				changeTotal: 0,
			};
		},
		handleAddRemainingSplitItem: async function () {
			var self = this;
			await asyncForEach(self.nonAddedCartItems, async (i) => {
				if (Number(i.quantity) > 0) {
					var cartItem = self.order.cart.items.find(function (ci) {
						return Number(ci.itemId) === Number(i.itemId);
					});
					var splitItem = self.blankItem();
					splitItem.orderItemId = cartItem.id;
					if (!splitItem.orderItemId) {
						splitItem.orderItemId = cartItem.orderItemId;
					}
					splitItem.title = cartItem.title;
					splitItem.taxable = cartItem.taxable;
					splitItem.rate = Number(cartItem.rate).toFixed(2);
					splitItem.quantity = Number(i.quantity);
					splitItem.amount = Number(
						Number(splitItem.quantity) * Number(splitItem.rate),
					).toFixed(2);

					var itemIndex = self.order.split[self.activeInvoice].items.findIndex(
						function (si) {
							return Number(si.orderItemId) === Number(splitItem.orderItemId);
						},
					);
					if (itemIndex === -1) {
						self.order.split[self.activeInvoice].items.push(splitItem);
					} else {
						self.order.split[self.activeInvoice].items[itemIndex].quantity = Number(splitItem.quantity) + Number(self.order.split[self.activeInvoice].items[itemIndex].quantity);
					}

					//self.order.split[self.activeInvoice].items.push(splitItem);
				}
			});
			self.calculate();
		},
		handleAddSplitItem: function (item) {
			var itemId = item.itemId;

			var itemIndex = this.order.split[this.activeInvoice].items.findIndex(
				function (si) {
					return Number(si.orderItemId) === Number(item.id);
				},
			);
			if (itemIndex === -1) {
				var cartItem = this.order.cart.items.find(function (ci) {
					return ci.itemId === itemId;
				});
				/*var splitItem = JSON.parse(JSON.stringify(cartItem));
                splitItem.quantity = 1;*/
				var splitItem = this.blankItem();
				splitItem.orderItemId = cartItem.id;
				if (!splitItem.orderItemId) {
					splitItem.orderItemId = cartItem.orderItemId;
				}
				splitItem.title = cartItem.title;
				splitItem.taxable = cartItem.taxable;
				splitItem.rate = Number(cartItem.rate).toFixed(2);
				splitItem.quantity = 1;
				splitItem.amount = Number(
					Number(splitItem.quantity) * Number(splitItem.rate),
				).toFixed(2);
				this.order.split[this.activeInvoice].items.push(splitItem);
			} else {
				var totalQuantityAdded = this.getSplitInvoicesItemTotalQty(item);
				var existingQty = this.getExistingQty(this.order.cart.items, item);
				if (existingQty > totalQuantityAdded) {
					this.order.split[this.activeInvoice].items[itemIndex].quantity++;
				}
			}
			this.calculate();
		},
		getExistingQty: function (items, compareItem) {
			var item = items.find(function (i) {
				return Number(compareItem.id) === Number(i.id);
			});
			if (typeof item !== "undefined") {
				return Number(item.quantity);
			}
			return 0;
		},
		handlePayment: function () {
			var split = this.order.split[this.activeInvoice];
			var payments = [];
			if (split.payments) {
				payments = split.payments;
			}
			var splitTotals = {
				payments: payments,
				subTotal: split.subTotal,
				taxTotal: split.taxTotal,
				promotionTotal: split.promotionTotal,
				taxRate: this.order.cart.totals.taxRate,
				freightTotal: split.freightTotal,
				discount: split.discount,
				discountValue: split.discountValue,
				discountType: split.discountType,
				change: split.change,
				tip: split.tip,
				grandTotal: split.grandTotal,
				gratuityTotal: split.gratuityTotal,
				gratuityRate: this.order.cart.totals.gratuityRate,
			};
			bus.$emit("payBoxInit", {
				type: "split",
				totals: splitTotals,
				split: split,
			});
		},

		handlePrintSplitOrder: function (printers) {
			var split = this.order.split[this.activeInvoice];
			var payload = {
				splitId: split.id,
				orderId: this.order.id,
				printers: printers
			};
			bus.$emit("printSplitOrderReceipt", payload);
		},
		printSplitOrder:function(){
			this.handlePrintSplitOrder(['cashier']);
		},
		handleCloseOrder: function () {
			this.order.close = true;
			bus.$emit("saveOrder", true);
			this.handleCloseModal(this.modal.id);
		},
		calculate: function () {
			if (this.order.splitType === "equal") {
				this.calculateEqually();
			} else if (this.order.splitType === "item") {
				this.calculateItem();
			}
		},
		calculateItem: function () {
			var self = this;

			self.order.split.forEach(function (invoice) {
				var subTotal = 0;
				var taxableTotal = 0;
				var gratuityTotal = 0;
				invoice.items.forEach(function (item, index) {
					invoice.items[index].quantity = Number(
						invoice.items[index].quantity,
					).toFixed(0);
					if (item.taxable === "1") {
						taxableTotal = Number(
							Number(taxableTotal) + Number(item.quantity) * Number(item.rate),
						).toFixed(2);
					}
					subTotal = Number(
						Number(subTotal) + Number(item.quantity) * Number(item.rate),
					).toFixed(2);
					item.amount = Number(item.quantity) * Number(item.rate);
				});
				var deliveryTotal = 0;
				var promotionTotal = 0;
				if (self.allowGratuity) {
					if (
						self.order.tableId &&
						Number(self.order.seatUsed) >= Number(self.gratuityPersons)
					) {
						gratuityTotal =
							(Number(subTotal) * Number(self.order.cart.totals.gratuityRate)) /
							100;
					}
				}
				var mixTotal =
					Number(taxableTotal) + Number(deliveryTotal) + Number(gratuityTotal);
				var promotionTotal =
					(Number(subTotal) * Number(self.order.cart.totals.promotionTotal)) /
					Number(self.order.cart.totals.subTotal);
				mixTotal -= Number(promotionTotal);
				var discount =
					(Number(subTotal) * Number(self.order.cart.totals.discount)) /
					Number(self.order.cart.totals.subTotal);
				mixTotal -= discount.toFixed(2); //Number(self.cart.totals.discount);
				var taxTotal =
					(Number(mixTotal) * Number(invoice.taxRate)) / Number(100);
				mixTotal = Number(mixTotal) + (Number(subTotal) - Number(taxableTotal));
				invoice.discount = Number(discount).toFixed(2);
				invoice.promotionTotal = Number(promotionTotal).toFixed(2);
				invoice.subTotal = Number(subTotal).toFixed(2);
				invoice.taxTotal = Number(taxTotal).toFixed(2);
				invoice.gratuityTotal = Number(gratuityTotal).toFixed(2);
				invoice.grandTotal = Number(
					Number(mixTotal) + Number(taxTotal),
				).toFixed(2);
			});

			this.order.splitType = "item";
			bus.$emit("saveOrder", { saveAndLoad: true });
		},
		calculateEqually: function () {
			var self = this;
			var divide = this.order.split.length;
			var items = this.order.cart.items;
			var splitItems = [];

			var subTotal = 0;
			var taxableTotal = 0;
			var gratuityTotal = 0;

			items.forEach(function (item) {
				var splitItem = self.blankItem();
				splitItem.orderItemId = item.id;
				if (!splitItem.orderItemId) {
					splitItem.orderItemId = item.orderItemId;
				}
				splitItem.title = item.title;
				splitItem.taxable = item.taxable;
				splitItem.rate = Number(Number(item.rate) / Number(divide)).toFixed(2);
				splitItem.quantity = Number(item.quantity).toFixed(0);
				splitItem.amount = Number(
					Number(splitItem.quantity) * Number(splitItem.rate),
				).toFixed(2);
				splitItems.push(splitItem);

				if (Number(item.taxable) === 1) {
					taxableTotal = Number(
						Number(taxableTotal) +
							Number(splitItem.quantity) * Number(splitItem.rate),
					).toFixed(2);
				}
				subTotal = Number(
					Number(subTotal) +
						Number(splitItem.quantity) * Number(splitItem.rate),
				).toFixed(2);
			});
			if (self.allowGratuity) {
				if (
					self.order.tableId &&
					Number(self.order.seatUsed) >= Number(self.gratuityPersons)
				) {
					var gratuityTotal =
						Number(self.order.cart.totals.gratuityTotal) / divide;
				}
			}

			var deliveryTotal = Number(self.order.cart.totals.freightTotal) / divide;
			var discount = Number(self.order.cart.totals.discount) / divide;
			var promotionTotal =
				Number(self.order.cart.totals.promotionTotal) / divide;

			var mixTotal =
				Number(taxableTotal) + Number(deliveryTotal) + Number(gratuityTotal);
			mixTotal -= Number(promotionTotal);
			mixTotal -= Number(discount);

			var taxTotal = Number(
				(Number(mixTotal) * Number(self.order.cart.totals.taxRate)) /
					Number(100),
			).toFixed(2);
			mixTotal = Number(mixTotal) + (Number(subTotal) - Number(taxableTotal));

			var calculatedGrandTotal = 0;

			this.order.split.forEach(function (invoice, index) {
				if (invoice.items.length === 0) {
					invoice.items = splitItems;
				} else {
					invoice.items.forEach(function (single) {
						var updateItem = splitItems.find(function (splItem) {
							return splItem.orderItemId === single.orderItemId;
						});
						single.rate = updateItem.rate;
						single.quantity = updateItem.quantity;
						single.amount = updateItem.amount;
					});
				}
				invoice.subTotal = subTotal;
				invoice.discount = discount;
				invoice.taxTotal = taxTotal;
				invoice.gratuityTotal = gratuityTotal;
				invoice.promotionTotal = promotionTotal;
				invoice.grandTotal = Number(
					Number(mixTotal) + Number(taxTotal),
				).toFixed(2);
				calculatedGrandTotal += Number(invoice.grandTotal);
				if (index === self.order.split.length - 1) {
					if (self.order.cart.totals.grandTotal > calculatedGrandTotal) {
						var difference =
							Number(self.order.cart.totals.grandTotal) -
							Number(calculatedGrandTotal);
						invoice.grandTotal = Number(
							Number(invoice.grandTotal) + Number(difference),
						).toFixed(2);
					}
				}
			});

			this.order.splitType = "equal";
			bus.$emit("saveOrder", { saveAndLoad: true });
		},
		updateActiveInvoice: function (index) {
			this.activeInvoice = index;
		},
		getTotal: function (type) {
			if (typeof this.order.split[this.activeInvoice] !== "undefined") {
				if (typeof this.order.split[this.activeInvoice][type] !== "undefined") {
					return this.order.split[this.activeInvoice][type];
				}
			}
			return 0;
		},
		isPaymentDone: function (index) {
			var currentInvoice = this.order.split[index];
			if (typeof currentInvoice !== "undefined") {
				if (typeof currentInvoice.payments !== "undefined") {
					if (currentInvoice.payments.length) {
						var grandTotal = currentInvoice.grandTotal;
						var totalPaid = currentInvoice.payments.reduce(function (
							total,
							payment,
						) {
							return Number(total) + Number(payment.amount);
						},
						0);
						return totalPaid >= grandTotal;
					}
				}
			}
			return false;
		},
		isSplitPaymentDone: function () {
			var self = this;
			var isDone = true;
			if (self.order.split.length) {
				self.order.split.forEach(function (s, i) {
					if (!self.isPaymentDone(i)) {
						isDone = false;
					}
				});
			}
			return isDone;
		},
		anyPaymentDoneSplitOrder: function () {
			var result = false;
			if (this.order.split.length) {
				this.order.split.forEach(function (invoice) {
					if (typeof invoice.payments !== "undefined") {
						if (invoice.payments.length) {
							result = true;
						}
					}
				});
			}
			this.anyPaymentDone = result;
		},
		updateTipAndChange: function () {
			var tip = 0;
			var change = 0;
			this.order.split.forEach(function (s) {
				change += Number(s.change);
				tip += Number(s.tip);
			});
			this.order.cart.totals.change = Number(change);
			this.order.cart.totals.tip = Number(tip);
		},
	},
	created: function () {
		var self = this;
		bus.$on("initSplitOrder", function (payload) {
			setTimeout(function () {
				self.initSplit(payload);
			}, 1000);
			self.anyPaymentDone = false;
		});
		bus.$on("splitPaymentCompleted", function (payload) {
			self.order.split[self.activeInvoice] = payload.split;
			bus.$emit("setOrderEditable", false);
			if(Number(payload.printers.length) > 0){
				self.handlePrintSplitOrder(payload.printers);
			}
			var nextActiveIndex = Number(self.activeInvoice) + 1;
			if (nextActiveIndex < self.order.split.length) {
				self.activeInvoice++;
			} else {
				self.activeInvoice--;
				self.activeInvoice++;
			}
			if (self.isSplitPaymentDone()) {
				self.canCloseOrder = true;
			}
			self.anyPaymentDoneSplitOrder();
			self.updateTipAndChange();
		});
	},
});
Vue.component("order-note", {
	template: "#order-note-template",
	props: ["order"],
	data: function () {
		return {
			modal: {
				id: "order-note-modal",
				title: "Update Order Note",
			},
			obj: {
				notes: "",
			},
		};
	},
	methods: {
		initNotes: function () {
			this.obj.notes = this.order.notes;
			this.showDialog();
		},
		handleConfirm: function () {
			this.order.notes = this.obj.notes;
			this.obj.notes = "";
			this.hideDialog();
		},
		handleClearNotes: function () {
			this.obj.notes = "";
		},
	},
});
Vue.component("info-customer", {
	template: "#info-customer-template",
	mixins: [customerCustomFieldsMixin],
	props: ["customer"],
	data: function () {
		return {
			modal: {
				id: "info-customer-modal",
				activeTab: "basic",
			},
			order: {},
			enableRepeatOrder: _s("enableRepeatOrder"),
			allowCustomerGroup: _s("allowCustomerGroup"),
			module: "contacts/customers",
			customerCustomFields: [],
			allowCustomerNotes: _s("allowCustomerNotes"),
		};
	},
	computed: {
		groupTitle: function () {
			var title = "";
			if (typeof this.customer.group !== "undefined") {
				title = this.customer.group.title;
			}
			return title;
		},
		addressList: function () {
			return this.customer.addresses;
		},
	},
	methods: {
		deleteAddress: function (id) {
			if (ds_confirm("Are you sure to delete this address ?")) {
				var self = this;
				var data = {
					module: self.module,
					method: "address",
					customerId: self.customer.id,
					addressId: id,
				};
				var request = submitRequest(data, "delete");
				request.then(function (response) {
					if (response.status === "ok") {
						self.$emit("updated", response.customer);
					}
				});
			}
		},
		getAddressTitle: function (a) {
			var self = this;
			//this.city = {};
			//self.getCity(a.cityId);

			var title = "";
			title = a.address1 + " , " + a.address2 + " , " + a.zipCode;
			return title;
		},
		/* deleteAddress: function (index) {
        	if (ds_confirm("Are you sure to delete this address ?")) {
        		this.customer.addresses.splice(index, 0);
        	}
        }, */
		getAddress: function (index) {
			return this.customer.addresses[index];
		},
		handleAddAddress: function () {
			bus.$emit("initEditAddress", {
				mode: "add",
				customerId: this.customer.id,
			});
		},
		handleEditAddress: function (index) {
			var add = this.getAddress(index);

			bus.$emit("initEditAddress", {
				mode: "edit",
				address: JSON.parse(JSON.stringify(add)),
			});
		},
		getItems: function (items) {
			var string = "";
			if (items.length) {
				items.forEach(function (item) {
					if (item) {
						if (string !== "") {
							string += ", " + item.title;
						} else {
							string += item.title;
						}
					}
				});
			}
			return string;
		},
		onInitInfoCustomer: function (payload) {
			this.showDialog(this.modal.id);
			this.detailOrder(this.customer.id);
		},
		detailOrder: function () {
			var self = this;
			var data = {
				module: "orders",
				method: "order",
				id: self.customer.id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.order = response.orders;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
		handleEditCustomer: function () {
			bus.$emit("initEditCustomer", this.customer);
		},
		handleOrderDetails: function (id) {
			bus.$emit("initOrderDetails", { id: id });
		},
		handleRepeatOrder: function (id) {
			bus.$emit("repeatOrder", id);
		},
	},
	created: function () {
		var self = this;
		bus.$on("initInfoCustomer", function (payload) {
			self.onInitInfoCustomer(payload);
		});
		self.customerCustomFields = _s("customerCustomFields");
	},
});
Vue.component("edit-customer", {
	template: "#edit-customer-template",
	props: ["isEditable"],
	mixins: [customerCustomFieldsMixin],
	data: function () {
		return {
			module: "contacts/customers",
			modal: {
				id: "edit-customer-modal",
			},
			customer: {
				group: [],
			},
			masters: {
				countries: [],
				states: [],
				groups: [],
				cities: [],
			},
			allowCustomerGroup: _s("allowCustomerGroup"),
			//customerCustomFields: _s("customerCustomFields"),
			city: {},
			allowCustomerNotes: _s("allowCustomerNotes"),
		};
	},
	computed: {
		addressList: function () {
			return this.customer.addresses;
		},
	},
	methods: {
		handleAddAddress: function () {
			bus.$emit("initEditAddress", {
				mode: "add",
				customerId: this.customer.id,
			});
		},
		handleEditAddress: function (index) {
			var add = this.getAddress(index);

			bus.$emit("initEditAddress", {
				mode: "edit",
				address: JSON.parse(JSON.stringify(add)),
			});
		},
		getAddress: function (index) {
			return this.customer.addresses[index];
		},
		getAddressTitle: function (a) {
			var self = this;
			//this.city = {};
			//self.getCity(a.cityId);

			var title = "";
			title = a.address1 + " , " + a.address2 + " , " + a.zipCode;
			return title;
		},
		getCities: async function () {
			var self = this;
			var data = {
				module: "core/cities",
				method: "select_cities",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.cities = response.cities;
			}
		},
		getCityMeta: function (id) {
			var city = this.masters.cities.find(function (c) {
				return Number(c.id) === Number(id);
			});
			return city.name;
		},
		getCity: async function (id) {
			var self = this;
			var data = {
				city_id: id,
				module: "core/cities",
				method: "select_city",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.city = response.city;
			}
		},
		populateMeta: async function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.countries = response.countries;
				self.masters.states = response.states;
				self.masters.groups = response.groups;
			}
			return true;
		},
		onInitEditCustomer: function (payload) {
			this.showDialog(this.modal.id);
			var customerId = payload.id;
			this.detailCustomer(customerId);
		},
		detailCustomer: function (customerId) {
			var self = this;
			var data = {
				module: self.module,
				method: "single",
				id: customerId,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.customer = response.obj;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
		handleSubmit: function () {
			var error = false;
			var self = this;
			var form = $("#frm-edit-customer");
			if (!form.parsley().validate()) {
				error = true;
			}
			if (self.customer.email) {
				if (this.isEmailDuplicate()) {
					var email_field = this.$refs.email;
					$(email_field)
						.parsley()
						.addError("email_duplicate", { message: "Email already exists." });
					error = true;
				}
			}
			if (self.customer.phone) {
				if (this.isPhoneDuplicate()) {
					var phone_field = this.$refs.phone;
					$(phone_field)
						.parsley()
						.addError("phone_duplicate", { message: "Mobile already exists." });
					error = true;
				}
			}
			if (!error) {
				var method = "post";
				var data = {
					module: this.module,
					obj: this.customer,
				};
				if (method) {
					var request = submitRequest(data, method);
					request.then(function (response) {
						if (response.status === "ok") {
							self.$emit("updated", response.customer);
							self.hideDialog();
						}
					});
				} else {
					alert("Something went wrong!");
				}
			}
		},
		handleCancel: function () {
			this.hideDialog();
		},
		isEmailDuplicate: function () {
			var result = false;
			var string = this.customer.email;
			var field = this.$refs.email;
			var id = this.customer.id;
			$(field).parsley().removeError("email_duplicate");
			var url =
				_s("action") +
				"?module=" +
				this.module +
				"&method=duplicate_email&email=" +
				string;
			if (id) {
				url += "&id=" + id;
			}
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.status === 200) {
					var response = JSON.parse(this.responseText);
					result = response.result;
				}
			};
			xhttp.open("GET", url, false);
			xhttp.send();
			return result;
		},
		isPhoneDuplicate: function () {
			var result = false;
			var string = this.customer.phone;
			var field = this.$refs.phone;
			var id = this.customer.id;
			$(field).parsley().removeError("phone_duplicate");
			var url =
				_s("action") +
				"?module=" +
				this.module +
				"&method=duplicate_phone&phone=" +
				string;
			if (id) {
				url += "&id=" + id;
			}
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (this.status === 200) {
					var response = JSON.parse(this.responseText);
					result = response.result;
				}
			};
			xhttp.open("GET", url, false);
			xhttp.send();
			return result;
		},
		onName: function () {
			if (
				this.customer.displayName.trim() === "" ||
				this.customer.displayName.trim() === this.customer.firstName.trim() ||
				this.customer.displayName.trim() === this.customer.lastName.trim()
			) {
				this.customer.displayName =
					this.customer.firstName.trim() + " " + this.customer.lastName.trim();
			}
		},
		setAddressEdit: function (payload) {
			var id = payload.id;
			var index = this.customer.addresses.findIndex(function (a) {
				return a.id === id;
			});
			this.customer.addresses[index] = payload;
		},
	},
	created: function () {
		var self = this;
		bus.$on("initEditCustomer", function (payload) {
			//self.getCities();
			self.onInitEditCustomer(payload);
			self.populateMeta();
		});
		self.customerCustomFields = _s("customerCustomFields");
		bus.$on("initSaveAddress", function (payload) {
			if (payload.mode === "add") {
				self.customer.addresses.push(payload.address);
			}
			if (payload.mode === "edit") {
				self.setAddressEdit(payload.address);
			}
		});
	},
});
Vue.component("edit-address", {
	template: "#edit-address-template",
	data: function () {
		return {
			module: "contacts/customers",
			modal: {
				id: "edit-address-modal",
			},
			address: {},
			masters: {
				countries: [],
				states: [],
				cities: [],
			},
			mode: null,
			customerId: null,
		};
	},
	watch: {
		"address.stateId": {
			handler: function (after, before) {
				if (after !== "" && before !== after) {
					this.populateCites(after);
				}
			},
			deep: true,
		},
	},
	computed: {
		addressList: function () {
			return this.customer.addresses;
		},
	},
	methods: {
		addressBlankObj: function () {
			return {
				id: "",
				title: "",
				customerId: "",
				address1: "",
				address2: "",
				countryId: _s("defaultCountryId") ? _s("defaultCountryId") : null,
				stateId: _s("defaultStateId") ? _s("defaultStateId") : null,
				cityId: _s("defaultCityId") ? _s("defaultCityId") : null,
				zipCode: "",
				added: "",
			};
		},
		populateCites: async function (id) {
			Codebase.blocks("#edit-address-block", "state_loading");
			var self = this;
			var data = {
				state_id: id,
				country_id: self.address.countryId,
				module: "core/cities",
				method: "select_data",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.cities = response.cities;
			}
			Codebase.blocks("#edit-address-block", "state_normal");
		},
		populateMeta: async function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate",
			};
			var response = await submitRequest(data, "get");
			if (response.status === "ok") {
				self.masters.countries = response.countries;
				self.masters.states = response.states;
				self.masters.groups = response.groups;
			}
			return true;
		},
		onInitAddress: function (payload) {
			this.showDialog(this.modal.id);
			this.populateMeta();
			if (payload.mode === "edit") {
				this.mode = "edit";
				this.address = payload.address;
				//var addressId = payload.addressId;
				//this.detailAddress(addressId);
			} else {
				this.customerId = payload.customerId;
				this.address.customerId = payload.customerId;
				this.mode = "add";
			}
		},
		detailAddress: function (addressId) {
			var self = this;
			var data = {
				module: self.module,
				method: "address",
				id: addressId,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.address = response.address;
				} else {
					ds_alert(response.message.text, response.message.type);
				}
			});
		},
		handleSubmit: async function () {
			var self = this;
			var form = $("#frm-edit-address");
			/* if (form.parsley().validate()) {
            bus.$emit("initSaveAddress", {
            	address: self.address,
            	mode: self.mode,
            });
            	self.hideModel();
            }  */
			if (form.parsley().validate()) {
				var method = self.mode === "edit" ? "post" : "put";
				var data = {
					module: self.module,
					method: "address",
					obj: self.address,
				};
				var response = await submitRequest(data, method);
				if (response.status === "ok") {
					self.$emit("updated", response.customer);
					self.hideModel();
				}
			}
		},
		hideModel: function () {
			this.addressBlankObj();
			this.hideDialog(this.modal.id);
		},
	},
	created: function () {
		var self = this;
		bus.$on("initEditAddress", function (payload) {
			self.address = self.addressBlankObj();
			self.customerId = null;
			self.onInitAddress(payload);
		});
	},
});
Vue.component("order-details", {
	template: "#order-details-template",
	data: function () {
		return {
			module: "orders",
			modal: {
				obj: {
					payments: [],
					customer: [],
					refundPayments: [],
					items: [],
					split: [],
				},
			},
			paymentMethods: [],
			allowGratuity: _s("allowGratuity"),
			allowConvertChangeToTip:_s('allowConvertChangeToTip')
		};
	},
	computed: {
		afterRefundGrandTotal() {
			var totalPaid =
				Number(this.modal.obj.grandTotal) + Number(this.modal.obj.tip);
			var refundTotal = this.getRefundTotalPaid();
			return Number(Number(totalPaid) - Number(refundTotal)).toFixed(2) > 0
				? Number(Number(totalPaid) - Number(refundTotal)).toFixed(2)
				: 0;
		},
		cartItems() {
			return this.modal.obj.items.filter(function (i) {
				return Number(i.quantity) > 0;
			});
		},
		isSplitOrder:function(){
			return this.modal.obj.splitType != 'none';
		},
		splitOrderTitle:function(){
			return this.isSplitOrder ? ' ( Split Order )' : '';
		},
		splitOrderList:function(){
			return this.modal.obj.split;
		},
		isClosedOrder:function(){
			return this.modal.obj.orderStatus === 'Closed'
		}
	},
	methods: {
		handleConvertToTip: function () {
			bus.$emit("initConvertToTip", { orderId: this.modal.obj.id });
		},
		handleSplitPrint:function(split){
			if(ds_confirm("Are You Sure, You Want To Print This Split Order?")){
				var payload = {
					splitId: split.id,
					orderId: this.modal.obj.id,
					printers: ["cashiers"]
				};
				bus.$emit("printSplitOrderReceipt", payload);
			}

		},
		getSplitAmount:function(split){
			return split.title+" - " + _s("currencySign") + " " + Number(split.grandTotal).toFixed(2);
		},
		handleDownloadPdf: function () {
			Object.assign(document.createElement("a"), {
				target: "_blank",
				href: this.modal.obj.pdfUrl,
			}).click();
		},
		getPaymentMethodName: function (id) {
			if (this.paymentMethods.length) {
				var paymentMethod = this.paymentMethods.find(function (method) {
					return Number(method.id) === Number(id);
				});
				return paymentMethod.value;
			}
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: "pos",
				method: "populate_payment",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.paymentMethods = response.paymentMethods;
				}
			});
		},
		getRefundTotalPaid: function () {
			var payments = this.modal.obj.refundPayments;
			var totalPaid = 0;
			if (payments.length) {
				totalPaid = payments.reduce(function (totalPaid, payment) {
					return Number(totalPaid) + Number(payment.amount);
				}, totalPaid);
			}
			return totalPaid.toFixed(2);
		},
		getTotalPaid: function () {
			var payments = this.modal.obj.payments;
			var totalPaid = 0;
			if (payments.length) {
				totalPaid = payments.reduce(function (totalPaid, payment) {
					return Number(totalPaid) + Number(payment.amount);
				}, totalPaid);
			}
			return totalPaid.toFixed(2);
		},
		hasAddons: function (addons) {
			var has = false;
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						has = true;
					}
				});
			}
			return has;
		},
		getAddons: function (addons) {
			var string = "";
			if (addons.length) {
				addons.forEach(function (addon) {
					if (addon.enabled === true) {
						if (string !== "") {
							string += ", " + addon.title;
						} else {
							string += addon.title;
						}
					}
				});
			}
			return string;
		},
		getNotes: function (notes) {
			if (typeof notes === "object") {
				var string = "";
				if (notes.length) {
					notes.forEach(function (note) {
						if (string !== "") {
							string += ", " + note.title;
						} else {
							string += note.title;
						}
					});
				}
				return string;
			} else {
				return notes;
			}
		},
		handleViewOrder: function (id) {
			var self = this;
			var data = {
				module: this.module,
				method: "single_view",
				id: id,
			};

			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.modal.obj = response.obj;
				self.$bvModal.show("order-details-modal");
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("initOrderDetails", function (payload) {
			self.populateMeta();
			self.handleViewOrder(payload.id);
		});
		bus.$on("updateChangeAndTip",function(payload){
			self.populateMeta();
			self.handleViewOrder(payload.id);
		});
	},
});
Vue.component("convert-tip", {
	template: "#convert-tip-template",
	data: function () {
		return {
			module: "orders",
			paymentMethods: [],
			order:{},
			total:{
				tip:0,
				change:0
			},
			tipConverted: false,
		};
	},
	computed: {
		changeAmount: function () {
			return Number(this.order.change).toFixed(2);
		},
		canConvertToTip: function() {
            return Number(this.total.change) > Number(this.total.tip);
        },
		isTipAllow: function() {
            return Number(this.total.tip) > 0;
        },
	},
	watch: {
		"total.tip": {
			handler: function (after, before) {
				var self = this;
				if (after > self.changeAmount) {
					self.total.tip = before;
				}
				self.total.change = Math.abs(
					Number(self.changeAmount) - Number(self.total.tip),
				);
			},
			deep: true,
		},
	},
	methods: {
		handleCancel:function(){
			this.total= this.getTotalObj();
			this.$bvModal.hide("convert-tip-modal");
		},
		handleConfirm:function(){
			var obj={
				tip : this.total.tip,
				change :this.total.change,
			}
			var self = this;
			var data = {
				module: "pos",
				method: "update_change_and_tip",
				orderId: this.order.id,
				obj: obj,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				bus.$emit("updateChangeAndTip",{id:self.order.id});
				self.$bvModal.hide("convert-tip-modal");
			});
		},
		reverseTip: function () {
			this.tipConverted = false;
			this.total.tip = 0;
			this.total.change = this.changeAmount;

		},
		handleConvertToTip: function () {
			this.tipConverted = true;
			this.total.tip = this.changeAmount;
			this.total.change = 0;
		},
		handleViewOrder: function (id) {
			var self = this;
			var data = {
				module: this.module,
				method: "single_order",
				id: id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.order = response.obj;
				self.total.change = response.obj.change,
				self.total.tip = response.obj.tip,
				self.$bvModal.show("convert-tip-modal");
			});
		},
		getTotalObj:function(){
			return {
				total:0,
				tip:0,
			}
		}
	},
	created: function () {
		var self = this;
		bus.$on("initConvertToTip", function (payload) {
			self.total=self.getTotalObj();
			self.handleViewOrder(payload.orderId);
		});
	},
});
Vue.component("order-source-switch", {
	template: "#order-source-switch-template",
	data: function () {
		return {
			modal: {
				id: "order-source-switch-modal",
				loading: false,
			},
			sources: {
				web: {
					key: "web",
					status: false,
				},
			},
		};
	},
	methods: {
		onStatusChange: function (e) {
			var self = this;
			Codebase.blocks("#order-source-switch-block", "state_loading");
			if (e === "web") {
				var data = {
					module: "pos",
					method: "order_source_switch_settings",
					key: this.sources[e].key,
					value: this.sources[e].status,
				};

				var req = submitRequest(data, "post");
				req.then(function (res) {
					self.sources[e].status = res.value;
				});
				req.finally(function (res) {
					Codebase.blocks("#order-source-switch-block", "state_normal");
				});
			}
		},
		getStatusText(status) {
			return status === true ? "Accepting Orders" : "Not Accepting Orders";
		},
		populateMeta: function () {
			var sources = ["web"];
			var self = this;
			Codebase.blocks("#order-source-switch-block", "state_loading");
			var data = {
				module: "pos",
				method: "order_source_switch_settings",
			};
			var req = submitRequest(data, "get");
			req.then(function (res) {
				sources.forEach(function (s) {
					if (typeof res.settings[s] !== "undefined") {
						self.sources[s].status = res.settings[s] === "1";
					}
				});
			});
			req.finally(function (res) {
				Codebase.blocks("#order-source-switch-block", "state_normal");
			});
		},
	},
	created: function () {
		var self = this;
		bus.$on("showOrderSwitch", function (payload) {
			self.populateMeta();
			self.handleOpenModal(self.modal.id);
		});
	},
	beforeDestroy: function () {
		this.modal.loading = false;
	},
});
Vue.component("issue-refund", {
	template: "#issue-refund-template",
	props: ["registerId"],
	data: function () {
		return {
			module: "pos",
			order: {
				customer: [],
				payments: [],
			},
			paymentMethods: [],
			refundPayments: [],
			payment: {},
			message: "",
			showError: false,
			errorTimeout: 3000,
			enableExtOrderNo: _s("enableExtOrderNo"),
			fullRefundBtnDisabled: false,
			//paymentBtnDisabled: false,
			//confirmBtnDisabled: true,
			afterRefundGrandTotal: 0,
			refundTotal: 0,
			cloverPaymentObj: null,
		};
	},
	watch: {
		refundPayments: {
			handler: function (newValue, oldValue) {
				//this.updateAfterRefundGrandTotal();
			},
			deep: true,
		},
	},
	computed: {
		getPaymentMethods: function () {
			return this.paymentMethods.filter(function (s) {
				return s.type === "manual";
			});
		},
		grandTotal: function () {
			return this.order.grandTotal;
		},
		confirmBtnDisabled: function () {
			return this.refundPayments.length < 1;
		},
		paymentBtnDisabled: function () {
			return Number(this.refundTotal) >= Number(this.grandTotal);
		},
		grandTotalAndTip() {
			return Number(this.order.tip) + Number(this.order.grandTotal);
		},
	},
	methods: {
		updateAfterRefundGrandTotal: function () {
			var payments = this.refundPayments;
			var totalPaid = 0;
			if (payments.length) {
				totalPaid = payments.reduce(function (totalPaid, payment) {
					return Number(totalPaid) + Number(payment.amount);
				}, totalPaid);
			}
			var self = this;
			self.afterRefundGrandTotal = Number(self.grandTotalAndTip) - totalPaid;
			self.refundTotal = totalPaid;
		},
		handleConfirm: function () {
			if (
				_s("allowCloverPayment") &&
				this.checkAnyCardPayment() &&
				this.cloverPaymentObj
			) {
				bus.$emit("cloverRefundPayment", this.cloverPaymentObj);
			} else {
				this.handleRefundPos();
			}
		},
		checkAnyCardPayment: function () {
			var result = false;
			if (this.refundPayments.length) {
				this.refundPayments.forEach(function (p) {
					if (Number(p.paymentMethodId) === _s("cardPaymentId")) {
						result = true;
					}
				});
			}
			return result;
		},
		handleRefundPos: function (cloverRefundObj) {
			if (typeof cloverRefundObj === "undefined") {
				var cloverRefundObj = null;
			}
			var orderId = this.order.id;
			var status = null;
			var self = this;

			var data = {
				module: "pos",
				method: "order_refund",
				orderId: orderId,
				refundPayments: self.refundPayments,
				sessionId: self.order.sessionId,
				registerId: self.registerId,
				orderStatus: status,
				refundTotal: self.refundTotal,
				cloverRefundObj: cloverRefundObj,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					self.resetRefundObj();
					self.refundPayments = [];
					bus.$emit("resetOrder", { orderId: orderId });
					bus.$emit("initOrderHistory", true);
					self.$bvModal.hide("issue-refund-modal");
				}
			});
		},
		handleCancel: function () {
			this.resetRefundObj();
			this.refundPayments = [];
			this.$bvModal.hide("issue-refund-modal");
		},
		handlePayment: function () {
			this.clearMessage();
			if (Number(this.payment.amount) === 0 || isNaN(this.payment.amount)) {
				this.setMessage("Invalid amount");
				this.payment.amount = "";
			} else if (!this.payment.paymentMethodId) {
				this.setMessage("Please select payment method first");
				this.payment.amount = "";
			} else if (
				Number(this.payment.amount) + Number(this.refundTotal) >
				Number(this.grandTotalAndTip)
			) {
				this.setMessage("Amount is greater then grand total");
				this.payment.amount = "";
			} else {
				var existingPaymentIndex = this.getExistingPaymentIndex(
					this.payment.paymentMethodId,
				);
				if (existingPaymentIndex !== -1) {
					var existingAmount = this.refundPayments[existingPaymentIndex].amount;
					this.refundPayments[existingPaymentIndex].amount =
						Number(this.payment.amount) + Number(existingAmount);
				} else {
					this.refundPayments.push(this.payment);
				}
				this.updateAfterRefundGrandTotal();
				this.resetRefundObj();
			}
		},
		handleBalance: function () {
			this.payment.amount =
				Number(this.grandTotalAndTip) - Number(this.refundTotal);
			this.handlePayment();
		},
		getExistingPaymentIndex: function (methodId) {
			return this.refundPayments.findIndex(function (payment) {
				return payment.paymentMethodId === methodId;
			});
		},
		clearMessage: function () {
			this.message = "";
			this.showError = false;
		},
		setMessage: function (message) {
			var self = this;
			self.message = message;
			self.showError = true;
			setTimeout(function () {
				self.showError = false;
			}, self.errorTimeout);
		},
		resetRefundObj: function () {
			this.payment = {
				paymentMethodId: "",
				amount: "",
			};
		},
		handleRemovePayment: function (index) {
			this.refundPayments.splice(index, 1);
			this.updateAfterRefundGrandTotal();
		},
		updatePaymentMethod: function (index) {
			var paymentMethod = this.paymentMethods[index];
			this.payment.cash = paymentMethod.cash;
		},
		getPaymentMethod: function (id) {
			if (this.paymentMethods.length) {
				var paymentMethod = this.paymentMethods.find(function (method) {
					return Number(method.id) === Number(id);
				});
				return paymentMethod.value;
			}
		},
		getTotalPaid: function () {
			var payments = this.order.payments;
			var totalPaid = 0;
			if (payments.length) {
				totalPaid = payments.reduce(function (totalPaid, payment) {
					return Number(totalPaid) + Number(payment.amount);
				}, totalPaid);
			}
			return totalPaid.toFixed(2);
		},
		handleViewOrder: function (id) {
			var self = this;
			var data = {
				module: "orders",
				method: "single_view",
				id: id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.order = response.obj;
				self.cloverPaymentObj = response.obj.cloverPayment.row;
			});
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: self.module,
				method: "populate_payment",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.paymentMethods = response.paymentMethods;
					self.$bvModal.show("issue-refund-modal");
				}
			});
			this.resetRefundObj();
		},
		handleSetRefunded: function () {
			var payment = {
				amount: "",
				paymentMethodId: "",
			};
			var self = this;
			self.refundPayments = [];
			self.order.payments.forEach(function (p) {
				payment.amount = p.amount;
				payment.paymentMethodId = p.paymentMethodId;
				self.refundPayments.push(payment);
			});
			this.updateAfterRefundGrandTotal();
			this.fullRefundBtnDisabled = true;
			this.handleConfirm();
			/* var orderId = this.order.id;
            this.handleChangeStatus(orderId, status);
            this.handleRefunded(orderId);
            this.resetRefundObj();
            this.refundPayments = [];
            bus.$emit("initOrderHistory", true);
            this.$bvModal.hide("issue-refunded-modal"); */
		},
		handleRefunded: function (id) {
			var data = {
				module: "pos",
				method: "order_refund",
				id: id,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
				}
			});
		},
		handleChangeStatus: function (id, status) {
			var self = this;
			var data = {
				module: "pos",
				method: "order_status",
				sessionId: self.order.sessionId,
				id: id,
				registerId: self.registerId,
				orderStatus: status,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					bus.$emit("resetOrder", { orderId: id });
				}
			});
		},
		cloverRefundPaymentDone: function (payload) {
			if (typeof payload !== "undefined") {
				this.handleRefundPos(payload);
			} else {
				ds_alert("Something went to Wrong..", "Warning");
			}
		},
	},
	created: function () {
		var self = this;
		bus.$on("initRefunded", function (payload) {
			self.refundPayments = [];
			self.refundTotal = 0;
			self.resetRefundObj();
			self.handleViewOrder(payload.id);
			self.populateMeta();
			self.fullRefundBtnDisabled = false;
		});
		bus.$on("cloverRefundPaymentClose", function (payload) {
			self.cloverRefundPaymentDone(payload);
		});
	},
});
Vue.component("employee-login", {
	template: "#employee-login-template",
	data: function () {
		return {
			module: "employees",
			employee: {},
			errorMessage: null,
			showError: false,
		};
	},
	methods: {
		handleCancel: function () {
			var self = this;
			self.employee = {};
			self.$bvModal.hide("employee-login-modal");
		},
		blankObject: function () {
			return {
				id: null,
				code: null,
				sessionId: null,
				name: null,
				openingRegisterId: null,
			};
		},
		handleSubmit: function () {
			var self = this;
			var data = {
				module: self.module,
				method: "set_employee_shift",
				employee: self.employee,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					bus.$emit("setEmployeeId", {
						employeeId: response.employee.id,
						employeeName: response.employee.name,
					});
					localStorage.setItem("employeeId", response.employee.id);
					localStorage.setItem("employeeName", response.employee.name);
				} else {
					self.showError = true;
					self.errorMessage = response.message;
				}
			});
		},
		setFocus:function(){
			/* var self = this;
			self.$nextTick(function () {
				self.$refs.codeRef.focus()
			}) */
			var self = this;
			 setTimeout(() => {
				self.$refs.codeRef.focus();
			}, 500);
		},
	},

	created: function () {
		var self = this;
		bus.$on("showEmployeeLogin", function (payload) {
			self.showError = false;
			self.errorMessage = null;
			self.employee = self.blankObject();
			self.employee = payload;
			self.employee.code = null;
			self.$bvModal.show("employee-login-modal");
			self.setFocus();
		});

	},
});
Vue.component("user-login", {
	template: "#user-login-template",
	data: function () {
		return {
			login: {
				email: _s("managerEmail"),
				password: "",
			},
			errorMessage: "",
			showMessage: false,
			sendingRequest: false,
			mode: null,
		};
	},
	methods: {
		handleCancel: function () {
			this.blankLoginObject();
			this.$bvModal.hide("user-login-modal");
		},
		handleSubmit: function () {
			var self = this;
			self.sendingRequest = true;
			self.showMessage = false;
			var form = $("#frm-login");
			if (form.parsley().validate()) {
				var data = {
					module: "pos",
					email: this.login.email,
					password: this.login.password,
					method: "user_login",
				};

				var request = submitRequest(data, "POST");
				request.then(function (response) {
					self.sendingRequest = false;
					if (response.status === "ok") {
						bus.$emit("setUserLogin", { type: self.mode });
					} else if (response.status === "error") {
						self.errorMessage = response.message;
						self.showMessage = true;
					}
				})
				.finally(function(){
					self.handleCancel();
				});
			} else {
				self.sendingRequest = false;
			}
		},
		blankLoginObject: function () {
			(this.login.password = ""),
				(this.sendingRequest = false),
				(this.errorMessage = ""),
				(this.showMessage = false);
		},
	},
	beforeDestroy: function (){
		//bus.$off("setUserLogin");
	},
	created: function () {
		var self = this;
		bus.$on("showUserLogin", function (payload) {
			self.mode = payload.type;
			self.blankLoginObject();
			self.$bvModal.show("user-login-modal");
		});
	},
});
Vue.component("pos", {
	template: "#pos-template",
	data: function () {
		return {
			module: "pos",
			posVersionMismatch: false,
			canCheckUpdate: _s("updateCheck"),
			updateInterval: _s("updateCheckInterval"),
			checkingForUpdate: false,
			enableSourceSwitch: _s("enableSourceSwitch"),
			allowRefund: _s("allowRefund"),
			unacceptedOrders: [],
			masters: {
				categories: [],
				items: [],
				customers: [],
				tables: [],
			},
			newSession: {
				registerId: _s("registerId"),
				openingCash: _s("openingCash"),
				openingNote: "",
			},
			orderMethods: _s("orderMethods"),
			register: _s("registerId"),
			sessionChecked: false,
			session: null,
			paymentAllowed: true,
			isEditable: false,
			directPrint: false,
			loadSplitDialog: false,
			order: {},
			isTabletMode: false,
			allowGratuity: _s("allowGratuity"),
			checkingForPrintQueue: false,
			browserId: null,
			topWarning: {
				show: false,
				message: "",
			},
			employeeId: null,
			employeeName: null,
			employees: [],
			registerDeviceId: null,
			registerId: null,
			registerCheckLogin: false,
			registerSession: null,
			newRegisterSession: {
				sessionId: null,
				registerId: null,
				openingCash: 0,
				openingNote: "",
			},
			registerTitle: null,
			openRegister: 0,
			openEmpShiftCount: 0,
			closeRegister: 0,
			openOrderCount: 0,
			primaryRegister: false,
			printQueueBusy:false,
		};
	},
	watch: {
		"order.type": {
			handler: function (newValue, oldValue) {
				if (newValue === "dine") {
					if (this.order.tableId === null || this.order.tableId === false) {
						bus.$emit("initTableList", { mode: "select" });
					}
				}
			},
			deep: true,
		},
		checkingForUpdate: {
			handler: function (newValue, oldValue) {
				if (newValue === false) {
					var self = this;
					self.checkingForUpdate = false;
					setTimeout(function () {
						self.updateCheck();
					}, self.updateInterval);
				}
			},
		},
	},
	computed: {
		canEmpLogin: function () {
			return this.employeeId == null;
		},
		showRegisterSession: function () {
			return this.registerSession == null;
		},
		canShowPos: function () {
			return this.sessionOpen && !this.canEmpLogin && !this.showRegisterSession;
		},
		canShowEmpLogin: function () {
			return this.canEmpLogin && !this.showRegisterSession;
		},
		canShowSession: function () {
			return !this.sessionOpen && this.sessionChecked && !this.isTabletMode;
		},
		sessionOpen: function () {
			return this.session !== null;
		},
		registerOpen: function () {
			return this.registerSession !== null;
		},
		anyShiftOpen: function () {
			return this.openEmpShiftCount > 0;
		},
		anyOrderOpen: function () {
			return this.openOrderCount > 0;
		},
		lastRegister: function () {
			return this.openRegister == 1;
			/*return this.openRegister !== 0
				? Number(this.openRegister) - Number(this.closeRegister) === 1
				: false;*/
		},
		canCloseSession: function () {
			return true;
			//return this.lastRegister && !this.anyShiftOpen && !this.anyOrderOpen;
			return this.lastRegister && !this.anyOrderOpen;
		},
		isPrimaryRegister: function () {
			return this.primaryRegister;
		},
	},
	methods: {
		setPrintQueueBusy(value) {
			this.printQueueBusy = value;
		},
		handleOrderType: function (type) {
			if (this.isEditable) {
				if (this.order.mode === "add") {
					this.order.type = type;
				} else {
					if (type === "p") {
						if (ds_confirm("are you sure you want to pickup this order ?")) {
							this.order.type = "p";
							this.order.cart.totals.gratuityTotal = 0;
							this.order.seatUsed = 0;
							this.handlePlaceOrder(true);
						}
					} else if (type === "dine") {
						this.order.type = type;
					}
				}
			}
		},
		handleEmployeeShiftClose: function (payload) {
			var self = this;
			var obj = {
				registerId: self.registerId,
				employeeId: self.employeeId,
				sessionId: self.session.id,
				takeOut: payload.takeOut,
			};
			var data = {
				module: "employees",
				method: "set_employee_shift_close",
				obj: obj,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status == "ok") {
					ds_alert(response.message);
					self.handleShiftDataPrint(obj);
				}
			});
		},
		handleShiftDataPrint:function(obj){
			var self = this;
			var data = {
				module: this.module,
				method: "close_shift_summary",
				obj: obj,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					/* if(obj.printers.length > 0) {
						self.directPrint = obj.printers;
						self.handlePrintToServer(response.printData);
					} */
					self.removeEmpId();
				}
			});
		},
		removeEmpId: function () {
			var self = this;
			self.employeeId = null;
			self.employees = [];
			self.handleLocalEmployeeRemove();
			self.resetOrder();
			self.updateCheck();
		},
		getShiftTitle: function (e) {
			return e ? "Shift Open" : "Shift Close";
		},
		handleChangeTable: function () {
			bus.$emit("initTableList", {
				mode: "change",
				tableId: this.order.tableId,
			});
			//bus.$emit("initChangeTable", { tableId: this.order.tableId });
		},
		onCustomerUpdated: function (payload) {
			var self = this;
			self.order.customer = payload;
		},
		hasOrderMethod: function (method) {
			return this.orderMethods.indexOf(method) !== -1;
		},
		getTableName: function () {
			if (this.order.tableId) {
				var self = this;
				var table = this.masters.tables.find(function (single) {
					return self.order.tableId === single.id;
				});
				return table ? table.title : "Not Selected";
			} else {
				return "Not Selected";
			}
		},
		populateMeta: function () {
			var self = this;
			var data = {
				module: this.module,
				method: "populate",
				register_id: this.register,
				deviceRegisterId: this.registerId,
				employeeId: this.employeeId,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					if (response.session != null) {
						self.session = response.session;
						if (response.registerSession) {
							self.registerSession = response.registerSession;
							self.registerCheckLogin = true;
						}
						self.masters.tables = response.tables;
						self.resetOrder();
						//bus.$emit('updateOnlineOrderList',true);
						self.updateCheck();
						//setTimeout(self.updateCheck(), self.updateInterval);
						self.newRegisterSession.openingCash =
							response.registerSessionOpeningCash;
					} else {
						self.newSession.openingCash = response.sessionOpeningCash;
					}
				}
				self.sessionChecked = true;
			});
		},
		onCustomerSelect: function (customer) {
			this.order.customer = customer;
		},
		onNewCustomerCancel: function () {},
		onOrderHistory: function () {
			bus.$emit("initOrderHistory", true);
		},
		onUnacceptedOrders: function () {
			bus.$emit("initOnlineOrderList", true);
		},
		handleUpdateOnlineOrderList: function () {
			var self = this;
			var data = {
				module: "pos",
				method: "unaccepted_orders",
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				self.unacceptedOrders = response.orders;
				if (_s("playSoundOnNewOrder") && self.unacceptedOrders.length) {
					var audio = new Audio(_s("webOrderSound"));
					audio.play();
				}
			});
		},
		handleOpenSession: function () {
			bus.$emit("showUserLogin", { type: "Open" });
		},
		setOpenSession: function () {
			var self = this;
			var data = {
				module: this.module,
				method: "open_session",
				obj: this.newSession,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					if (response.session != null) {
						self.session = response.session;
						self.resetOrder();
						self.updateCheck();
					}
				}
				self.sessionChecked = true;
			});
		},
		handleOpenRegister: function () {
			this.employees = [];
			this.newRegisterSession.sessionId = this.session.id;
			this.newRegisterSession.registerId = this.registerId;
			var self = this;
			var data = {
				module: this.module,
				method: "open_register_session",
				obj: self.newRegisterSession,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					if (response.registerSession != null) {
						self.registerSession = response.registerSession;
						self.resetOrder();
						self.updateCheck();
					}else{
						self.reloadWindow();
					}
				}
			});
		},
		checkRegister: function () {
			var self = this;
			var data = {
				module: "registers",
				method: "check_register_pos",
				registerId: self.registerId,
				key: self.registerDeviceId,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				self.registerTitle = response.result.title;
				self.registerCheckLogin = response.result.registerCheckLogin;
			});
		},
		getEmployees: function () {
			var self = this;
			var data = {
				module: this.module,
				method: "pos_employees",
				session_id: self.session.id,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					self.employees = response.employees;
				}
			});
		},
		handleEmployee: function (e) {
			var emp = JSON.parse(JSON.stringify(e));
			emp.sessionId = this.session.id;
			emp.openingRegisterId = this.registerId;
			bus.$emit("showEmployeeLogin", emp);
		},
		handleEmployeeLogout: function () {
			this.removeEmpId();
		},
		handleRegisterSummary: function () {
			var event = false;
			if (this.isTabletMode) {
				event = true;
			} else {
				if(this.isPrimaryRegister){
					if(this.anyOrderOpen){
						ds_alert("Close Open Orders", "warning");
					}else{
						if(this.anyShiftOpen){
							ds_alert("Close Shifts", "warning");
						}else{
							if(this.lastRegister){
								event = true;
							}else{
								ds_alert("Close Other Registers", "warning");
							}
						}
					}
				}else{
					event = true;
				}
			}
			if(event){
				this.showSummary('register',true);
			}
		},
		handlePosRegisterSummary: function () {
			this.showSummary('register',false);
		},
		handleSessionSummary: function () {
			this.showSummary('session',true);
		},
		handleEmployeeSummary: function () {
			this.showSummary('employee',true);
		},
		showSummary: function(type,show){
			bus.$emit("showSessionSummary-"+ type, { mode: type ,show:show});
		},
		handleOrderSwitch: function () {
			bus.$emit("showOrderSwitch", true);
		},
		handleCloseSession: function (obj) {
			var self = this;
			var data = {
				module: this.module,
				method: "close_session",
				obj: obj,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					self.session = null;
					self.sessionChecked = false;
					self.order = {};
					if(obj.printers.length > 0) {
						self.directPrint = obj.printers;
						self.handlePrintToServer(response.printData);
					}
					self.handleLocalEmployeeRemove();
					window.location.reload(true);
					/*if(response.printData) {
                        self.printInvoice(response.printData);
                    }*/
				}
				//self.populateMeta();
			});
		},
		handleCloseRegister: function (obj) {
			var self = this;
			var data = {
				module: this.module,
				method: "close_register",
				obj: obj,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					if(obj.printers.length > 0) {
						self.directPrint = obj.printers;
						self.handlePrintToServer(response.printData);
				   }
					self.registerSession = null;
					self.registerCheckLogin = false;
					self.order = {};
					self.handleLocalEmployeeRemove();
					window.location.reload(true);
				}
			});
		},
		handleLocalEmployeeRemove: function () {
			localStorage.removeItem("employeeId");
			localStorage.removeItem("employeeName");
		},
		handlePlaceOrder: function (saveAndLoad) {
			if (typeof saveAndLoad === "undefined") {
				saveAndLoad = false;
			}
			//TODO make all validations
			if (this.isValidOrder()) {
				bus.$emit("posBusyStart", true);
				var self = this;
				var method = self.order.mode === "edit" ? "post" : "put";
				var data = {
					module: self.module,
					method: "order",
					obj: self.order,
				};
				var request = submitRequest(data, method, { stringify: true });
				request
					.then(function (response) {
						if (response.status === "ok") {
							self.$bvModal.hide("payment-modal");
							if (response.printData) {
								self.handlePrintToServer(response.printData);
								//self.printToServer(response.printData);
								//self.printInvoice(response.printData);
							}
							bus.$emit("resetOrder", true);
							if (saveAndLoad) {
								self.handleLoadOrder(response.orderId);
							}
							ds_alert(response.message.text, response.message.type);
						}
					})
					.finally(function (res) {
						bus.$emit("posBusyStop", false);
					});
			} else {
				bus.$emit("posBusyStop", false);
			}
		},
		handlePrintToServer: function (printData) {
			var self = this;
			if (self.directPrint !== false) {
				var payload = { printData: printData, directPrint: self.directPrint };
				bus.$emit("initDirectPrint", payload);
			} else {
				bus.$emit("initPrintServerDialog", printData);
			}
			self.directPrint = false;
		},
		handlePrintOrder: function (orderId) {
			bus.$emit("posBusyStart", true);
			var self = this;
			var data = {
				module: self.module,
				method: "order_print",
				id: orderId,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					self.resetOrder();
					if (response.printData) {
						self.handlePrintToServer(response.printData);
						//self.printToServer(response.printData);
						//self.printInvoice(response.printData);
					}
				}
				bus.$emit("posBusyStop", false);
			});
		},
		handlePrintSplitOrderReceipt: function (payload) {
			bus.$emit("posBusyStart", true);
			var self = this;
			var data = {
				module: self.module,
				method: "split_order_print",
				split_order_id: payload.splitId,
				order_id: payload.orderId,
			};
			var request = submitRequest(data, "post");
			request.then(function (response) {
				if (response.status === "ok") {
					if (response.printData) {
						self.directPrint = payload.printers;
						self.handlePrintToServer(response.printData);

					}
				}
				bus.$emit("posBusyStop", false);
			});
		},
		isValidOrder: function () {
			var validOrder = true;

			if (!this.isValidOrderAmount()) {
				validOrder = false;
			}

			if (this.order.type === "p" && _s("pickupContactMandatory")) {
				var customer_id = this.order.customer.id;
				if (!customer_id) {
					validOrder = false;
					ds_alert("Customer is mandatory for Pick up", "warning");
				}
			}

			return validOrder;
		},
		isValidOrderAmount: function () {
			if (this.order.cart.totals.grandTotal > 0 || this.order.type === "dine") {
				return true;
			} else {
				ds_alert("Zero amount order is not allowed", "warning");
				//return ds_confirm('Total Order amount is zero. You still want to continue?');
				return false;
			}
		},
		resetOrder: function (payload) {
			var orderId = null;
			var resetOrder = true;
			if (typeof payload !== "undefined") {
				if (typeof payload.orderId !== "undefined") {
					orderId = payload.orderId;
				}
			}
			if (orderId !== null) {
				if (orderId !== this.order.id) {
					resetOrder = false;
				}
			}
			if (resetOrder) {
				this.order = {
					mode: "add",
					print: _s("defaultCashierPrint"),
					type: "p",
					splitType: "none",
					split: [],
					tableId: null,
					sourceId: _s("posSourceId"),
					orderStatus: "Confirmed",
					warehouseId: 1,
					registerSessionId: this.registerSession
						? this.registerSession.id
						: null,
					openingRegisterId: this.registerId,
					closeRegisterId: "",
					sessionId: this.session.id,
					employeeId: this.employeeId,
					seatUsed: null,
					customer: {},
					promotions: {
						available: [],
						applied: [],
					},
					cloverPayment: null,
					cart: {
						items: [],
						totals: {
							payments: [],
							subTotal: 0,
							taxTotal: 0,
							taxRate: _s("defaultTaxRate"),
							freightTotal: 0,
							discount: 0,
							promotionTotal: 0,
							discountValue: "",
							discountType: "p",
							change: 0,
							tip: 0,
							grandTotal: 0,
							gratuityTotal: 0,
							gratuityRate: _s("gratuityRate"),
						},
					},
				};
				this.paymentAllowed = true;
				this.isEditable = true;
				this.directPrint = false;
			}
		},
		printInvoice: function (data) {
			var mywindow = window.open("", "Print Invoice", "height=400,width=600");
			mywindow.document.write("<html><head><title>Print Invoice</title>");
			/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
			mywindow.document.write("</head><body >");
			mywindow.document.write(data);
			mywindow.document.write("</body></html>");

			mywindow.print();
			mywindow.close();

			return true;
		},
		updateCheck: function () {
			var obj = {
				browserId: this.browserId,
				sessionId: this.session ? this.session.id : null,
				registerId: this.registerId,
				key: this.registerDeviceId,
			};
			this.checkingForUpdate = true;
			var self = this;
			if (self.canCheckUpdate) {
				var data = {
					module: self.module,
					method: "update_check",
					obj: obj,
				};
				var request = submitRequest(data, "get");
				request.then(function (response) {
					if(response.result.reload) {
                        window.location.reload(true);
                        return false;
                    }
					if(response.result.appVersion !== _s('appVersion')) {
                        self.posVersionMismatch = true;
                    }
					if (response.result.printQueueCount >= _s("printQueueWarningLimit")) {
						self.showTopWarning(
							"Waning: It might looks like the Kitchen printing feature is not working. Please contact your administrator.",
						);
					} else {
						self.hideTopWarning();
					}
					if (response.result.printQueue.length > 0 && !self.printQueueBusy) {
						bus.$emit("initQueuePrint", response.result.printQueue);
					}

					bus.$emit("itemCountUpdate", response.result.itemCount);
					if (Number(response.result.onlineOrderCount) > 0) {
						self.handleUpdateOnlineOrderList();
					}
					if (self.employeeId === null && !self.employees.length) {
						self.getEmployees();
					}
					/* if (!self.registerCheckLogin && self.registerSession === null) { */
						self.registerTitle = response.result.register.title;
						self.registerCheckLogin = response.result.register.registerCheckLogin;
						self.primaryRegister = response.result.register.primary;
					/* } */
					self.openRegister = Number(response.result.openRegister);
					self.closeRegister = Number(response.result.closeRegister);
					self.openEmpShiftCount = Number(response.result.openEmpShiftCount);
					self.openOrderCount = Number(response.result.openOrderCount);
					self.checkingForUpdate = false;
				});
				return true;
			}
			return false;
		},
		showTopWarning: function (message) {
			this.topWarning.message = message;
			this.topWarning.show = true;
		},
		hideTopWarning: function () {
			this.topWarning.message = "";
			this.topWarning.show = false;
		},
		/* updateCheckPrintQueue: function () {
        	if (this.browserId) {
        		this.checkingForPrintQueue = true;
        		var self = this;
        		if (self.canCheckPrintQueue) {
        			var data = {
        				module: self.module,
        				method: "print_queue_check",
        				browserId: self.browserId,
        			};
        			var request = submitRequest(data, "get");
        			request.then(function (res) {
        				if (res.status === "ok") {
        					self.directPrint = ["kitchen"];
        					//self.handlePrintToServer(res.printData);
        				}
        				self.checkingForPrintQueue = false;
        			});
        			return true;
        		}
        	}
        	return false;
        }, */
		handleOpenDrawer: function () {
			var self = this;
			var data = {
				printers: ["cashier"],
			};
			$.ajax({
				url: _s("printServerUrl") + "api/drawer",
				type: "POST",
				crossDomain: true,
				dataType: "json",
				data: data,
				success: function (response) {},
			});
		},
		handleRepeatOrder: function (id) {
			var self = this;
			self.resetOrder();
			var data = {
				module: self.module,
				method: "repeat_order",
				id: id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.order = response.obj;
					self.getOrderItemId();
					self.order.mode = "add";
					self.order.registerId = self.session.registerId;
					self.order.sessionId = self.session.id;
					self.order.orderStatus = "Confirmed";
					self.order.splitType = "none";
					self.order.type = "p";
					self.order.sourceId = _s("posSourceId");
					self.order.print = _s("defaultCashierPrint");
					self.order.cart.items.forEach(function(i){
						i.id = null;
					})
				} else {
					ds_alert(response.message.text, response.message.type);
				}
				self.$bvModal.hide("info-customer-modal");
			});
		},
		getOrderItemId: function () {
			var self = this;
			var items = self.order.cart.items;
			if (items) {
				items.forEach(function (item) {
					item.orderItemId = self.generateOrderItemId(item.itemId);
				});
			}
			return true;
		},
		handleLoadOrder: function (id) {
			var self = this;
			self.resetOrder();
			var data = {
				module: self.module,
				method: "order_load",
				id: id,
			};
			var request = submitRequest(data, "get");
			request.then(function (response) {
				if (response.status === "ok") {
					self.order = response.obj;
					self.order.mode = "edit";
					self.isPaymentAllowed();
					bus.$emit("existingOrderLoaded", true);
					if (self.loadSplitDialog) {
						self.loadSplitDialog = false;
						bus.$emit("initSplitOrder", true);
					}
				} else {
					ds_alert(response.message.text, response.message.type);
				}
				self.$bvModal.hide("order-history-modal");
			});
		},
		allowOpenCashDrawer: function () {
			return _s("allowOpenCashDrawer");
		},
		isPaymentAllowed: function () {
			var grandTotal = this.order.cart.totals.grandTotal;
			var paidTotal = this.order.cart.totals.payments.reduce(function (
				sum,
				payment,
			) {
				return Number(payment.amount) + Number(sum);
			},
			0);
			this.paymentAllowed = paidTotal < grandTotal;
			if(!_s('allowOrderEdit')){
				this.isEditable = paidTotal === 0;
			}else{
				var onlinePaymentIds = _s('onlineOrderPaymentIds');
				var filteredArray = this.order.cart.totals.payments.filter(function(p) {
					return onlinePaymentIds.indexOf(Number(p.paymentMethodId)) !== -1;
				});
				if(filteredArray.length > 0){
					if(this.order.orderStatus ==='Closed'){
						this.isEditable = false;
					}else{
						this.isEditable = true;
					}
				}else{
					this.isEditable = paidTotal === 0;
				}
			}
		},
		reloadWindow() {
			window.location.reload(true);
		},
		setupEvents: function () {
			var self = this;
			if (this.clearEvents()) {
				bus.$on("clearCustomer", function (payload) {
					self.order.customer = {};
				});
				bus.$on("newCustomerAdded", function (payload) {
					self.order.customer = payload;
				});
				bus.$on("onCustomerSelect", function (customer) {
					self.onCustomerSelect(customer);
				});
				bus.$on("saveOrder", function (payload) {
					if (typeof payload.directPrint !== "undefined") {
						self.directPrint = payload.directPrint;
						self.order.print = self.directPrint.length > 0;
					}
					self.order.addToPrintQueue = false;
					if (typeof payload.addToPrintQueue !== "undefined") {
						self.order.addToPrintQueue = payload.addToPrintQueue;
					}
					if (typeof payload.cloverPayment !== "undefined") {
						self.order.cloverPayment = payload.cloverPayment;
					}
					var saveAndLoad = false;
					if (
						typeof payload.saveAndLoad !== "undefined" &&
						payload.saveAndLoad === true
					) {
						saveAndLoad = true;
					}
					if (
						typeof payload.loadSplitDialog !== "undefined" &&
						payload.loadSplitDialog === true
					) {
						self.loadSplitDialog = true;
					}
					if (self.order.close === true) {
						self.order.closeRegisterId = self.registerId;
					}
					self.order.orderStatus = "Confirmed";
					self.handlePlaceOrder(saveAndLoad);
				});
				bus.$on("printOrder", function (payload) {
					var id = null;
					if (typeof payload.id !== "undefined") {
						id = payload.id;
					} else if (typeof self.order.id !== "undefined") {
						id = self.order.id;
					}
					if (id !== null) {
						self.handlePrintOrder(id);
					}
				});
				bus.$on("printSplitOrderReceipt", function (payload) {
				/* var splitId = payload.splitId;
					var orderId = payload.orderId;
					var orderId = payload.printers; */
					self.handlePrintSplitOrderReceipt(payload);
				});
				bus.$on("saveAsDraft", function () {
					bus.$emit("posBusyStart", true);
					self.order.orderStatus = "Draft";
					self.order.print = false;
					self.handlePlaceOrder();
				});
				bus.$on("setOrderEditable", function (action) {
					self.isEditable = action;
				});
				bus.$on("resetOrder", function (payload) {
					self.resetOrder(payload);
				});
				bus.$on("loadExistingOrder", function (id) {
					self.handleLoadOrder(id);
				});
				bus.$on("closeRegister", function (obj) {
					if (typeof obj.type != "undefined") {
						if (obj.type === "session") {
							self.handleCloseSession(obj);
						} else if (obj.type === "register") {
							obj.id = self.registerSession ? self.registerSession.id : null;
							self.handleCloseRegister(obj);
						} else if (obj.type === "employee") {
							self.handleEmployeeShiftClose(obj);
						}
					}
				});
				bus.$on("updateOnlineOrderList", function (obj) {
					self.handleUpdateOnlineOrderList();
				});
				bus.$on("tableSelected", function (payload) {
					self.order.tableId = payload.id;
					self.order.seatUsed = payload.seatUsed;
					self.order.print = false;
					self.handlePlaceOrder(true);
				});
				bus.$on("repeatOrder", function (id) {
					self.handleRepeatOrder(id);
				});
				bus.$on("setOrderType", function (type) {
					self.order.type = type;
				});
				bus.$on("setEmployeeId", function (payload) {
					self.employeeId = payload.employeeId;
					self.employeeName = payload.employeeName;
					self.updateCheck();
					self.resetOrder();
				});
				bus.$on("setUserLogin", function (payload) {
					if (payload.type === "Open") {
						self.setOpenSession();
					}
				});
				bus.$on("setPrintQueueBusy", function(payload) {
					self.setPrintQueueBusy(payload);
				});
			}
		},
		clearEvents: function () {
			bus.$off("clearCustomer");
			bus.$off("newCustomerAdded");
			bus.$off("onCustomerSelect");
			bus.$off("saveOrder");
			bus.$off("printOrder");
			bus.$off("printSplitOrderReceipt");
			bus.$off("saveAsDraft");
			bus.$off("resetOrder");
			bus.$off("setOrderEditable");
			bus.$off("loadExistingOrder");
			bus.$off("closeRegister");
			bus.$off("updateOnlineOrderList");
			bus.$off("tableSelected");
			bus.$off("repeatOrder");
			bus.$off("setOrderType");
			bus.$off("setEmployeeId");
			bus.$off("initOrderHistory");
			bus.$off("setUserLogin");
			bus.$off("showPromoDialog");
			bus.$off("cacheItemsLoaded");
			bus.$off("initItemEdit");
			bus.$off("initGroupItemDetails");
			bus.$off("itemCountUpdate");
			bus.$off("itemSelected");
			bus.$off("initOnlineOrderDetail");
			bus.$off("updateCartTotal");
			bus.$off("initOnlineOrderList");
			bus.$off("initRefunded");
			bus.$off("initAddCustomer");
			bus.$off("initDiscountDialog");
			bus.$off("posBusyStop");
			bus.$off("addToCart");
			bus.$off("payBoxInit");
			bus.$off("existingOrderLoaded");
			bus.$off("initSplitOrder");
			bus.$off("setPrintQueueBusy");
			bus.$off("initPrintServerDialog");
			bus.$off("initGratuityDialog");
			bus.$off("initInfoCustomer");
			bus.$off("splitPaymentCompleted");
			bus.$off("initTableSelection");
			bus.$off("initEditAddress");
			bus.$off("showUserLogin");
			bus.$off("initDirectPrint");
			return true;
		},
		getLocalStorageData: function () {
			this.browserId = localStorage.getItem("browserUniqueId");
			this.employeeId = localStorage.getItem("employeeId");
			this.employeeName = localStorage.getItem("employeeName");
			this.registerId = localStorage.getItem("registerId");
			this.registerDeviceId = localStorage.getItem("registerDeviceId");
			var type = localStorage.getItem("registerType");
			this.isTabletMode = type === "Register" ? false : true;
		},
		checkEmployeeShiftOpen: function () {},
	},
	mounted: function () {
		this.populateMeta();
	},
	created: function () {
		var self = this;
		this.getLocalStorageData();
		this.setupEvents();
		var events = ['focus','touchstart'];
		events.forEach(function(e) {
			window.addEventListener(e, function() {
				if(self.session === null) {
					self.populateMeta();
				}
			});
		});
	},
});
