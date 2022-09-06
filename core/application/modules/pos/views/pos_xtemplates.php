<script type="text/x-template" id="cart-edit-item-template">
    <div>
        <b-modal no-fade centered id="cart-edit-item-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="cart-edit-item-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="handleModalClose" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content mb-20">
                    <div v-if="showError" class="alert alert-danger alert-dismissable" role="alert">
                        <p class="mb-0 text-center">{{ message }}</p>
                    </div>
                    <div class="row">
                        <div v-if="isNameEditable" class="col-6">
                            <div class="form-group row">
                                <label class="col-12 text-danger" for="item-title">Name *</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="item-title" placeholder="Name" v-model="obj.title" />
                                </div>
                            </div>
                        </div>
                        <div v-if="isPriceEditable" class="col-6">
                            <div class="row form-group">
                                <label class="col-12 text-danger" for="closing-note">Price *</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="closing-note" placeholder="Price" v-model="obj.rate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                   <!--  <div v-if="obj.hasSpiceLevel" class="form-group row">
                        <label class="col-12" for="spice-level">Spice Level</label>
                        <div class="col-md-12">
                            <select id="spice-level" class="form-control" v-model="obj.spiceLevel">
                                <option v-for="sl in masters.spiceLevels" :value="sl.id">{{ sl.title }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="closing-note">Notes</label>
                        <div class="col-md-12">
                            <textarea class="form-control" rows="5" id="closing-note" placeholder="Note..." v-model="obj.orderItemNotes"></textarea>
                        </div>
                    </div> -->
                    <div class="row" v-if="obj.addons.length || obj.notes.length || obj.hasSpiceLevel">
                        <div class="col-md-6">
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Addons</h5>
                                </div>
                                <div class="block-content py-1 ">
                                    <div v-if="obj.addons.length" class="row overflow-y-auto mh-300">
                                        <div v-for="(addon,index) in getGroupedAddons" class="col-md-12">
                                            <label class="css-control css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" :id="addon.itemId" @change="updateAddonSelection" :data-item-id="addon.itemId" :value="addon.itemId" v-model="addon.enabled">
                                                <span class="css-control-indicator"></span>&nbsp;{{ addon.title }}&nbsp;(+{{ addon.rate }})
                                            </label>
                                            <div v-if="addon.enabled" class="btn-group pull-right" data-toggle="buttons" role="group" aria-label="Second group">
                                                <button type="button" class="btn btn-primary" @click="handleDecrement(addon.itemId)">-</button>
                                                <button type="button" class="btn btn-secondary" disabled>{{ addon.quantity }}</button>
                                                <button type="button" class="btn btn-primary" @click="handleIncrement(addon.itemId)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="!obj.addons.length" class="row">
                                        <div class="col-md-12 font-14">Not Applicable</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Notes</h5>
                                </div>
                                <div class="block-content py-1">
                                    <div v-if="obj.notes.length" class="row overflow-y-auto mh-300p">
                                        <div v-for="single in obj.notes" class="col-md-12">
                                            <label class="css-control css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" :value="single" v-model="obj.selectedNotes">
                                                <span class="css-control-indicator"></span>&nbsp;{{ single.title }}
                                            </label>
                                        </div>
                                    </div>
                                    <div v-if="!obj.notes.length" class="row">
                                        <div class="col-md-12 font-14">Not Applicable</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Spice Levels</h5>
                                </div>
                                <div class="block-content py-1">
                                    <div v-if="obj.hasSpiceLevel" class="row">
                                        <div class="col-md-12" v-for="sl in masters.spiceLevels">
                                            <label class="css-control css-control css-control-danger css-radio">
                                                <input type="radio" class="css-control-input" :value="sl.id" v-model="obj.spiceLevel">
                                                <span class="css-control-indicator"></span>&nbsp;{{ sl.title }}
                                            </label>
                                        </div>
                                    </div>
                                    <div v-if="!obj.hasSpiceLevel" class="row">
                                        <div class="col-md-12 font-14">Not Applicable</div>
                                    </div>
                                </div>
                            </div>
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Custom Notes</h5>
                                </div>
                                <div class="block-content py-1">
                                    <textarea class="form-control border-0 px-0" rows="5" id="custom-note" placeholder="Note..." v-model="obj.orderItemNotes"></textarea>
                                </div>
                            </div>
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Price</h5>
                                </div>
                                <div class="block-content py-1">
                                    <h4 class="text-danger">{{ getButtonTitle }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-alt-danger" @click="handleConfirm">Confirm</button>
                        <button type="button" class="btn btn-alt-secondary" @click="handleModalClose">Cancel</button>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="promotion-dialog-template">
    <div>
        <b-modal no-fade :id="modal.id" centered size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="promotion-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 px-15 py-20">
                    <div class="row">
                        <div class="col-3">
                            <div class="list-group push">
                                <a v-for="(single,i) in promotions.available" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="spi==i ? 'active':''" href="javascript:void(0)">
                                    {{ i+1 }}. {{ single.title }}
                                </a>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h5><small>Offer Duration</small><br/>{{ sp.startDate | beautifyDateTime }} - {{ sp.endDate | beautifyDateTime }}</h5>
                                </div>
                                <div class="col-6 py-2 bg-gray-lighter">
                                    <h5>Buy 1 of the following</h5>
                                    <p v-for="c in getCriteriaInclude" class="mb-2 font-16">{{ getItemName(c.itemId) }}<span v-if="isCartItem(c.itemId)" class="ml-1 font-10 badge badge-danger">IN CART</span><span class="pull-right">{{ getItemPriceText(c.itemId) }}</span></p>
                                </div>
                                <div class="col-6 py-2 bg-gray-light">
                                    <h5>Get 1 of the following</h5>
                                    <p v-for="r in getRewardInclude" class="mb-2 font-16">{{ getItemName(r.itemId) }}<span v-if="isCartItem(r.itemId)" class="ml-1 font-10 badge badge-danger">IN CART</span><span class="pull-right">{{ getItemDiscountedPriceText(r.itemId,r) }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="cart-template">
    <div id="cart-container" class="row">
        <div v-if="!cart.items.length" class="col-md-12 overflow-y-auto" :class="getClass()">
            <h5 class="text-center m-0 py-3 border border-info">No Items</h5>
        </div>
        <div v-if="cart.items.length" class="col-md-12 overflow-y-auto" :class="getClass()">
        <component :is="activeListType" :is-editable="isEditable" :order-mode="order.mode" :cart="cart"></component>
        </div>
        <div v-if="hasPromotion" class="col-12">
            <a @click.prevent="showPromoDialog" class="block block-transparent bg-earth mb-2 block-rounded" href="javascript:void(0)">
                <div class="block-content block-content-full py-1 d-flex align-items-center justify-content-between">
                    <div class="ml-1 py-1">
                        <p class="font-size-sm text-uppercase font-w600 text-white-op mb-0">
                            <i class="fa fa-dollar"></i> Promotion Available
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-12">
            <table class="table table-vcenter table-bordered table-sm">
                <tbody>
                <tr>
                    <th class="w-60">Subtotal</th>
                    <th class="w-40 text-right">{{ cart.totals.subTotal | toTwoDecimal }}</th>
                </tr>
                <tr v-if="orderType==='d'">
                    <th class="w-60">Delivery Charges</th>
                    <th class="w-40 text-right"><input type="text" class="form-control text-right" v-model="cart.totals.freightTotal"></th>
                </tr>
                <tr v-if="hasAppliedPromotion">
                    <th class="w-60">Promotions<a class="ml-2" :title="getAppliedPromotionsText" href="javascript:void(0)"><i class="fa fa-info-circle text-earth"></i></a></th>
                    <th class="w-40 text-right">{{ cart.totals.promotionTotal | toTwoDecimal }}</th>
                </tr>
                <tr v-if="discountAllowed">
                    <th class="w-60">
                        <div class="d-inline-block"><span>Discount</span><span v-if="cart.totals.discountType=='p' && cart.totals.discount>0">&nbsp;({{ cart.totals.discountValue }}%)</span>
                            <a class="text-success font-16" title="Apply Discount" v-if="cart.totals.discount==0 && cart.items.length && isEditable" href="javascript:void(0);" @click.prevent="handleOpenDiscountDialog">&nbsp;<i class="fa fa-plus"></i></a>
                            <a class="text-danger font-16" title="Remove Discount" v-if="cart.totals.discount>0" href="javascript:void(0);" @click.prevent="handleClearDiscount">&nbsp;<i class="fa fa-minus"></i></a>
                        </div>
                    </th>
                    <th class="w-40 text-right">{{ cart.totals.discount | toTwoDecimal }}</th>
                </tr>
                <tr v-if="displayGratuity">
                    <th class="w-60">
                        <div class="d-inline-block"><span>Gratuity</span>&nbsp;({{ cart.totals.gratuityRate }}%)</span>
                            <a class="text-danger font-16" title="Change Gratuity Rate" v-if="allowGratuityChange && cart.items.length && isEditable" href="javascript:void(0);" @click.prevent="handleOpenGratuityDialog">&nbsp;<i class="fa fa-edit"></i></a>
                        </div>
                    </th>
                    <th class="text-right w-40">{{ cart.totals.gratuityTotal | toTwoDecimal }}</th>
                </tr>
                <tr>
                    <th class="w-60">Tax ({{ cart.totals.taxRate }}%)</th>
                    <th class="text-right w-40">{{ cart.totals.taxTotal | toTwoDecimal }}</th>
                </tr>
                <tr>
                    <th class="w-60">Grand Total</th>
                    <th class="text-right w-40">{{ cart.totals.grandTotal | toTwoDecimal }}</th>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-if="isTabletMode" class="col-md-12">
            <div class="row">
                <div class="col-md-6 mb-3">
                   <a href="javascript:void(0)" @click.prevent="handleTabletOrder" :class="(cart.items.length<1 || orderPlacing) ? 'disabled':''" class="btn btn-danger border-0 w-100 py-3">To Kitchen</a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="javascript:void(0)" @click.prevent="handleResetOrder" :class="orderPlacing ? 'disabled':''" class="btn btn-danger border-0 w-100 py-3">Clear Order</a>
                </div>
            </div>
        </div>
        <div v-else class="col-md-12">
            <div class="row">
                <div class="mb-3" :class="enableSplitOrders ? 'col-md-4' : 'col-md-6'">
                    <a href="javascript:void(0)" @click.prevent="handlePayment" :class="(cart.items.length<1 || order.orderStatus!=='Confirmed' || orderPlacing || order.splitType !== 'none') ? 'disabled':''" class="btn btn-lg btn-primary border-0 w-100 py-3">Pay</a>
                </div>
                <div v-if="enableSplitOrders" class="col-md-4 mb-3">
                    <a href="javascript:void(0)" @click.prevent="handleSplitOrder" :class="canSplit ? 'disabled':''" class="btn btn-lg btn-alt-primary border-0 w-100 py-3">Split Order</a>
                </div>
                <div class="mb-3" :class="enableSplitOrders ? 'col-md-4' : 'col-md-6'">
                    <a href="javascript:void(0)" @click.prevent="handleUpdateOrder" v-if="order.mode==='add'" :class="(cart.items.length<1 || orderPlacing) ? 'disabled':''" class="btn btn-danger border-0 w-100 py-3">Print</a>
                    <a href="javascript:void(0)" @click.prevent="handleUpdateOrder" v-if="order.mode==='edit'" :class="(cart.items.length<1 || orderPlacing) ? 'disabled':''" class="btn btn-danger border-0 w-100 py-3">Print</a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="javascript:void(0)" @click.prevent="handlePutOnHold" :class="isHoldAllowed ? '':'disabled'" class="btn btn-secondary border-0 w-100 py-3">Put on Hold</a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="javascript:void(0)" @click.prevent="handleResetOrder" :class="orderPlacing ? 'disabled':''" class="btn btn-danger border-0 w-100 py-3">Clear Order</a>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="cart-accordian-view-template">
    <div id="cart-items" role="tablist" aria-multiselectable="true">
        <div v-for="(single,index) in cart.items" class="block block-bordered mb-0">
            <div class="block-header" role="tab" :id="'item-'+single.itemId">
                <a class="font-w600 text-dark collapsed" data-toggle="collapse" data-parent="#cart-items" :href="'#item-body-'+single.itemId" aria-expanded="false" :aria-controls="'item-body-'+single.itemId">{{ single.title }}</a>
                <a href="javascript:void(0)" @click.prevent="handleRemoveItem(index)" class="pull-right text-danger"><i class="fa fa-trash"></i></a>
            </div>
            <div :id="'item-body-'+single.itemId" class="collapse" role="tabpanel" :aria-labelledby="'item-'+single.itemId" data-parent="#cart-items">
                <div class="block-content pt-1">
                    <div class="row">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-12 text-right" :for="'item-quantity-'+single.id">Quantity</label>
                                <div class="col-md-12">
                                    <input v-model="single.quantity" type="text" class="form-control text-right" :id="'item-quantity-'+single.id" placeholder="Quantity">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="col-md-12 text-right" for="'item-rate-'+single.id">Price</label>
                                <div class="col-md-12">
                                    <input :value="single.rate | toTwoDecimal" type="text" class="form-control text-right" readonly id="'item-rate-'+single.id" placeholder="Price">
                                </div>
                            </div>
                        </div>
                        <?php if ( 1 == 2 ) {?>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-md-12 text-right" for="'item-discount-'+single.id">Discount (%)</label>
                                    <div class="col-md-12">
                                        <input v-model="single.discount" type="text" class="form-control text-right" id="'item-discount-'+single.id" placeholder="Discount">
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-12" for="'item-note-'+single.id">Note</label>
                                <div class="col-md-12">
                                    <input v-model="single.notes" type="text" class="form-control" id="'item-note-'+single.id" placeholder="Type to add notes...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="cart-table-view-template">
    <div id="cart-items">
        <table class="table table-vcenter table-sm">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(single,index) in cartItems">
                    <td>
                        {{ single.title }}<a v-if="isEditable && single.id === null && (single.selectedNotes.length || single.addons.length || single.hasSpiceLevel)" @click.prevent="handleEditItem(index)" class="ml-2 font-14 text-danger" title="Edit this Item" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                        <span v-if="hasAddons(single.addons)"><br/>{{ getAddons(single.addons) }}</span>
                        <span v-if="single.selectedNotes.length"><br/>{{ getNotes(single.selectedNotes) }}</span>
                        <span v-if="single.hasSpiceLevel"><br/>Spice:&nbsp;{{ single.spiceLevel }}</span>
                        <span v-if="single.orderItemNotes.length"><br/>Note:&nbsp;{{ single.orderItemNotes }}</span>
                    </td>
                    <td class="text-center">{{ single.rate | toTwoDecimal }}</td>
                    <td class="text-center">
                        <div class="btn-group" data-toggle="buttons" role="group" aria-label="Second group">
                            <button type="button" class="btn btn-primary" @click="handleDecrement(index)" :disabled="!isEditable">-</button>
                            <button type="button" class="btn btn-secondary" disabled>{{ single.quantity }}</button>
                            <button type="button" class="btn btn-primary" @click="handleIncrement(index)" :disabled="!isEditable">+</button>
                        </div>
                    </td>
                    <td class="text-right">{{ getAmount(single.rate,single.quantity) | toTwoDecimal }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</script>
<script type="text/x-template" id="item-template">
    <div id="item-container" class="block">
        <div class="block-content block-content-full p-3">
            <div class="row">
                <div v-if="showItemSearch" class="col-md-9 mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" v-model="itemSearchString" placeholder="Search products...">
                        <div class="input-group-append">
                            <button type="button" @click="handleClearSearch" class="btn btn-primary">Clear</button>
                        </div>
                    </div>
                </div>
                <div v-if="showItemDisplayType" class="col-md-3 mb-3 float-right">
                    <div class="btn-group float-right" role="group">
                        <button type="button" @click="activeListType='item-list-view'" title="Switch to List View" class="btn btn-primary" :class="activeListType == 'item-list-view' ? 'active' : ''">
                            <i class="fa fa-th-list"></i>
                        </button>
                        <button type="button" @click="activeListType='item-thumb-view'" title="Switch to Thumb View" class="btn btn-primary" :class="activeListType == 'item-thumb-view' ? 'active' : ''">
                            <i class="fa fa-th-large"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="nav nav-pills push">
                        <li class="nav-item" v-for="single in categories"><a @click.prevent="handleChangeCategory(single.id)" class="nav-link mr-2 mb-2 border border-primary" :class="single.id == categoryId ? 'active' : ''" href="javascript:void(0)">{{ single.value }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="row overflow-y-auto" :class="getClass()">
                <div class="col-md-12">
                    <component :is-editable="isEditable" :is="activeListType" :icons="icons" :cachedItems="cachedItems" :filteredItems="filteredItems" @itemSelected="handleItemClick"></component>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="item-thumb-view-template">
    <div class="row">
        <div v-for="(single,index) in filteredItems" class="col-xl-3 col-lg-6 col-md-12 col-sm-6 mb-10">
            <div class="border-info border">
                <a @click.prevent="handleItemClick(index)" href="javascript:void(0);" class="w-100 text-secondary d-block text-center">
                    <img class="img-fluid options-item" :src="single.image | imagePath" alt="">
                    <p class="text-center mt-1">{{ single.title }}</p>
                </a>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="item-list-view-template">
    <div class="row">
        <div v-for="(single,index) in filteredItems" class="col-xl-4 col-lg-12 col-md-12 col-sm-6">
            <div @click="handleItemClick(index)" class="block block-bordered block-rounded cursor-pointer mb-2">
                <div class="block-header">
                    <div class="font-w600 text-black float-left">
                        <i v-if="showItemIcons" :class="getIconClass(single.icon)" class="mr-2"></i>
                        {{ single.title }}
                    </div>
                    <div class="float-right">
                        <i v-if="!showItemVegNVeg" class="fa fa-arrow-right"></i>
                        <img v-if="showItemVegNVeg && hasVegNVeg(single.id,'nveg')" :src="nVegImg()" class="h-15p">
                        <img v-if="showItemVegNVeg && hasVegNVeg(single.id,'veg')" :src="vegImg()" class="h-15p">
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="item-detail-template">
    <div>
        <b-modal no-fade centered id="item-detail-modal" size="lg" hide-header hide-footer body-class="p-0">
            <div id="item-detail-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ item.name }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('item-detail-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="block">
                                <div class="block-content overflow-hidden pl-0 pt-0">
                                    <b-tabs content-class="mt-3">
                                        <b-tab title="Image" active>
                                            <img :src="item.imageCachePath | imagePath" alt="" class="img-fluid options-item border-info border"/>
                                        </b-tab>
                                        <b-tab title="Features">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2">Features</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="single in item.features">
                                                        <td>{{ getFeatureLabel(single.featureId) }}</td>
                                                        <th>{{ single.title }}</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </b-tab>
                                    </b-tabs>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Unit</th>
                                            <th class="text-right">Rate</th>
                                            <th class="text-right">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="single in item.prices">
                                            <td>{{ getUnitLabel(single.unitId) }}</td>
                                            <td class="text-right">{{ single.salePrice }}</td>
                                            <td v-if="single.unitId==item.unit" class="text-right mw-75p"><input v-model.number="item.quantity" v-float @blur="rateCalculation('quantity')" class="form-control text-right" type="text"></td>
                                            <td v-if="single.unitId==item.saleUnit" class="text-right mw-75p"><input v-model.number="item.unitQuantity" v-float @blur="rateCalculation('unitQuantity')" class="form-control text-right" type="text"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <h1>{{ item.rate * item.quantity | toThreeDecimal | beautifyCurrency }}</h1>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button @click="handleAddToCart" class="btn btn-lg btn-danger"><i class="fa fa-cart-plus"></i>&nbsp;Add to Cart</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="group-item-detail-template">
    <div>
        <b-modal id="group-item-detail-modal" no-fade centered :size="item.addons.length || item.notes.length || item.hasSpiceLevel ? 'xl' : 'lg'" hide-header hide-footer body-class="p-0">
            <div id="group-item-detail-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ item.baseName }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="handleModalClose" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div  class="block-content pt-20">
                    <div v-if="isOpenItem(item.id)" class="row">
                        <div class="col-6">
                            <div v-if="isNameEditable" class="form-group row">
                                <label class="col-12 text-danger" for="closing-title">Name *</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="item-title" placeholder="Name" v-model="item.baseName" />
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div  v-if="isPriceEditable"  class="form-group row">
                                <label class="col-12 text-danger" for="closing-note">Price *</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="item-note" placeholder="Price" v-model="customPrice"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="item.addons.length || item.notes.length || item.hasSpiceLevel">
                        <div class="col-md-6">
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Addons</h5>
                                </div>
                                <div class="block-content py-1 ">
                                    <div v-if="item.addons.length" class="row overflow-y-auto mh-300">
                                        <div v-for="(addon,index) in getGroupedAddons" class="col-md-12">
                                            <label class="css-control css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" :id="addon.itemId" @change="updateAddonSelection" :data-item-id="addon.itemId" :value="addon.itemId" v-model="addon.enabled">
                                                <span class="css-control-indicator"></span>&nbsp;{{ addon.title }}&nbsp;(+{{ addon.rate }})
                                            </label>
                                            <div v-if="addon.enabled" class="btn-group pull-right" data-toggle="buttons" role="group" aria-label="Second group">
                                                <button type="button" class="btn btn-primary" @click="handleDecrement(addon.itemId)">-</button>
                                                <button type="button" class="btn btn-secondary" disabled>{{ addon.quantity }}</button>
                                                <button type="button" class="btn btn-primary" @click="handleIncrement(addon.itemId)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="!item.addons.length" class="row">
                                        <div class="col-md-12 font-14">Not Applicable</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Notes</h5>
                                </div>
                                <div class="block-content py-1">
                                    <div v-if="item.notes.length" class="row overflow-y-auto mh-300p">
                                        <div v-for="single in item.notes" class="col-md-12">
                                            <label class="css-control css-control css-control-primary css-checkbox">
                                                <input type="checkbox" class="css-control-input" :value="single" v-model="item.selectedNotes">
                                                <span class="css-control-indicator"></span>&nbsp;{{ single.title }}
                                            </label>
                                        </div>
                                    </div>
                                    <div v-if="!item.notes.length" class="row">
                                        <div class="col-md-12 font-14">Not Applicable</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Spice Levels</h5>
                                </div>
                                <div class="block-content py-1">
                                    <div v-if="item.hasSpiceLevel" class="row">
                                        <div class="col-md-12" v-for="sl in masters.spiceLevels">
                                            <label class="css-control css-control css-control-danger css-radio">
                                                <input type="radio" class="css-control-input" :value="sl.id" v-model="item.spiceLevel">
                                                <span class="css-control-indicator"></span>&nbsp;{{ sl.title }}
                                            </label>
                                        </div>
                                    </div>
                                    <div v-if="!item.hasSpiceLevel" class="row">
                                        <div class="col-md-12 font-14">Not Applicable</div>
                                    </div>
                                </div>
                            </div>
                            <div class="block block-bordered">
                                <div class="block-header py-1">
                                    <h5 class="mb-0 mt-10 font-14">Custom Notes</h5>
                                </div>
                                <div class="block-content py-1">
                                    <textarea class="form-control border-0 px-0" rows="5" id="custom-note" placeholder="Note..." v-model="item.orderItemNotes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div v-for="(single,index) in item.variations" @click="handleAddToCart(index)" class="col-md-auto mb-20">
                            <button :class="(single.isVeg==='1')?'btn-success':'btn-danger'" class="btn btn-lg text-center w-100">{{ getButtonTitle(single) }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="customer-template">
    <div class="row">
        <div class="col-md-12">
            <span>Find or add Customer</span>
        </div>
        <div class="col-md-8 mb-2">
            <vue-multiselect :disabled="!isEditable" id="ajax" @select="getDetails" :internal-search="false" v-model="selectedCustomer" placeholder="Find Customer by Name, Email or Mobile" @search-change="queryCustomer" :options="queryCustomers" :loading="isLoading" label="value" track-by="id">
                <template slot="noResult">
                    <strong>Add <a href="javascript:void(0)" @click.prevent="handleNewCustomer">{{ searchString }}</a></strong>
                </template>
                <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.value }}</strong></template>
            </vue-multiselect>
        </div>
        <div class="col-md-4 mb-2 text-right">
            <button v-if="!isCustomerLoaded" :disabled="!isEditable" @click="handleNewCustomer" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;New</button>
            <button v-if="customerLoaded" :disabled="!isEditable" @click="handleInfoCustomer(customer.id)" class="btn btn-primary" title="View Customer"><i class="fa fa-user" :class="getClassCustomer"></i></button>
            <button v-if="isCustomerLoaded" :disabled="!isEditable" @click="clearSearch" class="btn btn-danger">Clear</button>
        </div>
        <div v-if="customer.id" class="col-md-12">
           <!--  <div class="table">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td><small>Customer Name</small><br>{{ customer.displayName }}</td>
                            <td></td>
                        </tr>
                        <tr v-if="customer.phone">
                            <th>Mobile</th>
                            <td>{{ customer.phone }}</td>
                        </tr>
                        <tr v-if="customer.email">
                            <th>Email</th>
                            <td>{{ customer.email }}</td>
                        </tr>
                        <tr v-if="customer.group.posDiscount > 0 && allowCustomerGroup">
                            <th>Eligible Discount</th>
                            <td><span class="mr-2">{{customer.group.posDiscount}}%</span><a :disabled="!discountApplyBtn" @click="handleDiscountDialog" class="text-danger">Apply</a></td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            <div class="row">
                <div class="col-6 text-left"><small>Customer Name</small><br>{{ customer.displayName }}</div>
                <div class="col-6 text-left"><small>Email</small><br>{{ customer.email ? customer.email : '-' }} </div>
            </div>
            <div class="row">
                <div class="col-6 text-left mt-2" v-if="customer.group.posDiscount > 0 && allowCustomerGroup"><small>Eligible Discount</small><br><span class="mr-2">{{customer.group.posDiscount}}%</span><a href="#" v-if="discountApplyBtn" @click.prevent="handleDiscountDialog" class="text-danger">Apply</a></div>
                <div class="text-left" :class="allowCustomerGroup ? 'col-6' : 'col-12'"> <small>Mobile</small><br>{{ customer.phone ? customer.phone : '' }}</div>
            </div>

        </div>
    </div>
</script>
<script type="text/x-template" id="add-customer-template">
    <?php $code = 'customer';?>
    <div>
        <b-modal no-fade centered :id="modal.id" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Add Customer</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('add-customer-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div id="add-customer-block" class="block-content bg-gray-light">
                    <form id="frm-add-customer" data-parsley-validate="true" @submit.prevent="handleSubmit">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="block block-rounded block-bordered">
                                                <div class="block-content block-content-full">
                                                    <div class="row">
                                                        <div class="col-md-12 text-right"><h6>Customer Information</h6></div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => 'customer-customer-id', 'title' => 'Customer Id', 'attribute' => 'disabled', 'vue_model' => 'customer.customerId'] ); ?></div>
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code . '-salutation'; ?>">Customer Name</label>
                                                                <div class="col-md-<?php echo TEXT_COLUMNS ?>">
                                                                    <?php echo get_text( ['id' => $code . '-first-name', 'title' => 'First Name', 'placeholder' => 'First Name', 'class' => 'form-control mb-2', 'attribute' => '@blur="onName"', 'vue_model' => $code . '.firstName'], 'text', true ); ?>
                                                                    <?php echo get_text( ['id' => $code . '-last-name', 'title' => 'Last Name', 'placeholder' => 'Last Name', 'attribute' => '@blur="onName"', 'vue_model' => $code . '.lastName'], 'text', true ); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => 'customer-display-name', 'title' => 'Display Name', 'attribute' => 'required', 'vue_model' => 'customer.displayName'] ); ?></div>
                                                        <div class="col-md-12"><?php $required = ( _get_var( 'customer_user_field', 'mobile' ) == 'mobile' ) ? 'required' : '';
echo get_text( ['id' => 'customer-phone', 'title' => 'Mobile', 'attribute' => $required . ' ref="phone"', 'vue_model' => 'customer.phone'] );?></div>
                                                        <div class="col-md-12"><?php $required = ( _get_var( 'customer_user_field', 'mobile' ) == 'email' ) ? 'required' : '';
echo get_text( ['id' => 'customer-email', 'title' => 'Email', 'attribute' => $required . ' ref="email"', 'vue_model' => 'customer.email'], 'email' );?></div>
                                                        <div v-if="allowCustomerGroup" class="col-md-12 mb-3">
                                                            <div class="row">
                                                                <label class="col-md-3 col-form-label" for="customer-group">Customer Group</label>
                                                                <div class="col-md-9"><?php echo get_select( ['id' => $code . '-groupId', 'title' => 'Customer Group', 'attribute' => '', 'vue_model' => $code . '.groupId', 'vue_for' => 'masters.groups'], [], 'value', 'id', true ); ?></div>
                                                            </div>
                                                        </div>
                                                        <div v-if="isCustomFields('fullVaccinated')" class="col-md-12 mb-3">
                                                            <div class="row">
                                                                <label class="col-md-3 col-form-label" for="customer-full-vaccinated">Fully Vaccinated</label>
                                                                <div class="col-md-9"><?php echo get_select( ['id' => $code . '-full-vaccinated', 'title' => 'Full Vaccinated', 'attribute' => '', 'vue_model' => $code . '.fullVaccinated', 'vue_for' => 'vaccination'], [], 'value', 'id', true ); ?></div>
                                                            </div>
                                                        </div>
                                                        <div v-if="isCustomFields('memberNumber')" class="col-md-12"><?php echo get_text( ['id' => $code . '-member-number', 'title' => 'Member Number', 'attribute' => '', 'vue_model' => $code . '.memberNumber'] ); ?></div>
                                                        <div v-if="allowCustomerNotes"  class="col-md-12">
                                                            <?php echo get_textarea( ['id' => $code . '-customer-notes', 'title' => 'Notes', 'attribute' => '', 'vue_model' => $code . '.notes'] ); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="block block-rounded block-bordered">
                                                <div class="block-content block-content-full">
                                                    <div class="row">
                                                        <div class="col-md-12 text-right"><h6>Address</h6></div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => $code . '-address-title', 'title' => 'Title', 'attribute' => '', 'vue_model' => $code . '.address.title'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => $code . '-address-address-1', 'title' => 'Address 1', 'attribute' => '', 'vue_model' => $code . '.address.address1'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => $code . '-address-address-2', 'title' => 'Address 2', 'attribute' => '', 'vue_model' => $code . '.address.address2'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_select( ['id' => $code . '-address-country', 'title' => 'Country', 'attribute' => 'disabled', 'vue_model' => $code . '.address.countryId', 'vue_for' => 'masters.countries'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_select( ['id' => $code . '-address-state', 'title' => 'State', 'attribute' => '', 'vue_model' => $code . '.address.stateId', 'vue_for' => 'masters.states'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_select( ['id' => $code . '-address-city', 'title' => 'City', 'attribute' => '', 'vue_model' => $code . '.address.cityId', 'vue_for' => 'masters.cities'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => $code . '-address-zip-code', 'title' => 'Zip Code', 'attribute' => '', 'vue_model' => $code . '.address.zipCode'] ); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="1==2" class="col-md-12">
                                    <div class="block block-rounded block-bordered">
                                        <div class="block-content block-content-full">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <?php if ( 1 == 2 ) {?>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => $code . '-customer-id', 'title' => 'Customer ID', 'attribute' => 'readonly', 'vue_model' => $code . '.customerId'] ); ?></div>
                                                        <?php }?>
                                                        <div class="col-md-12"><?php $required = ( _get_var( 'customer_user_field', 'mobile' ) == 'mobile' ) ? 'required' : '';
echo get_text( ['id' => $code . '-phone', 'title' => 'Mobile', 'attribute' => $required . ' ref="phone"', 'placeholder' => 'e.g. 9999999999', 'vue_model' => $code . '.phone'] );?></div>
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code . '-salutation'; ?>">Customer Name</label>
                                                                <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                                    <?php echo get_select( ['id' => $code . '-salutation', 'title' => 'Salutation', 'class' => 'mr-2', 'attribute' => 'v-if="!isLimitedDisplayMode"', 'vue_model' => $code . '.salutation', 'vue_for' => 'masters.salutations'], [], 'value', 'id', true ); ?>
                                                                    <?php echo get_text( ['id' => $code . '-first-name', 'title' => 'First Name', 'placeholder' => 'First Name', 'class' => 'mr-2', 'attribute' => '@blur="onName"', 'vue_model' => $code . '.firstName'], 'text', true ); ?>
                                                                    <?php echo get_text( ['id' => $code . '-last-name', 'title' => 'Last Name', 'placeholder' => 'Last Name', 'attribute' => '@blur="onName"', 'vue_model' => $code . '.lastName'], 'text', true ); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div v-if="!isLimitedDisplayMode" class="col-md-12"><?php echo get_text( ['id' => $code . '-company-name', 'title' => 'Company Name', 'attribute' => '@blur="onCompanyName"', 'vue_model' => $code . '.companyName'] ); ?></div>
                                                        <div class="col-md-12"><?php echo get_text( ['id' => $code . '-display-name', 'title' => 'Display Name', 'attribute' => 'required', 'vue_model' => $code . '.displayName'] ); ?></div>
                                                        <div class="col-md-12"><?php $required = ( _get_var( 'customer_user_field', 'mobile' ) == 'email' ) ? 'required' : '';
echo get_text( ['id' => $code . '-email', 'title' => 'Email', 'attribute' => $required . ' ref="email"', 'placeholder' => 'example@email.com', 'vue_model' => $code . '.email'], 'email' );?></div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div v-if="isCustomFields('memberNumber')" class="col-sm">
                                                                    <div class="row">
                                                                    <label class="col-sm col-form-label" for="customer-full-vaccinated">Fully Vaccinated</label>
                                                                    <div class="col-sm"><?php echo get_select( ['id' => $code . '-full-vaccinated', 'title' => 'Full Vaccinated', 'attribute' => '', 'vue_model' => $code . '.fullVaccinated', 'vue_for' => 'vaccination'], [], 'value', 'id', true ); ?></div>
                                                                    </div>
                                                                </div>
                                                                <div v-if="isCustomFields('fullVaccinated')" class="col-sm"><?php echo get_text( ['id' => $code . '-full-vaccinated', 'title' => 'Member Number', 'attribute' => '', 'vue_model' => $code . '.memberNumber'] ); ?></div>
                                                            </div>
                                                        </div>
                                                        <?php if ( 1 == 2 ) {?>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-designation', 'title' => 'Designation', 'attribute' => '', 'vue_model' => $code . '.designation'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-department', 'title' => 'Department', 'attribute' => '', 'vue_model' => $code . '.department'] ); ?></div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!isLimitedDisplayMode" class="col-md-12">
                                    <div class="block block-rounded block-bordered">
                                        <div class="block-content">
                                            <b-tabs>
                                                <b-tab title="Address">
                                                    <div class="row py-4">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12"><h6>Billing Address</h6></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-attention', 'title' => 'Attention', 'attribute' => '', 'vue_model' => $code . '.billing.attention'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-address-1', 'title' => 'Address 1', 'attribute' => '', 'vue_model' => $code . '.billing.address1'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-address-2', 'title' => 'Address 2', 'attribute' => '', 'vue_model' => $code . '.billing.address2'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_select( ['id' => $code . '-billing-country', 'title' => 'Country', 'attribute' => '', 'vue_model' => $code . '.billing.country', 'vue_for' => 'masters.countries'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_select( ['id' => $code . '-billing-state', 'title' => 'State', 'attribute' => '', 'vue_model' => $code . '.billing.state', 'vue_for' => 'masters.states.billing'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-city', 'title' => 'City', 'attribute' => '', 'vue_model' => $code . '.billing.city'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-zip-code', 'title' => 'Zip Code', 'attribute' => '', 'vue_model' => $code . '.billing.zipCode'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-phone', 'title' => 'Phone', 'attribute' => '', 'vue_model' => $code . '.billing.phone'] ); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12"><h6>Shipping Address<a @click.prevent="copyBillingAddress" href="javascript:void(0)" class="float-right font-12">Copy from billing address</a></h6></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-attention', 'title' => 'Attention', 'attribute' => '', 'vue_model' => $code . '.shipping.attention'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-address-1', 'title' => 'Address 1', 'attribute' => '', 'vue_model' => $code . '.shipping.address1'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-address-2', 'title' => 'Address 2', 'attribute' => '', 'vue_model' => $code . '.shipping.address2'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_select( ['id' => $code . '-shipping-country', 'title' => 'Country', 'attribute' => '', 'vue_model' => $code . '.shipping.country', 'vue_for' => 'masters.countries'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_select( ['id' => $code . '-shipping-state', 'title' => 'State', 'attribute' => '', 'vue_model' => $code . '.shipping.state', 'vue_for' => 'masters.states.shipping'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-city', 'title' => 'City', 'attribute' => '', 'vue_model' => $code . '.shipping.city'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-zip-code', 'title' => 'Zip Code', 'attribute' => '', 'vue_model' => $code . '.shipping.zipCode'] ); ?></div>
                                                                <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-phone', 'title' => 'Phone', 'attribute' => '', 'vue_model' => $code . '.shipping.phone'] ); ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-tab>
                                                <b-tab title="Tax &amp; Payment Details">
                                                    <div class="row py-4">
                                                        <div class="col-md-7"><?php echo get_select( ['id' => $code . '-currency', 'title' => 'Currency', 'class' => 'mr-2', 'attribute' => 'required', 'vue_model' => $code . '.currencyId', 'vue_for' => 'masters.currencies'], [], 'value', 'id' ); ?></div>
                                                        <?php if ( 1 == 2 ) {?>
                                                            <div class="col-md-7"><?php echo get_select( ['id' => $code . '-price-list', 'title' => 'Group', 'class' => 'mr-2', 'attribute' => '', 'vue_model' => $code . '.priceListId', 'vue_for' => 'priceLists'], [], 'value', 'id' ); ?></div>
                                                            <div class="col-md-7"><?php echo get_select( ['id' => $code . '-payment-terms', 'title' => 'Payment Terms', 'class' => 'mr-2', 'attribute' => '', 'vue_model' => $code . '.paymentTerm', 'vue_for' => 'paymentTerms'], [], 'value', 'id' ); ?></div>
                                                        <?php }?>
                                                    </div>
                                                </b-tab>
                                                <b-tab title="Notes">
                                                    <div class="row py-4">
                                                        <div class="col-md-7">
                                                            <?php echo get_textarea( ['id' => $code . '-notes', 'title' => 'Notes', 'attribute' => '', 'vue_model' => $code . '.notes'] ); ?>
                                                        </div>
                                                    </div>
                                                </b-tab>
                                            </b-tabs>
                                        </div>
                                        <?php if ( 1 == 2 ) {?>
                                        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#tab-tax-payment-details">Tax &amp; Payment Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab-address">Address</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tab-notes">Notes</a>
                                            </li>
                                        </ul>
                                        <div class="block-content tab-content">
                                            <div class="tab-pane active" id="tab-tax-payment-details" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-7"><?php echo get_select( ['id' => $code . '-currency', 'title' => 'Currency', 'class' => 'mr-2', 'attribute' => 'required', 'vue_model' => $code . '.defaultCurrency', 'vue_for' => 'currencies'], [], 'value', 'id' ); ?></div>
                                                    <?php if ( 1 == 2 ) {?>
                                                        <div class="col-md-7"><?php echo get_select( ['id' => $code . '-price-list', 'title' => 'Group', 'class' => 'mr-2', 'attribute' => '', 'vue_model' => $code . '.priceListId', 'vue_for' => 'priceLists'], [], 'value', 'id' ); ?></div>
                                                        <div class="col-md-7"><?php echo get_select( ['id' => $code . '-payment-terms', 'title' => 'Payment Terms', 'class' => 'mr-2', 'attribute' => '', 'vue_model' => $code . '.paymentTerm', 'vue_for' => 'paymentTerms'], [], 'value', 'id' ); ?></div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-address" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12"><h6>Billing Address</h6></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-attention', 'title' => 'Attention', 'attribute' => '', 'vue_model' => $code . '.billing.attention'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-address-1', 'title' => 'Address 1', 'attribute' => '', 'vue_model' => $code . '.billing.address1'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-address-2', 'title' => 'Address 2', 'attribute' => '', 'vue_model' => $code . '.billing.address2'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_select( ['id' => $code . '-billing-country', 'title' => 'Country', 'attribute' => '', 'vue_model' => $code . '.billing.country', 'vue_for' => 'masters.countries'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_select( ['id' => $code . '-billing-state', 'title' => 'State', 'attribute' => '', 'vue_model' => $code . '.billing.state', 'vue_for' => 'masters.states.billing'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-city', 'title' => 'City', 'attribute' => '', 'vue_model' => $code . '.billing.city'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-zip-code', 'title' => 'Zip Code', 'attribute' => '', 'vue_model' => $code . '.billing.zipCode'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-billing-phone', 'title' => 'Phone', 'attribute' => '', 'vue_model' => $code . '.billing.phone'] ); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12"><h6>Shipping Address<a @click.prevent="copyBillingAddress" href="javascript:void(0)" class="float-right font-12">Copy from billing address</a></h6></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-attention', 'title' => 'Attention', 'attribute' => '', 'vue_model' => $code . '.shipping.attention'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-address-1', 'title' => 'Address 1', 'attribute' => '', 'vue_model' => $code . '.shipping.address1'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-address-2', 'title' => 'Address 2', 'attribute' => '', 'vue_model' => $code . '.shipping.address2'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_select( ['id' => $code . '-shipping-country', 'title' => 'Country', 'attribute' => '', 'vue_model' => $code . '.shipping.country', 'vue_for' => 'masters.countries'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_select( ['id' => $code . '-shipping-state', 'title' => 'State', 'attribute' => '', 'vue_model' => $code . '.shipping.state', 'vue_for' => 'masters.states.shipping'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-city', 'title' => 'City', 'attribute' => '', 'vue_model' => $code . '.shipping.city'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-zip-code', 'title' => 'Zip Code', 'attribute' => '', 'vue_model' => $code . '.shipping.zipCode'] ); ?></div>
                                                            <div class="col-md-12"><?php echo get_text( ['id' => $code . '-shipping-phone', 'title' => 'Phone', 'attribute' => '', 'vue_model' => $code . '.shipping.phone'] ); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-notes" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <?php echo get_textarea( ['id' => $code . '-notes', 'title' => 'Notes', 'attribute' => '', 'vue_model' => $code . '.notes'] ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="block block-rounded block-bordered">
                                        <div class="block-content block-content-full">
                                            <a href="javascript:void(0)" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                            <a href="javascript:void(0)" @click.prevent="handleCancel" class="btn btn-white btn-noborder">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="payment-template">
    <div>
        <b-modal no-fade centered id="payment-modal" @hidden="handleModalHidden" no-close-on-backdrop size="lg" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="payment-modal-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Order Payment</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('payment-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content mb-20">
                    <div v-if="showError" class="alert alert-danger alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <p class="mb-0">{{ message }}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table">
                                <table class="table-bordered table f-s-12 text-black">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" class="text-center">Cart Details</th>
                                        </tr>
                                        <tr>
                                            <th class="w-60">Sub Total</th>
                                            <td class="w-40 text-right">{{ total.subTotal | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr v-if="order.type==='d'">
                                            <th class="w-60">Delivery Charges</th>
                                            <td class="text-right">{{ total.freightTotal | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr v-if="total.discount > 0">
                                            <th class="w-60">Discount<span v-if="total.discountType === 'p'">&nbsp;({{total.discountValue}}%)</span></th>
                                            <td class="text-right">{{ total.discount | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr v-if="Number(total.gratuityTotal) > 0">
                                            <th class="w-60">Gratuity ({{ total.gratuityRate }}%)</th>
                                            <td class="text-right">{{ total.gratuityTotal | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr>
                                            <th class="w-60">Tax ({{ total.taxRate }}%)</th>
                                            <td class="text-right">{{ total.taxTotal | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr>
                                            <th class="w-60">Net Payable</th>
                                            <td class="text-right">{{ total.grandTotal | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr v-if="Number(getTotalPaid()) > 0" class="font-18 bg-gray-lighter">
                                            <th class="w-60">Paid</th>
                                            <td class="text-right">{{ getTotalPaid() | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr v-if="Number(getOutstanding()) > 0" class="font-18 bg-gray-lighter">
                                            <th class="w-60">Outstanding</th>
                                            <td class="text-right">{{ getOutstanding() | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr class="font-18 bg-gray-lighter">
                                            <th class="w-60">Change <a v-if="canConvertToTip" class="font-11 text-right" @click.prevent="handleConvertToTip" href="javascript:void(0);">to Tip</a></th>
                                            <td class="text-right">{{ total.change | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                        <tr class="font-18 bg-gray-lighter">
                                            <th class="w-60">Tip <a v-if="isTipAllow" class="font-11 text-right" @click.prevent="reverseTip" href="javascript:void(0);">Clear</a></th>
                                            <td class="text-right">
                                                <input type="number" :disabled="!canConvertToTip" class="form-control text-right" v-model="total.tip">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table v-if="!isSplitPayment" class="table-bordered table f-s-12 text-black table-vcenter">
                                    <tbody>
                                        <tr v-if="enableExtOrderNo">
                                            <th class="w-50">Order No</th>
                                            <td class="text-center w-50">
                                                <input class="form-control" id="order-ext-order-no" type="text" v-model="order.extOrderNo">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <div class="row flex">
                                        <table class="table-bordered table table-sm text-black table-vcenter col-md-4">
                                            <tr>
                                                <th class="w-50 text-center">Print</th>
                                            </tr>
                                            <tr v-for="(p,index) in printers">
                                                <td>
                                                    <label class="css-control css-control css-control-primary css-checkbox">
                                                        <input type="checkbox" class="css-control-input" v-model="p.selected">
                                                        <span class="css-control-indicator"></span>&nbsp;{{ p.title }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="table-bordered table f-s-12 text-black col-md-8">
                                            <tbody>
                                                <tr>
                                                    <th colspan="3" class="text-center">Payments</th>
                                                </tr>
                                                <tr v-for="(single,index) in total.payments">
                                                    <th>{{ getPaymentMethod(single.paymentMethodId) }}</th>
                                                    <td class="text-right">{{ single.amount | toTwoDecimal | beautifyCurrency }}</td>
                                                    <td class="text-center"><a class="cursor-pointer text-danger" @click.prevent="handleRemovePayment(index)" href="javascript:void(0)" title="Remove this Payment"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                                <tr v-if="!total.payments.length">
                                                    <td class="text-center" colspan="2">No payment made</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-10">Payment Method</h5>
                            <div class="row mb-10">
                                <div v-for="(single,index) in getPaymentMethods" class="col-6">
                                    <label class="css-control css-control-md css-control-primary css-radio">
                                        <input type="radio" :disabled="paymentBtnDisabled" class="css-control-input" @change="updatePaymentMethod(index)" :value="single.id" v-model="payment.paymentMethodId">
                                        <span class="css-control-indicator"></span> {{ single.value }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-10">
                                    <div class="input-group">
                                        <input id="input-amount" type="text" class="form-control text-right" placeholder="Amount" v-model="payment.amount">
                                        <div class="input-group-append">
                                            <button :disabled="paymentBtnDisabled" type="button" @click="clearChars" class="btn btn-danger">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-10 text-center">
                                    <button :disabled="paymentBtnDisabled" type="button" @click="handlePayment" class="btn btn-primary mr-2">Add Custom</button>
                                    <button :disabled="paymentBtnDisabled" type="button" @click="handleFullPayment" class="btn btn-alt-primary">Add Balance</button>
                                </div>
                                <div class="col-md-12 mb-10">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <a href="javascript:void(0)" v-for="single in [1,2,3]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <a href="javascript:void(0)" v-for="single in [4,5,6]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <a href="javascript:void(0)" v-for="single in [7,8,9]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <a href="javascript:void(0)" v-for="single in ['.']" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-1 mb-2">{{ single }}</a>
                                            <a href="javascript:void(0)" v-for="single in [0]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-1 mb-2">{{ single }}</a>
                                            <a href="javascript:void(0)" v-for="single in ['←']" @click.prevent="removeChar" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="1==2" class="col-md-12 text-center">
                                    <button :disabled="paymentBtnDisabled" @click="handleFullPayment" class="btn btn-danger mr-2">Balance Payment</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <button :disabled="confirmBtnDisabled || isLoading" @click="handleConfirm" class="btn btn-danger mr-2">Pay</button>
                            <button :disabled="confirmBtnDisabled || split || isLoading" @click="handleConfirmClose" class="btn btn-primary">Pay &amp; Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="order-history-template">
    <div>
        <b-modal no-fade id="order-history-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="order-history-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Orders</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('order-history-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content mb-20">
                    <div class="row">
                        <div class="col-md-12">
                            <b-tabs content-class="mt-3">
                                <b-tab title="My Orders" active>
                                    <b-table bordered class="table-vcenter" :items="empOrders" :fields="empOrderFields">
                                        <template slot="date" slot-scope="{row,item}">
                                            <a href="#" @click.prevent="handleOrderDetails(item.id)">{{ item.date | beautifyDateTime }}</a>
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                        <template slot="id" slot-scope="{row,item}">
                                            <b-button variant="primary" size="sm" @click="handleOpenOrder(item.id)">Load</b-button>
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab title="On Hold" active>
                                    <b-table bordered class="table-vcenter" :items="onHoldOrders" :fields="confirmFields">
                                        <template slot="date" slot-scope="{row,item}">
                                            <a href="#" @click.prevent="handleOrderDetails(item.id)">{{ item.date | beautifyDateTime }}</a>
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                        <template slot="id" slot-scope="{row,item}">
                                            <b-button variant="primary" size="sm" @click="handleOpenOrder(item.id)">Load</b-button>
                                            <b-button variant="danger" class="ml-2" size="sm" @click="handelHoldOrderCancel(item.id)">Cancel</b-button>
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab title="Open" active>
                                    <b-table bordered class="table-vcenter" :items="confirmOrders" :fields="confirmFields">
                                        <template slot="date" slot-scope="{row,item}">
                                            <a href="#" @click.prevent="handleOrderDetails(item.id)">{{ item.date | beautifyDateTime }}</a>
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                        <template slot="id" slot-scope="{row,item}">
                                            <b-button variant="primary" size="sm" @click="handleOpenOrder(item.id)">Load</b-button>
                                            <b-button v-if="complexOrderStatus" variant="danger" class="ml-2" size="sm" @click="handleSetPreparing(item.id)">Preparing</b-button>
                                            <b-button v-if="!complexOrderStatus && item.closeOrder !== false" variant="alt-danger" class="ml-2" size="sm" @click="handleSetClose(item.id)">Close</b-button>
                                            <b-button v-if="item.cancelOrder !== false" variant="danger" class="ml-2" size="sm" @click="handleSetCancelled(item.id)">Cancel</b-button>
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab v-if="complexOrderStatus" title="Preparing">
                                    <b-table bordered class="table-vcenter" :items="preparingOrders" :fields="confirmFields">
                                        <template slot="date" slot-scope="row">
                                            {{ row.value | beautifyDate }}
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                        <template slot="id" slot-scope="row">
                                            <b-button variant="primary" size="sm" @click="handleOpenOrder(row.value)">Load</b-button>
                                            <b-button variant="danger" class="ml-2" size="sm" @click="handleSetReady(row.value)">Ready</b-button>
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab v-if="complexOrderStatus" title="Ready">
                                    <b-table bordered class="table-vcenter" :items="readyOrders" :fields="readyFields">
                                        <template slot="date" slot-scope="row">
                                            {{ row.value | beautifyDate }}
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                        <template slot="paymentStatus" slot-scope="row">
                                            <label v-if="row.value === 'Pending'" class="text-danger">{{ row.value }}</label>
                                            <label v-if="row.value === 'Partial'" class="text-warning">{{ row.value }}</label>
                                            <label v-if="row.value === 'Paid'" class="text-success">{{ row.value }}</label>
                                        </template>
                                        <template slot="id" slot-scope="row">
                                            <b-button variant="primary" size="sm" @click="handleOpenOrder(row.value)">Load</b-button>
                                        </template>
                                        <template slot="closeOrder" slot-scope="row">
                                            <b-button v-if="row.value !== false" variant="danger" class="ml-2" size="sm" @click="handleSetClose(row.value)">Close</b-button>
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab title="Closed">
                                    <b-table bordered class="table-vcenter" :items="closedOrders" :fields="fields">
                                        <template slot="id" slot-scope="{row,item}">
                                            <b-button variant="primary" size="sm" @click="handleOpenOrder(item.id)">Load</b-button>
                                            <b-button v-if="item.orderStatus =='Closed' && allowRefund && !isTabletMode" variant="danger" class="ml-2" size="sm" @click="handleSetRefunded(item.id)" >Issue Refund</b-button>
                                        </template>
                                        <template slot="date" slot-scope="{row,item}">
                                            <a href="#" @click.prevent="handleOrderDetails(item.id)">{{ item.date | beautifyDateTime }}</a>
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                        <template slot="orderStatus" slot-scope="row">
                                            {{ row.value ==='Closed'?"Closed":"Partial Refunded"  }}
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab title="Cancelled">
                                    <b-table bordered class="table-vcenter" :items="cancelledOrders" :fields="cancelledFields">
                                        <template slot="date" slot-scope="{row,item}">
                                            <a href="#" @click.prevent="handleOrderDetails(item.id)">{{ item.date | beautifyDateTime }}</a>
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                    </b-table>
                                </b-tab>
                                <b-tab v-if="allowRefund" title="Refunded">
                                    <b-table bordered class="table-vcenter" :items="refundedOrders" :fields="refundedFields">
                                        <template slot="date" slot-scope="{row,item}">
                                            <a href="#" @click.prevent="handleOrderDetails(item.id)">{{ item.date | beautifyDateTime }}</a>
                                        </template>
                                        <template slot="grandTotal" slot-scope="row">
                                            {{ row.value | beautifyCurrency }}
                                        </template>
                                    </b-table>
                                </b-tab>
                            </b-tabs>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="session-summary-template">
    <div>
        <b-modal no-fade centered :id="modalId" size="xl" hide-header hide-footer body-class="p-0">
            <div id="session-summary-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ getTitle }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="closeModal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <div class="row d-flex">
                        <div class="col-md-12">
                            <div v-if="!isEmployeeType" class="block">
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <span><small>Opened</small><br>{{ session.openingDate | beautifyDateTime }}</span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span><small>By employee</small><br>{{ session.openingEmployee }}</span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span><small>Closed</small><br>{{ (session.closingDate)?session.closingDate:null | beautifyDateTime }}</span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <span><small>By employee</small><br>{{ session.closingEmployee }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block">
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2"> Total Orders</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <td>Orders Placed</td>
                                                        <td class="text-center">{{ session.ordersCount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Orders Cancelled</td>
                                                        <td class="text-center">{{ session.cancelledOrdersCount }}</td>
                                                    </tr>
                                                    <tr v-if="allowRefund && Number(session.openOrdersCount)>0">
                                                        <td>Refunded Orders</td>
                                                        <td class="text-center">{{ session.refundedOrdersCount }}</td>
                                                    </tr>
                                                    <tr v-if="session.openOrdersCount>0" class="alert-danger">
                                                        <th>Open Orders</th>
                                                        <th class="text-center">{{ session.openOrdersCount }}</th>
                                                    </tr>
                                                </table>
                                                <h6 class="mb-2"> Specific Orders</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.source" v-for="single in session.source">
                                                        <td> {{ single.label }} </td>
                                                        <td class="text-center">{{ single.order }}</td>
                                                    </tr>
                                                </table>
                                                <h6 v-if="allowDiscountInSummary" class="mb-2">Specific Discounts</h6>
                                                <table  v-if="allowDiscountInSummary" class="table table-bordered table-sm">
						                            <tr v-if="session.source" v-for="single in session.source">
                                                        <td>{{ single.amountLabel }}</td>
                                                        <td class="text-right">{{ single.discount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2">Specific Payments</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.payments" v-for="single in session.payments">
                                                        <td>{{ single.label }}</td>
                                                        <td class="text-right">{{ single.amount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                                <h6 class="mb-2">Specific Amounts</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="session.source" v-for="single in session.source">
                                                        <td>{{ single.amountLabel }}</td>
                                                        <td class="text-right">{{ single.amount | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                </table>
                                                <h6 v-if="isEmployeeType" class="mb-2">Specific Registers</h6>
                                                <table v-if="isEmployeeType" class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Register</th>
                                                            <th class="text-right">Tip</th>
                                                            <th class="text-right">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-show="session.registersDetail" v-for="single in session.registersDetail">
                                                            <td>{{ single.registerTitle }}</td>
                                                            <td class="text-right">{{ single.tip | toTwoDecimal | beautifyCurrency }}</td>
                                                            <td class="text-right">{{ single.grandTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                        </tr>
                                                        <tr  v-show="!session.registersDetail" >
                                                            <td class="text-center" colspan="3">No order</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <h6 v-if="isRegisterType" class="mb-2">Specific Employee Tip</h6>
                                                <table v-if="isRegisterType" class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Emp</th>
                                                            <th class="text-right">Tip</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="single in session.empTips">
                                                            <td>{{ single.empName }}</td>
                                                            <td class="text-right">{{ single.tip | toTwoDecimal | beautifyCurrency }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h6 class="mb-2">Total Amounts</h6>
                                                <table class="table table-bordered table-sm">
                                                    <tr v-if="!isEmployeeType">
                                                        <td>Opening Cash</td>
                                                        <td class="text-right">{{ session.openingCash | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Discount</td>
                                                        <td class="text-right">{{ session.discountTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Change Given</td>
                                                        <td class="text-right">{{ session.changeTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Tip</td>
                                                        <td class="text-right">{{ session.tipTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Tax</td>
                                                        <td class="text-right">{{ session.taxTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="allowGratuity">
                                                        <td>Total Gratuity</td>
                                                        <td class="text-right">{{ session.gratuityTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Overall Cancelled Orders Amount</td>
                                                        <td class="text-right">{{ session.cancelledTransactionsTotal  | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="allowRefund">
                                                        <td>Overall Refunded Orders Amount</td>
                                                        <td class="text-right">{{ session.refundTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Overall Orders Amount</td>
                                                        <td class="text-right">{{ session.transactionsTotal | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr class="alert-danger ">
                                                        <th>Overall Payment Received</th>
                                                        <th class="text-right">{{ session.totalPaymentReceived | toTwoDecimal | beautifyCurrency }}</th>
                                                    </tr>
                                                    <tr v-if="!isEmployeeType">
                                                        <td>Expected Closing Cash</td>
                                                        <td class="text-right">{{ session.expectedClosingCash | toTwoDecimal | beautifyCurrency }}</td>
                                                    </tr>
                                                    <tr v-if="isRegisterType && Number(session.registerToEmpTotal) > 0 " class="alert-info">
                                                        <th>Give Employee Total</th>
                                                        <th class="text-right">{{ session.registerToEmpTotal | toTwoDecimal | beautifyCurrency }}</th>
                                                    </tr>
                                                    <tr v-if="!isEmployeeType" class="alert-success">
                                                        <th>Take Out Cash</th>
                                                        <th class="text-right">{{ session.takeOut | toTwoDecimal | beautifyCurrency }}</th>
                                                    </tr>
                                                    <tr v-if="showPrinter">
                                                        <label class="css-control css-control css-control-primary css-checkbox">
                                                            <input type="checkbox" class="css-control-input" v-model="printers.selected">
                                                            <span class="css-control-indicator"></span>&nbsp;{{ printers.title }}
                                                        </label>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-show="limitedShow" class="col-md-12">
                            <div class="block ">
                                <div class="block-content h-100 ">
                                    <div v-if="Number(session.openOrdersCount) > 0" class="alert alert-danger alert-dismissable " role="alert">
                                        <h3 class="alert-heading font-size-h4 font-w400 mb-0 text-center">Open Orders Found</h3>
                                        <p class="mb-0 text-center">In order to close register, please close existing orders.</p>
                                    </div>
                                    <div v-if="!isEmployeeType">
                                        <div  v-if="session.openOrdersCount===0" class="form-group row">
                                            <label class="col-12" for="closing-cash">Cash to Keep in Register</label>
                                            <div class="col-md-12">
                                                <input :readonly="session.openOrdersCount!==0" type="text" class="form-control" id="closing-cash" placeholder="Enter closing amount" v-model="session.closingCash">
                                            </div>
                                        </div>
                                        <div v-if="session.openOrdersCount===0" class="form-group row">
                                            <label class="col-12" for="closing-note">Note</label>
                                            <div class="col-md-12">
                                                <textarea :readonly="session.openOrdersCount!==0" class="form-control" rows="3" id="closing-note" placeholder="Note..." v-model="session.closingNote"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="session.openOrdersCount===0" class="form-group text-center">
                                        <button :disabled="session.openOrdersCount!==0" type="button" class="btn btn-alt-danger" @click="handleCloseRegister">Close {{ getTitle }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <user-login></user-login>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="print-server-dialog-template">
    <div>
        <b-modal no-fade centered id="print-server-dialog-modal" size="md" hide-header hide-footer body-class="p-0" v-cloak>
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Select Printers to print</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light p-20">
                    <div class="row">
                        <div class="col-4 text-center"><a class="btn btn-danger btn-lg text-center" href="#" @click.prevent="handleCashierPrinter">Cashier</a></div>
                        <div class="col-4 text-center"><a class="btn btn-danger btn-lg text-center" href="#" @click.prevent="handleKitchenPrinter">Kitchen</a></div>
                        <div class="col-4 text-center"><a class="btn btn-danger btn-lg text-center" href="#" @click.prevent="handleBothPrinters">Both</a></div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="online-order-history-template">
    <div>
        <b-modal no-fade id="online-order-history-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="order-history-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Online Orders</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="handleCloseModal(modal.id)" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content mb-20">
                    <div class="row">
                        <div class="col-md-12">
                            <b-table bordered class="table-vcenter" :items="orders" :fields="confirmFields">
                                <template slot="orderNo" slot-scope="{row,item}">
                                    <a href="#" @click.prevent="handleViewOrderDetails(item.id)">{{ item.orderNo }}</a>
                                </template>
                                <template slot="date" slot-scope="row">
                                    {{ row.value | beautifyDateTime }}
                                </template>
                                <template slot="grandTotal" slot-scope="row">
                                    {{ row.value | beautifyCurrency }}
                                </template>
                            </b-table>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="order-detail-template">
    <div>
        <div class="row my-20">
            <div v-if="order.customer" class="col-8">
                <p class="mb-0 font-10">FROM</p>
                <p class="h4 mb-5">{{ order.customer.displayName }}</p>
                <p v-show="order.customer.email" class="h6 font-14 mb-5">Email : <small>{{ order.customer.email }}</small></p>
                <p v-show="order.customer.phone" class="h6 font-14 mb-5">Mobile : <small>{{ order.customer.phone }}</small></p>
                <address>
                    
                    <span v-if="order.customer.address1">{{ order.customer.address1 }}<br/></span>
                    <span v-if="order.customer.address2">{{ order.customer.address2 }}<br/></span>
                    <span v-if="order.customer.city">{{ order.customer.city }}<br/></span>
                    <span v-if="order.customer.pincode">{{ order.customer.pincode }}</span>
                </address>
            </div>
            <div class="col-4 font-weight-600">
                <table class="table table-sm table-bordered table-vcenter">
                    <tr>
                        <th class="text-right">Order No</th>
                        <td>{{ order.orderNo }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Date</th>
                        <td>{{ order.date | beautifyDate }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Status</th>
                        <td>{{ order.orderStatus }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="table-responsive push">
            <table class="table table-bordered table-hover table-vcenter">
                <thead>
                <tr>
                    <th class="text-center" style="width: 60px;"></th>
                    <th>Item</th>
                    <th class="text-center" style="width: 90px;">Quantity</th>
                    <th class="text-right" style="width: 120px;">Rate</th>
                    <th class="text-right" style="width: 120px;">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(single,index) in order.cart.items">
                    <td class="text-center">{{ Number(index) + 1 }}</td>
                    <td>
                        <span class="font-w600 mb-4">{{ single.title }}</span>
                        <small v-if="hasAddons(single.addons)"><br/>{{ getAddons(single.addons) }}</small>
                        <small v-if="single.selectedNotes.length"><br/>{{ getNotes(single.selectedNotes) }}</small>
                        <small v-if="single.hasSpiceLevel"><br/>Spice:&nbsp;{{ single.spiceLevel }}</small>
                        <small v-if="single.orderItemNotes.length"><br/>Note:&nbsp;{{ single.orderItemNotes }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-pill badge-primary">{{ single.quantity }}</span>
                    </td>
                    <td class="text-right">{{ single.rate }}</td>
                    <td class="text-right">{{ single.amount }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-w600 text-right">Sub Total</td>
                    <td class="text-right">{{ order.cart.totals.subTotal }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="font-w600 text-right">Tax ({{ order.cart.totals.taxRate }}%)</td>
                    <td class="text-right">{{ order.cart.totals.taxTotal }}</td>
                </tr>
                <tr class="table-warning">
                    <td colspan="4" class="font-w700 text-uppercase text-right">Grand Total</td>
                    <td class="font-w700 text-right">{{ order.cart.totals.grandTotal | beautifyCurrency }}</td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th colspan="2">Payment Details</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="payment in order.cart.totals.payments">
                    <td>{{ payment.paymentMethodName }}</td>
                    <td class="text-right w-20">{{ payment.amount | beautifyCurrency }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</script>
<script type="text/x-template" id="online-order-detail-template">
    <div>
        <b-modal no-fade id="online-order-detail-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="order-history-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Online Order Details</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="handleCloseModal(modal.id)" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content mb-20">
                    <div class="row">
                        <div class="col-md-12">
                            <order-detail :order-id="orderId"></order-detail>
                        </div>
                        <div class="col-md-12 text-center">
                            <button @click="handleAcceptOnlineOrder" class="btn btn-primary">Accept Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="table-list-template">
    <div>
        <b-modal no-fade id="table-list-modal" size="xl"  no-close-on-backdrop  hide-header hide-footer body-class="p-0"  @hidden="handleClosing" v-cloak>
            <div id="table-list-block" data-keyboard="false" data-backdrop="static" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modelTitle}}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 px-15">
                    <div class="row">
                        <div class="col-md-3 bg-white shadow py-30">
                            <div class="list-group push">
                                <a @click.prevent="switchArea(single.id)" v-for="single in masters.areas" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="areaId === single.id ? 'active' : ''" href="javascript:void(0)">
                                    {{ single.title }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 bg-gray-light py-30">
                            <div class="row">
                                <div v-for="single in filtered.tables" :class="listColClass">
                                    <a class="block text-center shadow" href="javascript:void(0)" @click.prevent="handleTableSelection(single)">
                                        <div class="block-content block-content-full block-content-sm" :class="single.status === 'available' ? 'bg-primary-dark' : 'bg-danger'">
                                            <span class="font-w600 text-white">{{ single.title }}</span>
                                        </div>
                                        <div class="block-content block-content-full" :class="single.status === 'available' ? 'bg-white' : 'bg-white'">
                                            <img class="w-100" src="<?php _easset_url( "assets/img/restaurant-table.png" );?>" alt="">
                                            <div class="mt-15">
                                                <span v-if="single.status === 'available'" class="text-primary-dark text-uppercase">Available</span>
                                                <span v-if="single.status === 'engaged'" class="text-primary-dark text-uppercase">{{ single.durationSince }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="table-dialog-template">
    <div>
        <b-modal no-fade id="table-dialog-modal" size="md" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="table-list-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Select a Persons</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 px-15 py-20">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>{{ table.title }}</h4>
                            <p><small>Capacity</small><br/>{{ table.maxSeat }}</p>
                            <p><small>Status</small><br/>{{ getTableStatus(table.status) }}</p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h5>How many persons?</h5>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-12 text-center">
                                    <a href="javascript:void(0)" v-for="single in [1,2,3]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                </div>
                                <div class="col-md-12 text-center">
                                    <a href="javascript:void(0)" v-for="single in [4,5,6]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                </div>
                                <div class="col-md-12 text-center">
                                    <a href="javascript:void(0)" v-for="single in [7,8,9]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                </div>
                                <div class="col-md-12 text-center">
                                    <a href="javascript:void(0)" v-for="single in ['C']" @click.prevent="clearChars" class="btn btn-lg btn-secondary mr-1 mb-2">{{ single }}</a>
                                    <a href="javascript:void(0)" v-for="single in [0]" @click.prevent="punchChar(single)" class="btn btn-lg btn-secondary mr-1 mb-2">{{ single }}</a>
                                    <a href="javascript:void(0)" v-for="single in ['←']" @click.prevent="removeChar" class="btn btn-lg btn-secondary mr-2 mb-2">{{ single }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <div class="form-group">
                                        <label for="table-persons">Persons</label>
                                        <input id="table-persons" type="text" class="form-control" readonly v-model="table.seatUsed" />
                                    </div>
                                    <p class="text-danger" v-if="Number(table.seatUsed) > Number(table.maxSeat)">Max seats allowed: {{ table.maxSeat }}</p>
                                </div>
                                <div class="col md-12">
                                    <button href="#" class="btn btn-primary mr-10" @click.prevent="handleReserve" :disabled="Number(table.seatUsed) > Number(table.maxSeat) || Number(table.seatUsed) < 1">Confirm</button>
                                    <a href="#" class="btn btn-white" @click.prevent="hideDialog">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="discount-dialog-template">
    <div>
        <b-modal no-fade id="discount-dialog-modal" size="md" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="discount-dialog-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 px-15 py-20">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="css-control css-control-primary css-radio float-right">
                                <input v-model="discount.type" type="radio" class="css-control-input" value="f">
                                <span class="css-control-indicator"></span> Fixed
                            </label>
                            <label class="css-control css-control-primary css-radio float-right mr-3">
                                <input v-model="discount.type" type="radio" class="css-control-input" value="p">
                                <span class="css-control-indicator"></span> Percent
                            </label>
                            <input v-model="discount.value" type="text" class="form-control text-right">
                        </div>
                        <div class="col-md-6 text-center">
                            <h6 class="mb-5">Discount Amount</h6>
                            <h2>{{ discount.total }}</h2>
                        </div>
                        <div class="col-md-12 text-center">
                            <button :disabled="discount.total==0" type="button" @click="handleSubmit" class="btn btn-primary mr-2">Confirm</button>
                            <button type="button" @click="handleCancel" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="gratuity-dialog-template">
    <div>
        <b-modal no-fade id="gratuity-dialog-modal" size="md" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="gratuity-dialog-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 px-15 py-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-5">Gratuity Rate</h6>
                            <input v-model="gratuity.gratuityRate" @keyup.enter="handleSubmit" type="text" class="form-control text-right">
                        </div>
                        <div class="col-md-6 text-center">
                            <h6 class="mb-5">Gratuity Amount</h6>
                            <h2>{{ gratuity.gratuityTotal }}</h2>
                        </div>
                        <div class="col-md-12 text-center">
                            <button  type="button" @click="handleSubmit" class="btn btn-primary mr-2">Confirm</button>
                            <button type="button" @click="handleCancel" class="btn btn-secondary">Cancel</button>
                            <button type="button" @click="handleClear" class="btn btn-danger pull-right">Clear Gratuity</button>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="split-dialog-template">
    <div>
        <b-modal no-fade :id="modal.id" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div class="block block-themed block-transparent bg-gray-light mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.title }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="hideModal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content pb-20">
                    <div v-if="error.show" class="alert alert-danger alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <p class="mb-0">{{ error.message }}</p>
                    </div>
                    <div class="row justify-content-center py-30" v-if="activeComponent==='splitTypeSelection'">
                        <div class="col-4">
                            <a @click.prevent="handleSelectSplitType('equal')" class="block mb-0 text-center mr-3" href="#">
                                <div class="block-content block-content-full bg-primary">
                                    <p class="mt-30">
                                        <i class="fa fa-ellipsis-h fa-3x text-white"></i>
                                    </p>
                                    <p class="font-size-h4 font-w600 text-white mb-20">Split Equally</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a @click.prevent="handleSelectSplitType('item')" class="block mb-0 text-center" href="#">
                                <div class="block-content block-content-full bg-primary ribbon ribbon-modern ribbon-danger">
                                    <div v-if="!isSplitByItemAvailable" class="ribbon-box">
                                        Not Available for this order
                                    </div>
                                    <p class="mt-30">
                                        <i class="fa fa-copy fa-3x text-white"></i>
                                    </p>
                                    <p class="font-size-h4 font-w600 text-white mb-20">Split by Items</p>

                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row px-20" v-if="activeComponent==='splitEqualManage'">
                        <div class="col-12 text-center mb-3">
                            <h4 class="mb-2">Split Equally</h4>
                            <h6 class="mb-2">Number of Invoices</h6>
                            <div class="btn-group btn-group-lg" role="group" aria-label="btnGroup3">
                                <button :disabled="anyPaymentDone" type="button" class="btn btn-primary" @click="minusInvoice">-</button>
                                <button type="button" class="btn btn-secondary">{{ getParts }}</button>
                                <button :disabled="anyPaymentDone" type="button" class="btn btn-primary" @click="plusInvoice">+</button>
                            </div>
                        </div>
                        <div class="col-4 bg-white">
                            <div class="block">
                                <div class="block-content">
                                    <div class="list-group push">
                                        <a v-for="(single,index) in order.split" @click.prevent="updateActiveInvoice(index)" :class="activeInvoice === index ? 'active':''" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="javascript:void(0)">
                                            {{ single.title }}
                                            <span v-if="isPaymentDone(index)" class="badge badge-pill badge-danger">Paid</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 bg-white">
                            <div class="block">
                                <div class="block-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <th>Item</th>
                                                <th class="text-right">Rate</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Amount</th>
                                            </thead>
                                            <tbody>
                                                <tr v-if="activeInvoiceItems" v-for="single in activeInvoiceItems">
                                                    <td>{{ single.title }}</td>
                                                    <td class="text-right">{{ single.rate }}</td>
                                                    <td class="text-center">{{ single.quantity }}</td>
                                                    <td class="text-right">{{ single.amount }}</td>
                                                </tr>
                                                <tr v-if="!activeInvoiceItems">
                                                    <td colspan="4" class="text-center">No Items</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right">Subtotal</th>
                                                    <th class="text-right">{{ getTotal('subTotal') }}</th>
                                                </tr>
                                                <tr v-if="getTotal('discount')>0">
                                                    <th colspan="3" class="text-right">Discount</th>
                                                    <th class="text-right">{{ getTotal('discount') }}</th>
                                                </tr>
                                                <tr v-if="getTotal('gratuityTotal')>0">
                                                    <th colspan="3" class="text-right">Gratuity</th>
                                                    <th class="text-right">{{ getTotal('gratuityTotal') }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Tax</th>
                                                    <th class="text-right">{{ getTotal('taxTotal') }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Grand Total</th>
                                                    <th class="text-right">{{ getTotal('grandTotal') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button :disabled="isActivePaymentDone" @click="handlePayment" class="btn btn-danger mr-2"><i class="fa fa-dollar"></i> Make Payment</button>
                                        <button  @click="printSplitOrder" class="btn btn-secondary"><i class="fa fa-print"></i> Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-20" v-if="activeComponent==='splitItemManage'">
                        <div class="col-12 text-center mb-3">
                            <h4 class="mb-2">Split by Items</h4>
                            <h6 class="mb-2">Number of Invoices</h6>
                            <div class="btn-group btn-group-lg" role="group" aria-label="btnGroup3">
                                <button :disabled="anyPaymentDone" type="button" class="btn btn-primary" @click="minusInvoice">-</button>
                                <button type="button" class="btn btn-secondary">{{ getParts }}</button>
                                <button :disabled="anyPaymentDone" type="button" class="btn btn-primary" @click="plusInvoice">+</button>
                            </div>
                        </div>
                        <div class="col-3 bg-white px-0">
                            <div class="block">
                                <div class="block-content">
                                    <div class="list-group push">
                                        <a v-for="(single,index) in order.split" @click.prevent="updateActiveInvoice(index)" :class="activeInvoice === index ? 'active':''" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="javascript:void(0)">
                                            {{ single.title }}
                                            <span v-if="isPaymentDone(index)" class="badge badge-pill badge-danger">Paid</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 bg-white px-0">
                            <div class="block">
                                <div class="block-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                            <th>Item</th>
                                            <th class="text-right">Rate</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Amount</th>
                                            </thead>
                                            <tbody>
                                            <tr v-if="activeInvoiceItems" v-for="single in activeInvoiceItems">
                                                <td>{{ single.title }}</td>
                                                <td class="text-right">{{ single.rate }}</td>
                                                <td class="text-center">{{ single.quantity }}</td>
                                                <td class="text-right">{{ single.amount }}</td>
                                            </tr>
                                            <tr v-if="!activeInvoiceItems">
                                                <td colspan="4" class="text-center">No Items</td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Subtotal</th>
                                                <th class="text-right">{{ getTotal('subTotal') }}</th>
                                            </tr>
                                            <tr v-if="getTotal('discount')>0">
                                                <th colspan="3" class="text-right">Discount</th>
                                                <th class="text-right">{{ getTotal('discount') }}</th>
                                            </tr>
                                            <tr v-if="getTotal('gratuityTotal')>0">
                                                <th colspan="3" class="text-right">Gratuity</th>
                                                <th class="text-right">{{ getTotal('gratuityTotal') }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Tax</th>
                                                <th class="text-right">{{ getTotal('taxTotal') }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Grand Total</th>
                                                <th class="text-right">{{ getTotal('grandTotal') }}</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button :disabled="isActivePaymentDone" @click="handlePayment" class="btn btn-danger mr-2"><i class="fa fa-dollar"></i> Make Payment</button>
                                        <button :disabled="hasNonAddedItems" @click="printSplitOrder" class="btn btn-secondary"><i class="fa fa-print"></i> Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 bg-white px-0">
                            <div class="block">
                                <div class="block-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="s in nonAddedCartItems">
                                                    <td>{{ s.title }}</td>
                                                    <td class="text-center">{{ s.quantity }}</td>
                                                    <td class="text-center"><a v-if="s.quantity>0" @click.prevent="handleAddSplitItem(s)" href="#">Add</a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-primary mt-1" :disabled="remainingItem"  @click.prevent="handleAddRemainingSplitItem">+ Remaining Items</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="canCloseOrder" class="row mt-3">
                        <div class="col-12 text-center">
                            <button @click="handleCloseOrder" class="btn btn-danger mr-2">Close Order</button>
                        </div>
                    </div>
                    <div v-if="!isSplitPaymentCompleted && activeComponent!='splitTypeSelection'" class="row p-10">
                        <div class="col-12 text-right">
                            <a class="text-danger" href="#" @click.prevent="clearSplitOrder">Cancel Split Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="order-note-template">
    <div class="row">
        <div class="col-12 mb-2">
            <a class="pull-right text-danger" @click.prevent="initNotes" href="javascript:void(0)"><i class="fa fa-plus"></i>&nbsp;Order Note</a>
            <b-modal no-fade centered :id="modal.id" size="md" hide-header hide-footer body-class="p-0" v-cloak>
                <div class="block block-themed block-transparent bg-gray-light mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">{{ modal.title }}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" @click="hideDialog" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-0 px-12 py-15">
                        <div class="block-content pb-20">
                            <textarea class="form-control"  id="order-note" cols="30" rows="10" placeholder="Order Note..." v-model="obj.notes"></textarea>
                        </div>
                        <div class="text-center">
                            <button  type="button" @click="handleConfirm" class="btn btn-primary mr-2">Confirm</button>
                            <button type="button" @click="handleClearNotes" class="btn btn-danger">Clear</button>
                        </div>
                    </div>
                </div>
            </b-modal>
        </div>
    </div>
</script>
<script type="text/x-template" id="order-details-template">
    <b-modal id="order-details-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ modal.obj.orderNo }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" @click="$bvModal.hide('order-details-modal');" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="row bg-black-op-10 p-3 mx-0">
                    <div class="col text-right">
                        <button class="btn btn-danger ml-5" @click="handleDownloadPdf" title="Download PDF"><i class="fa fa-file-pdf-o"></i></button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row my-20">
                        <div class="col-4 font-weight-600 table-left">
                            <table class="table table-sm table-bordered table-vcenter">
                                <tr>
                                    <th class="text-right">Order No</th>
                                    <td>{{ modal.obj.orderNo }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Date</th>
                                    <td>{{ modal.obj.date | beautifyDate }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Status</th>
                                    <td>{{ modal.obj.orderStatus }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-4 font-weight-600 table-left">
                        </div>
                        <div class="col-4 font-weight-600 table-right">
                            <table class="table table-sm table-bordered table-vcenter">
                                <tr>
                                    <th class="text-right">Name</th>
                                    <td>{{ modal.obj.customer.displayName }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Mobile</th>
                                    <td>{{ modal.obj.customer.phone }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Email</th>
                                    <td>{{ modal.obj.customer.email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-sm table-hover table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;"></th>
                                <th>Items</th>
                                <th class="text-center" style="width: 90px;">Quantity</th>
                                <th class="text-right" style="width: 120px;">Rate</th>
                                <th class="text-right" style="width: 120px;">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(single,index) in cartItems">
                                <td class="text-center">{{ Number(index) + 1 }}</td>
                                <td>
                                    <span class="font-w600 mb-4">{{ single.title }}</span>
                                    <small v-if="hasAddons(single.addons)"><br/>{{ getAddons(single.addons) }}</small>
                                    <small v-if="single.selectedNotes.length"><br/>{{ getNotes(single.selectedNotes) }}</small>
                                    <small v-if="single.hasSpiceLevel"><br/>Spice:&nbsp;{{ single.spiceLevel }}</small>
                                    <small v-if="single.orderItemNotes.length"><br/>Note:&nbsp;{{ single.orderItemNotes }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-primary">{{ single.quantity }}</span>
                                </td>
                                <td class="text-right">{{ single.rate | beautifyCurrency }}</td>
                                <td class="text-right">{{ single.amount | beautifyCurrency }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-w600 text-right">Sub Total</td>
                                <td class="text-right">{{ modal.obj.subTotal |beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="allowGratuity && Number(modal.obj.gratuityTotal) !== 0">
                                <td colspan="4" class="font-w600 text-right">Gratuity ({{ modal.obj.gratuityRate }}%)</td>
                                <td class="text-right">{{ modal.obj.gratuityTotal | toTwoDecimal | beautifyCurrency  }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="font-w600 text-right">Tax ({{ modal.obj.taxRate }}%)</td>
                                <td class="text-right">{{ modal.obj.taxTotal | beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="Number(modal.obj.discount) !== 0">
                                <td colspan="4" class="font-w600 text-right">Discount</td>
                                <td class="text-right">{{ modal.obj.discount | beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="Number(modal.obj.tip) !== 0">
                                <td colspan="4" class="font-w600 text-right">Tip</td>
                                <td class="text-right">{{ modal.obj.tip | beautifyCurrency }}</td>
                            </tr>
                            <tr v-if="Number(modal.obj.change) > 0">
                                <td colspan="4" class="font-w600 text-right">Change <a v-if="!isClosedOrder && allowConvertChangeToTip" class="font-11 text-right" @click="handleConvertToTip" href="javascript:void(0);">Convert to Tip</a></td>
                                <td class="text-right">{{ modal.obj.change | beautifyCurrency }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="4" class="font-w700 text-uppercase text-right">Grand Total</td>
                                <td class="font-w700 text-right">{{ modal.obj.grandTotal | beautifyCurrency }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                   <div class="table-responsive">
                                       <h6 class="mb-2">Payment History {{ splitOrderTitle}}</h6>
                                        <table class="table table-sm table-bordered font-12">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="font-w600 text-left">Date</th>
                                                    <th class="font-w600 text-left">Payment Method</th>
                                                    <th class="font-w600 text-left">Payment #</th>
                                                    <th class="font-w600 text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="modal.obj.payments.length">
                                                <tr v-for="(single,index) in modal.obj.payments"  class="font-w600">
                                                    <td class="text-center">{{ Number(index) + 1 }}</td>
                                                    <td>{{ single.date | beautifyDate }}</td>
                                                    <td>{{ single.paymentMethodName }}</td>
                                                    <td>{{ single.orderNo }}</td>
                                                    <td class="text-right">{{ single.amount | beautifyCurrency }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="5" class="text-center font-w600">Payment Pending</td>
                                                </tr>
                                            </tbody>
                                            <tfoot v-if="modal.obj.payments.length">
                                                <tr class="font-w700 text-right">
                                                    <td colspan="4" >TOTAL</td>
                                                    <td>{{ getTotalPaid() | beautifyCurrency}}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-2">Order Notes</h6>
                                    <table class="table">
                                        <tbody>
                                            <tr v-if="modal.obj.notes" class="font-14-w600">
                                                <td>{{ modal.obj.notes }}</td>
                                            </tr>
                                            <tr v-if="!modal.obj.notes">
                                                <td class="text-center font-w600">No Notes</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div v-if="modal.obj.refundPayments.length" class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-2">Refund Payment History</h6>
                                    <table class="table table-sm table-bordered font-12">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="font-w600 text-left">Payment Method</th>
                                                <th class="font-w600 text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <tr v-for="(single,index) in modal.obj.refundPayments"  class="font-w600">
                                                <td class="text-center">{{ Number(index) + 1 }}</td>
                                                <td>{{ getPaymentMethodName(single.paymentMethodId) }}</td>
                                                <td class="text-right">{{ single.amount | beautifyCurrency }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="font-w700 text-right">
                                                <td colspan="2" >TOTAL</td>
                                                <td>{{ getRefundTotalPaid() | beautifyCurrency}}</td>
                                            </tr>
                                            <tr v-if="afterRefundGrandTotal > 0" class="font-w700 text-right">
                                                <td colspan="2" >After Refund Grand Total And Tip</td>
                                                <td>{{ afterRefundGrandTotal | beautifyCurrency }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div v-if="isSplitOrder" class="col-md-12">
                           <h6 class="mb-2">Splits</h6>
                            <!-- <div class="row">
                                <div v-for="(single,index) in splitOrderList" class="col-xl-3 col-lg-12 col-md-12 col-sm-6">
                                    <div @click="handleSplitClick(single.id)" class="block block-bordered block-rounded bg-primary cursor-pointer mb-2">
                                        <div class="block-header">
                                            <div class="font-w600 text-black float-left">
                                                {{ single.title }} - {{ single.grandTotal | beautifyCurrency }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row justify-content-left">
                                <div v-for="(single,index) in splitOrderList" @click="handleSplitPrint(single)" class="col-md-auto mb-20">
                                    <button class="btn btn-lg btn-success text-center w-100">{{ getSplitAmount(single) }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
</script>
<script type="text/x-template" id="info-customer-template">
    <div>
        <b-modal no-fade id="info-customer-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="info-customer-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ customer.displayName }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option"@click="handleCloseModal(modal.id)" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full min-height-100">
                <div class="row">
                    <div class="col-md-3">
                        <div class="block">
                            <div class="block-content pr-0">
                                <div class="list-group push">
                                    <a @click.prevent="modal.activeTab='basic'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="modal.activeTab==='basic' ? 'active':''" href="javascript:void(0)">Basic Info</a>
                                    <a @click.prevent="modal.activeTab='history'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="modal.activeTab==='history' ? 'active':''" href="javascript:void(0)">Order History</a>
                                    <a @click.prevent="modal.activeTab='addresses'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" :class="modal.activeTab==='addresses' ? 'active':''" href="javascript:void(0)">Addresses</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="block">
                            <div class="block-content pl-0">
                                <div class="border-black-op rounded mnh-100p">
                                    <div class="row">
                                        <div v-if="modal.activeTab==='basic'" class="col-md-12 py-20 px-40">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <h6 class="mb-5">Basic Information</h6>
                                                        <p class="mb-4 font-weight-500 font-16"><small>Name</small>: {{ customer.displayName }}</p>
                                                        <p class="mb-4 font-weight-500 font-16"><small>Email</small> : {{ customer.email }}</p>
                                                        <p class="mb-4 font-weight-500 font-16"><small>Mobile</small> : {{ customer.phone }}</p>
                                                        <p v-if="isCustomFields('memberNumber')" class="mb-4 font-weight-500 font-16"><small>Member Number</small> : {{ customer.memberNumber }}</p>
                                                        <p v-if="isCustomFields('fullVaccinated')" class="mb-4 font-weight-500 font-16"><small>Fully Vaccinated</small> : {{ customer.fullVaccinated==='0' ? 'No' : 'Yes' }}</p>
                                                        <p v-if="allowCustomerGroup" class="mb-4 font-weight-500 font-16"><small>Customer Group</small> : {{ groupTitle }}</p>
                                                        <p v-if="allowCustomerNotes" class="mb-4 font-weight-500 font-16"><small>Customer Notes</small> : {{ customer.notes }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button  @click="handleEditCustomer(customer.id)" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="modal.activeTab==='history'" class="col-md-12 py-20 px-40">
                                            <div class="col-md-12" >
                                                <h6 class="mb-2">Orders</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered table-vcenter">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-left">Date</th>
                                                            <th>Type</th>
                                                            <th>Item</th>
                                                            <th class="text-right">Total</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr  v-for="(single,index) in order">
                                                                <td ><a href="#" @click.prevent="handleOrderDetails(single.id)">{{ single.date | beautifyDate }}</a></td>
                                                                <td>{{  single.type }}</td>
                                                                <td>{{ getItems(single.items) }}</td>
                                                                <td class="text-right">{{ single.grandTotal | beautifyCurrency }}</td>
                                                                <td class="text-center">{{ single.orderStatus }}</td>
                                                                <td class='text-center'><b-button v-if="enableRepeatOrder" @click="handleRepeatOrder(single.id)" variant="primary" size="sm">Repeat Order </b-button></td>
                                                            </tr>
                                                            <tr v-if="!order.length">
                                                                <td colspan="6" class="text-center">No Order</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="modal.activeTab==='addresses'" class="col-md-12 py-20 px-40">
                                            <div class="row">
                                                <div class="col-md-12" >
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a  href="javascript:void(0)" class="btn btn-sm btn-primary mr-2 mb-2 mr-4 pull-right" @click.prevent="handleAddAddress">Add Address</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div v-if="addressList.length" v-for="(single,index) in addressList">
                                                        <div class="block-content bg-gray-light">
                                                            <div class="block block-rounded block-bordered">
                                                                <div class="block-content block-content-full">
                                                                    <span class="font-w600">{{single.title}}</span><a href="javascript:void(0)" class="pull-right" @click="deleteAddress(single.id)"><i class="fa fa-trash text-danger"></i></a><a class="pull-right text-primary mr-3" href="javascript:void(0)" @click="handleEditAddress(index)"><i class="fa fa-edit"></i></a>
                                                                    <br>
                                                                    <span >{{getAddressTitle(single)}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="!addressList.length">
                                                        <div class="block-content">
                                                            <div class="block block-rounded block-bordered">
                                                                <div class="block-content block-content-full text-center">
                                                                    <h5 class="text-center mb-0">No Address</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="edit-customer-template">
    <div>
        <b-modal no-fade centered id="edit-customer-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="edit-customer-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Edit Customer</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option"@click="handleCloseModal(modal.id)" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <form id="frm-edit-customer" data-parsley-validate="true">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block block-rounded block-bordered">
                                    <div class="block-content block-content-full">
                                        <div class="row">
                                            <div class="col-md-12"><?php echo get_text( ['id' => 'customer-customer-id', 'title' => 'Customer Id', 'attribute' => 'disabled', 'vue_model' => 'customer.customerId'] ); ?></div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-<?php echo LABEL_COLUMNS; ?> col-form-label" for="<?php echo $code . '-salutation'; ?>">Customer Name</label>
                                                    <div class="col-md-<?php echo TEXT_COLUMNS; ?> form-inline">
                                                        <?php echo get_text( ['id' => $code . '-first-name', 'title' => 'First Name', 'placeholder' => 'First Name', 'class' => 'mr-2', 'attribute' => '@blur="onName"', 'vue_model' => $code . '.firstName'], 'text', true ); ?>
                                                        <?php echo get_text( ['id' => $code . '-last-name', 'title' => 'Last Name', 'placeholder' => 'Last Name', 'attribute' => '@blur="onName"', 'vue_model' => $code . '.lastName'], 'text', true ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12"><?php echo get_text( ['id' => 'customer-display-name', 'title' => 'Display Name', 'attribute' => 'required', 'vue_model' => 'customer.displayName'] ); ?></div>
                                            <div class="col-md-12"><?php $required = ( _get_var( 'customer_user_field', 'mobile' ) == 'mobile' ) ? 'required' : '';
                                                echo get_text( ['id' => 'customer-phone', 'title' => 'Mobile', 'attribute' => $required . ' ref="phone"', 'vue_model' => 'customer.phone'] );?></div>
                                            <div class="col-md-12"><?php $required = ( _get_var( 'customer_user_field', 'mobile' ) == 'email' ) ? 'required' : '';
                                                echo get_text( ['id' => 'customer-email', 'title' => 'Email', 'attribute' => $required . ' ref="email"', 'vue_model' => 'customer.email'], 'email' );?></div>
                                            <div v-if="isCustomFields('fullVaccinated')" class="col-md-12 mb-3">
                                                <div class="row">
                                                    <label class="col-md-3 col-form-label" for="customer-full-vaccinated">Fully Vaccinated</label>
                                                    <div class="col-md-9"><?php echo get_select( ['id' => $code . '-full-vaccinated', 'title' => 'Full Vaccinated', 'attribute' => '', 'vue_model' => 'customer.fullVaccinated', 'vue_for' => 'vaccination'], [], 'value', 'id', true ); ?></div>
                                                </div>
                                            </div>
                                            <div v-if="isCustomFields('memberNumber')" class="col-md-12"><?php echo get_text( ['id' => $code . '-member-number', 'title' => 'Member Number', 'attribute' => '', 'vue_model' => $code . '.memberNumber'] ); ?></div>
                                            <div v-if="allowCustomerGroup" class=" col-md-12">
                                                <div class="row">
                                                    <label class="col-md-3 col-form-label" for="customer-group">Customer Group</label>
                                                    <div class="col-md-9"><?php echo get_select( ['id' => $code . '-groupId', 'title' => 'Customer Group', 'attribute' => '', 'vue_model' => $code . '.groupId', 'vue_for' => 'masters.groups'], [], 'value', 'id', true ); ?></div>
                                                </div>
                                            </div>
                                            <div v-if="allowCustomerNotes"  class="col-md-12"><?php echo get_textarea( ['id' => $code . '-customer-notes', 'title' => 'Notes', 'attribute' => '', 'vue_model' => $code . '.notes'] ); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <!--  <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Addresses</h6>
                                    </div>
                                    <div class="col-6">
                                       <a href="javascript:void(0)" class="btn btn-sm btn-primary mb-2 pull-right" @click.prevent="handleAddAddress">Add Address</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div v-for="(single,index) in addressList">
                                            <div class="block block-rounded block-bordered">
                                                <div class="block-content block-content-full">
                                                    <span class="font-w600">{{single.title}}</span><a href="javascript:void(0)" class="pull-right" @click="deleteAddress(index)"><i class="fa fa-trash text-danger"></i></a><a class="pull-right text-primary mr-3" href="javascript:void(0)" @click="handleEditAddress(index)"><i class="fa fa-edit"></i></a>
                                                    <br>
                                                    <span >{{getAddressTitle(single)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> -->
                            <div class="col-md-12">
                                <div class="block block-rounded block-bordered">
                                    <div class="block-content block-content-full">
                                        <a href="javascript:void(0)" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                        <a href="javascript:void(0)" @click.prevent="handleCancel" class="btn btn-danger btn-noborder">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="edit-address-template">
    <div>
        <b-modal no-fade centered id="edit-address-modal" size="xl" hide-header hide-footer body-class="p-0" v-cloak>
            <div id="edit-address-block" class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Edit Address</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option"@click="handleCloseModal(modal.id)" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content bg-gray-light">
                    <form id="frm-edit-address" data-parsley-validate="true">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="block block-rounded block-bordered">
                                    <div class="block-content block-content-full">
                                        <div class="row">
                                            <div class="col-md-12"><?php echo get_text( ['id' => 'address-title', 'title' => 'Title', 'attribute' => 'required', 'vue_model' => 'address.title'] ); ?></div>
                                            <div class="col-md-12"><?php echo get_text( ['id' => 'address-address-1', 'title' => 'Address 1', 'attribute' => 'required', 'vue_model' => 'address.address1'] ); ?></div>
                                            <div class="col-md-12"><?php echo get_text( ['id' => 'address-address-2', 'title' => 'Address 2', 'attribute' => '', 'vue_model' => 'address.address2'] ); ?></div>
                                            <div class="col-md-12"><?php echo get_select( ['id' => 'address-country', 'title' => 'Country', 'attribute' => 'disabled', 'vue_model' => 'address.countryId', 'vue_for' => 'masters.countries'] ); ?></div>
                                            <div class="col-md-12"><?php echo get_select( ['id' => 'address-state', 'title' => 'State', 'attribute' => '', 'vue_model' => 'address.stateId', 'vue_for' => 'masters.states'] ); ?></div>
                                            <div class="col-md-12"><?php echo get_select( ['id' => 'address-city', 'title' => 'City', 'attribute' => '', 'vue_model' => 'address.cityId', 'vue_for' => 'masters.cities'] ); ?></div>
                                            <div class="col-md-12"><?php echo get_text( ['id' => 'address-zip-code', 'title' => 'Zip Code', 'attribute' => 'required', 'vue_model' => 'address.zipCode'] ); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block block-rounded block-bordered">
                                    <div class="block-content block-content-full">
                                        <a href="javascript:void(0)" @click.prevent="handleSubmit" class="btn btn-primary btn-noborder">Save</a>
                                        <a href="javascript:void(0)" @click.prevent="hideModel" class="btn btn-danger btn-noborder">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="order-source-switch-template">
    <b-modal no-fade id="order-source-switch-modal" size="md" hide-header hide-footer body-class="p-0" v-cloak>
        <div id="order-source-switch-block" class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">Switch Order Source</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" @click="handleCloseModal(modal.id)" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="block-content mb-20">
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter">
                        <tbody>
                            <tr>
                                <th>Website</th>
                                <th class="text-center" :class="sources.web.status === true ? 'text-success' : 'text-danger'">{{ getStatusText(sources.web.status) }}</th>
                                <th class="text-right">
                                    <label class="css-control css-control-success css-switch">
                                        <input type="checkbox" class="css-control-input" @change="onStatusChange('web')" v-model="sources.web.status">
                                        <span class="css-control-indicator"></span>
                                    </label>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </b-modal>
</script>
<script type="text/x-template" id="issue-refund-template">
    <b-modal id="issue-refund-modal" size="xl"   hide-header hide-footer body-class="p-0" v-cloak>
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">{{ order.orderNo }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" @click="handleCancel" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="block block-content">
                <div class="row">
                    <div v-if="showError" class="alert alert-danger alert-dismissable col-md-12" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <p class="mb-0">{{ message }}</p>
                    </div>
                    <div class="col-md-12 mb-10">
                        <button :disabled="fullRefundBtnDisabled" class="btn btn-danger pull-right" @click="handleSetRefunded()">Full Refund</button>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <h6 class="mb-2">Payment History</h6>
                                    <table class="table table-bordered font-12">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="font-w600 text-left">Date</th>
                                                <th class="font-w600 text-left">Payment Method</th>
                                                <th class="font-w600 text-left">Payment #</th>
                                                <th class="font-w600 text-right">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="order.payments.length">
                                            <tr v-for="(single,index) in order.payments"  class="font-w600">
                                                <td class="text-center">{{ Number(index) + 1 }}</td>
                                                <td>{{ single.date | beautifyDate }}</td>
                                                <td>{{ single.paymentMethodName }}</td>
                                                <td>{{ single.orderNo }}</td>
                                                <td class="text-right">{{ single.amount  | toTwoDecimal| beautifyCurrency }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5" class="text-center font-w600">Payment Pending</td>
                                            </tr>
                                        </tbody>
                                        <tfoot v-if="order.payments.length">
                                            <tr class="font-w700 text-right">
                                                <td colspan="4" >TOTAL</td>
                                                <td>{{ getTotalPaid() | toTwoDecimal | beautifyCurrency}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-2">Payment Details</h6>
                                <table class="table table-sm table-bordered font-12">
                                    <tr>
                                        <th class="text-right">Sub Total</th>
                                        <td class="text-right">{{ order.subTotal | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Tax(5%)</th>
                                        <td class="text-right">{{ order.taxTotal | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Discount</th>
                                        <td class="text-right">{{ order.discount | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Grant Total</th>
                                        <td class="text-right">{{ order.grandTotal | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Change</th>
                                        <td class="text-right">{{ order.change | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Tip</th>
                                        <td class="text-right">{{ order.tip | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Grand Total + Tip</th>
                                        <td class="text-right">{{ grandTotalAndTip | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5>Partial Refund</h5>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="mb-10">Payment Method</h6>
                                <div class="row mb-10">
                                    <div class="col-md-12 mb-10">
                                    <select class="form-control w-100 d-inline mr-2 mb-2" v-model="payment.paymentMethodId">
                                        <option value="">None</option>
                                        <option v-for="(single,index) in getPaymentMethods" :value="single.id">{{ single.value }}</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-money"></i></span></div>
                                            <input id="input-amount" type="number" class="form-control text-right" placeholder="Amount" v-model="payment.amount">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-10 text-center">
                                        <button :disabled="paymentBtnDisabled" type="button"  @click="handlePayment" class="btn btn-primary mr-2">Add Custom</button>
                                        <button :disabled="paymentBtnDisabled" type="button"  @click="handleBalance" class="btn btn-alt-primary">Add Balance</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <table class="table-bordered table f-s-12 text-black col-md-12">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="text-center">Refund Payments</td>
                                        </tr>
                                        <tr v-for="(single,index) in refundPayments">
                                            <td>{{ getPaymentMethod(single.paymentMethodId) }}</td>
                                            <td class="text-right">{{ single.amount | toTwoDecimal | beautifyCurrency }}</td>
                                            <td class="text-center"><a class="cursor-pointer text-danger" @click.prevent="handleRemovePayment(index)" href="javascript:void(0)" title="Remove this Payment"><i class="fas fa-trash"></i></a></td>
                                        </tr>
                                        <tr v-if="!refundPayments.length">
                                            <td class="text-center" colspan="2">No Refund Made</td>
                                        </tr>
                                    </tbody>
                                    <tfoot v-if="refundPayments.length">
                                        <tr class="font-w600 text-right">
                                            <td colspan="1" >TOTAL</td>
                                            <td>{{ refundTotal | toTwoDecimal | beautifyCurrency}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table-bordered table f-s-12 text-black col-md-12">
                                    <tbody>
                                        <tr>
                                            <td class="text-left font-12">After Refund Grand Total And Tip</td>
                                            <td>{{ afterRefundGrandTotal | toTwoDecimal | beautifyCurrency }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button :disabled="confirmBtnDisabled" @click="handleConfirm" class="btn btn-danger mr-2">Refund</button>
                        <button  @click="handleCancel" class="btn btn-primary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </b-modal>
</script>
<script type="text/x-template" id="employee-login-template">
    <b-modal id="employee-login-modal" size="md"   hide-header hide-footer body-class="p-0" v-cloak>
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">{{ employee.name }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" @click="handleCancel" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="block block-content">
                <div class="row">
                    <div class="col-md-12 mb-10">
                        <div class="form-group row">
                            <label class="col-12" for="code">Code</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control" id="code" placeholder="Enter code" v-model="employee.code"/>
                                <p v-if="showError" class="text-danger">{{ errorMessage }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button  @click="handleSubmit" class="btn btn-danger mr-2">Login</button>
                        <button  @click="handleCancel" class="btn btn-primary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </b-modal>
</script>
<script type="text/x-template" id="user-login-template">
     <b-modal id="user-login-modal" size="md"   hide-header hide-footer body-class="p-0" v-cloak>
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">Manager Login</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" @click="handleCancel" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="block block-content">
                <form id="frm-login" @submit.prevent="handleSubmit" class="js-validation-signin px-30" data-parsley-validate="true">
                    <div style="display: none;" class="form-group row ">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input id="login-email" class="form-control" type="email" v-model="login.email" required data-parsley-required-message="Email Address is required.">
                                <label for="login-email">Email Address</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input id="login-password" class="form-control" @keyup.enter="handleSubmit" type="password" v-model="login.password" required data-parsley-required-message="Password is required">
                                <label for="login-password">Password</label>
                            </div>
                        </div>
                    </div>
                    <p v-if="showMessage" class="text-danger">{{ errorMessage }}</p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary" :disabled="sendingRequest">
                            <i class="si si-login mr-10"></i>Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </b-modal>
</script>
<script type="text/x-template" id="convert-tip-template">
     <b-modal id="convert-tip-modal" size="md"   hide-header hide-footer body-class="p-0" v-cloak>
        <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">{{order.orderNo}}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" @click="handleCancel" aria-label="Close">
                        <i class="si si-close"></i>
                    </button>
                </div>
            </div>
            <div class="block block-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table">
                            <table class="table-bordered table f-s-12 text-black">
                                <tbody>
                                    <tr>
                                        <th colspan="2" class="text-center">Cart Details</th>
                                    </tr>
                                    <tr class="font-18 bg-gray-lighter">
                                        <th class="w-60">Change <a v-if="canConvertToTip"class="font-11 text-right" @click.prevent="handleConvertToTip" href="javascript:void(0);">to Tip</a></th>
                                        <td class="text-right">{{ total.change | toTwoDecimal | beautifyCurrency }}</td>
                                    </tr>
                                    <tr class="font-18 bg-gray-lighter">
                                        <th class="w-60">Tip <a v-if="isTipAllow" class="font-11 text-right" @click.prevent="reverseTip" href="javascript:void(0);">Clear</a></th>
                                        <td class="text-right">
                                            <input type="number" :disabled="!canConvertToTip" class="form-control text-right" v-model="total.tip">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="text-center">
                            <button  type="button" @click="handleConfirm" class="btn btn-primary mr-2">Confirm</button>
                            <button type="button" @click="handleCancel" class="btn btn-danger">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </b-modal>
</script>
<script type="text/x-template" id="clover-payment-template">
    <b-modal no-fade centered id="clover-payment-modal" size="lg" :no-close-on-backdrop="true"  hide-header hide-footer body-class="p-0" v-cloak>
        <div id="clover-payment-block" class="block">
            <div class="loader-text text-bold">
            {{ cloverPaymentMessage }}
            </div>
        </div>
    </b-modal>
</script>
<script type="text/x-template" id="pos-template">
    <div id="brahma-pos" v-cloak>
        <div v-if="canShowSession" class="row" v-cloak>
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="block block-rounded block-themed w-400p">
                        <div class="block-header bg-flat">
                            <h3 class="block-title">Open Session</h3>
                        </div>
                        <div class="block-content">
                           <!--  <div class="form-group row">
                                <label class="col-12" for="opening-cash">Opening Amount</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="opening-cash" placeholder="Enter opening amount" v-model="newSession.openingCash">
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-12" for="opening-note">Note</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" rows="5" id="opening-note" placeholder="Note..." v-model="newSession.openingNote"></textarea>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-alt-primary" @click="handleOpenSession">Open Session</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <user-login></user-login>
        </div>
        <div v-if="session==null && isTabletMode">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="block block-rounded block-themed w-400p">
                        <div class="block-header bg-danger">
                            <h3 class="block-title">Open Register</h3>
                        </div>
                        <div class="block-content">
                        <p>Please inform Admin/Manager to open a session first.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="sessionOpen">
            <div v-if="registerCheckLogin">
                <div v-if="showRegisterSession">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="block block-rounded block-themed w-400p">
                                <div class="block-header bg-flat">
                                    <h3 class="block-title">Open {{ registerTitle }}</h3>
                                    <!-- <button type="button" class="btn btn-alt-primary" :disabled="!canCloseSession" @click="handleSessionSummary">Open Register {{ registerTitle }}</button> -->
                                </div>
                                <div class="block-content">
                                    <div v-if="!isTabletMode" class="form-group row">
                                        <label class="col-12" for="opening-cash">Opening Amount</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" id="opening-cash" placeholder="Enter opening amount" v-model="newRegisterSession.openingCash">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12" for="opening-note">Note</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control" rows="5" id="opening-note" placeholder="Note..." v-model="newRegisterSession.openingNote"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="button" class="btn btn-alt-primary" @click="handleOpenRegister">Open {{ registerTitle }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="isPrimaryRegister" class="d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-alt-danger" :disabled="!canCloseSession" @click="handleSessionSummary">Close Session</button>
                        </div>
                        <session-summary object-id="session" :id="session.id" :employeeId="employeeId" :registerId="registerId" :registerSessionId="registerSession ? registerSession.id : ''"></session-summary>
                        <print-server-dialog></print-server-dialog>
                    </div>
                </div>
                <div v-if="canShowEmpLogin">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-center mt-100" v-cloak>
                            <div class="block block-rounded block-themed w-50">
                                <div class="block-header bg-secondary">
                                    <h3 class="block-title">Employee Login</h3>
                                    <a class="btn btn-danger" href="javascript:void(0)" @click.prevent="handleRegisterSummary">Close Register</a>
                                </div>
                                <div class="block-content">
                                    <div class="row">
                                        <div  v-for="e in employees" class="col-md-4 col-12">
                                            <a class="block block-link-pop text-center bg-info-light" @click.prevent="handleEmployee(e)" href="javascript:void(0)">
                                                <div class="block-content block-content-full">
                                                    <img class="img-avatar" src="<?php _easset_url( "assets/img/employee.jpg" );?>" alt="">
                                                </div>
                                                <div class="block-content block-content-full bg-info">
                                                    <div class="font-w600 mb-5 text-white">{{ e.name }}</div>
                                                    <div class="font-size-sm" :class="e.shiftOpen ? 'text-white':'text-warning'">{{ getShiftTitle(e.shiftOpen) }}</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <employee-login></employee-login>
                    <session-summary object-id="register" :id="session.id" :employeeId="employeeId" :registerId="registerId" :registerSessionId="registerSession ? registerSession.id : ''"></session-summary>
                    <print-server-dialog></print-server-dialog>
                </div>
            </div>
            <div v-if="!registerCheckLogin">
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="block block-rounded block-themed w-400p">
                            <div class="block-header bg-danger">
                                <h3 class="block-title">Open Register</h3>
                            </div>
                            <div class="block-content">
                            <p>Please inform Admin/Manager to open a register first.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="canShowPos" class="row font-13">
            <div v-if="topWarning.show" class="col-12"><p class="p-10 bg-danger text-white text-center">{{ topWarning.message }}</p></div>
            <div class="col-xl-8 col-lg-6">
                <item-list :cart="order.cart" :is-editable="isEditable"></item-list>
                <item-detail :is-editable="isEditable"></item-detail>
                <group-item-detail :is-editable="isEditable"></group-item-detail>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="block mb-10 line-height-0">
                    <div class="block-content block-content-full d-inline-block py-10">
                        <div class="btn-group" role="group">
                            <button v-if="hasOrderMethod('p')" :disabled="!isEditable" @click="handleOrderType('p')" type="button" class="btn btn-alt-primary" :class="order.type == 'p' ? 'active border-primary' : ''">Pickup</button>
                            <button v-if="hasOrderMethod('d')" :disabled="!isEditable" @click="handleOrderType('d')" type="button" class="btn btn-alt-primary" :class="order.type == 'd' ? 'active border-primary' : ''">Delivery</button>
                            <button v-if="hasOrderMethod('dine')" :disabled="!isEditable" @click="handleOrderType('dine')" type="button" class="btn btn-alt-primary" :class="order.type == 'dine' ? 'active border-primary' : ''">Dine-in</button>
                        </div>
                        <span v-if="order.type=='dine'" class="ml-2">Table: <a href="javascript:void(0)" @click="handleChangeTable" title="Change Table" class="text-danger">{{ getTableName() }}</a></span>
                        <div class="btn-group pull-right" role="group">
                            <button type="button" class="btn btn-primary" @click="onOrderHistory">Orders</button>
                            <div class="btn-group float-right d-block" role="group">
                                <button type="button" class="btn btn-danger dropdown-toggle" id="additional-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-cog mr-5"></i>Options</button>
                                <div class="dropdown-menu" aria-labelledby="additional-actions" x-placement="bottom-start">
                                    <div class="dropdown-item"><i class="fa fa-fw fa-user mr-5"></i>{{ employeeName }}</div>
                                    <a v-if="enableSourceSwitch && !isTabletMode" class="dropdown-item" href="javascript:void(0)" @click.prevent="handleOrderSwitch"><i class="fa fa-fw fa-bell mr-5"></i>Order Sources</a>
                                    <a class="dropdown-item" href="javascript:void(0)" @click.prevent="handlePosRegisterSummary"><i class="fa fa-fw fa-money mr-5"></i>Register Summary</a>
                                    <a v-if="allowOpenCashDrawer() && !isTabletMode" class="dropdown-item" href="javascript:void(0)" @click.prevent="handleOpenDrawer"><i class="fa fa-fw fa-bell mr-5"></i>Cash Drawer</a>
                                    <a class="dropdown-item" href="javascript:void(0)" @click.prevent="handleEmployeeLogout"><i class="si si-logout mr-5"></i>Lock Terminal</a>
                                    <a v-if="!isTabletMode" class="dropdown-item" href="javascript:void(0)" @click.prevent="handleEmployeeSummary"><i class="si si-logout mr-5"></i>Close Shift</a>
                                </div>
                            </div>
                        </div>
                        <a v-if="unacceptedOrders.length" @click.prevent="onUnacceptedOrders" href="#" class="btn btn-link float-right text-danger">{{ unacceptedOrders.length }} pending online {{ unacceptedOrders.length > 1 ? 'orders' : 'order' }}</a>
                    </div>
                </div>
                <table-list></table-list>
                <table-dialog></table-dialog>
                <discount-dialog :cart="order.cart" :is-editable="isEditable"></discount-dialog>
                <gratuity-dialog :cart="order.cart" :is-editable="isEditable"></gratuity-dialog>
                <order-history :employeeId="employeeId" :registerId="registerId" :session="session.id" :isTabletMode="isTabletMode"></order-history>
                <order-details :session="session.id"></order-details>
                <convert-tip></convert-tip>
                <online-order-history :orders="unacceptedOrders"></online-order-history>
                <online-order-detail :session="session.id" :register="register"></online-order-detail>
                <session-summary object-id="employee" :id="session.id" :employeeId="employeeId" :registerId="registerId" :registerSessionId="registerSession ? registerSession.id : ''"></session-summary>
                <session-summary object-id="register" :id="session.id" :employeeId="employeeId" :registerId="registerId" :registerSessionId="registerSession ? registerSession.id : ''"></session-summary>
                <print-server-dialog></print-server-dialog>
                <div class="block">
                    <div class="block-content block-content-full py-10">
                        <order-source-switch v-if="enableSourceSwitch"></order-source-switch>
                        <issue-refund v-if="allowRefund" :registerId="registerId"></issue-refund>
                        <customer :customer="order.customer" :is-editable="isEditable" :cart="order.cart"></customer>
                        <add-customer :is-editable="isEditable" mode="add"></add-customer>
                        <info-customer :customer="order.customer" @updated="onCustomerUpdated" :is-editable="isEditable"></info-customer>
                        <edit-customer :customer="order.customer" @updated="onCustomerUpdated" :is-editable="isEditable"></edit-customer>
                        <edit-address  :is-editable="isEditable"  @updated="onCustomerUpdated"></edit-address>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <order-note :order="order"></order-note>
                                <cart :isTabletMode="isTabletMode" :cart="order.cart" :order-type="order.type" :is-editable="isEditable" :order="order" :is-payment-allowed="paymentAllowed" :allow-gratuity="allowGratuity"></cart>
                                <promotion-dialog :order="order"></promotion-dialog>
                                <split-order :order="order" :allow-gratuity="allowGratuity"></split-order>
                                <cart-edit-item :items="order.cart.items" :is-editable="isEditable"></cart-edit-item>
                            </div>
                            <payment :order="order" :is-editable="isEditable"></payment>
                            <clover-payment></clover-payment>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
