<b-modal v-cloak id="item-details-modal" centered size="xl" hide-header hide-footer body-class="p-0" v-cloak>
    <div class="block block-themed block-transparent mb-0">
        <div class="block-header bg-primary-dark">
            <h3 class="block-title">{{ modal.obj.name }}</h3>
            <div class="block-options">
                <a :href="modal.obj.edit" class="btn-block-option"> <i class="fas fa-edit"></i></a>
                <button type="button" class="btn-block-option" @click="$bvModal.hide('item-details-modal');" aria-label="Close">
                    <i class="si si-close"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="row my-20">
                <div class="col-4 font-weight-600">
                    <table class="table table-sm table-bordered table-vcenter">
                        <tr>
                            <th class="text-left">Taxable Goods?</th>
                            <td>{{ getTaxable }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Print Location</th>
                            <td>{{ getPrintLocation }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Has Spice Level</th>
                            <td>{{ getHasSpiceLevel }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-4 font-weight-600">
                    <table class="table table-sm table-bordered table-vcenter">
                        <tr>
                            <th class="text-left">Show in POS?</th>
                            <td>{{ getPosStatus }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Show in WEB?</th>
                            <td>{{ getWebStatus }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Show in APP?</th>
                            <td>{{ getAppStatus }}</td>
                        </tr>

                    </table>
                </div>
                <div class="col-3 font-weight-600">
                    <img class="img-fluid img-thumbnail mb-20 w-50" :src="modal.obj.imageUrl | imagePath" alt="">
                </div>
            </div>
            <div class="row">
                <div class="col-9 font-weight-600">
                    <table class="table table table-bordered table-vcenter">
                        <thead class="font-12">
                            <tr>
                                <th class="text-center">Category</th>
                                <th class="text-center">Types</th>
                                <th class="text-center">Sale Price</th>
                                <th class="text-center">Icon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="single in modal.obj.prices">
                                <td class="text-center">{{ getCategoryTitle }}</td>
                                <td  class="text-center"> <img class="img-fluid h-15p" :src="getVegNVegImg(modal.obj.isVeg)" /></td>
                                <td class="text-center"><span class="badge badge-pill badge-primary">{{ single.salePrice }}</span></td>
                                <td class="text-center" v-if="modal.obj.iconTitle">
                                        <i class="fa-2x" :class="modal.obj.iconTitle"></i>
                                    </td>
                                    <td class="text-center" v-else>
                                        No Icon
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table">
                <div class="row my-20">
                    <div class="col-7 font-weight-600">
                        <table class="table table-bordered table-hover table-vcenter font-13">
                            <thead class="font-11">
                                <tr class="bg-success-light">
                                    <th></th>
                                    <th>Display Addons</th>
                                    <th class="text-center">Price</th>
                                </tr>
                            </thead>
                            <tbody v-if="modal.obj.addons.length">
                                <tr v-for="(single, index) in modal.obj.addons">
                                    <td class="text-center">{{ Number(index) + 1 }}</td>
                                    <td>{{ single.title }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-pill badge-primary">{{ single.salePrice }}</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3" class="text-center">No Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-4 font-weight-600">
                        <table class="table table-bordered table-hover table-vcenter font-13">
                            <thead class="font-11">
                                <tr class="bg-success-light">
                                    <th></th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody v-if="modal.obj.notes.length">
                                 <tr v-for="(single, index) in modal.obj.notes">
                                    <td class="text-center">{{ Number(index) + 1 }}</td>
                                    <td>{{ single.title }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="2" class="text-center">No Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</b-modal>
