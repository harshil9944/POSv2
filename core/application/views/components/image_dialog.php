<script type="text/x-template" id="image-selection-dialog-template">
    <div>
        <b-modal id="image-dialog-modal" size="xl" hide-footer title="Image Selection">
            <div class="row">
                <div class="col-md-2">
                    <add-image-category></add-image-category>
                    <dialog-category-list></dialog-category-list>
                </div>
                <div class="col-md-10">
                    <dialog-image-list :multiple="multiple" :values="values"></dialog-image-list>
                </div>
            </div>
        </b-modal>
        <b-row>
            <b-col md="10" class="align-items-center mb-10">
                <label for="hotel-images">Gallery</label>
            </b-col>
            <b-col md="2" class="text-right mb-10">
                <b-button @click="showImageModal" size="sm" variant="primary">Select Images</b-button>
            </b-col>
        </b-row>
    </div>
</script>
<script type="text/x-template" id="select-image-list-template">
    <div class="row">
        <b-col md="12" class="mb-10">
            <form id="frm-img-upload" ref="uploadform" method="post" enctype="multipart/form-data">
                <b-button size="sm" variant="primary" @click="openDialog">Upload</b-button>
                <input type="file" ref="fileInput" class="hide hidden" multiple @change.prevent="handleUpload" accept="image/*">
                <small class="ml-10 text-danger">Image will be uploaded in selected Category</small>
            </form>
        </b-col>
        <div class="col-md-12" style="max-height:600px;overflow-y:auto;">
            <ul class="list-inline">
                <li v-for="(image,index) in images" class="list-inline-item">
                    <img class="rounded-5 cursor-pointer border border-4" :class="image.checked?'border-danger':''" @click="handleImgClick(index)" :src="image.thumbnail" :alt="image.title" :title="image.title">
                </li>
            </ul>
        </div>
        <div v-if="!images.length" class="col-md-12">
            <h5 class="text-center">Images could not be found.</h5>
        </div>
        <div class="col-md-12 mt-10 mb-10 text-center">
            <hr>
            <b-button @click="handleSelect" variant="primary">Select</b-button>
            <b-button class="ml-10" @click="handleCancel" variant="danger">Cancel</b-button>
        </div>
    </div>
</script>
<script type="text/x-template" id="select-category-template">
    <div>
        <h5 class="mb-20">Categories <b-button v-b-modal.add-category-modal size="xs" variant="primary"><i class="fa fa-plus"></i> </b-button></h5>
        <div class="list-group">
            <a v-for="(cat,index) in categories" href="#" @click.prevent="selectCategory(index)" class="list-group-item narrow list-group-item-action" :class="(category==cat.id)?'active':''">{{ cat.title }}</a>
        </div>
    </div>
</script>
<script type="text/x-template" id="add-image-category-template">
    <div>
        <b-modal id="add-category-modal" size="xs" @ok="handleAddImageCategory" title="Add Image Category">
            <form id="frm-add-image-category" data-parsley-validate="true" @submit.prevent.enter="handleAddImageCategory">
                <b-form-group
                        label="Title"
                        label-for="category-title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="category-title"
                            v-model="category.title"
                            required
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</script>
<script type="text/x-template" id="view-image-list-template">
    <div class="row">
        <b-col md="12">
            <div class="form-control pt-10 mnh-200p">
                <ul v-if="images.length" class="list-inline">
                    <li v-for="(image,index) in images" class="list-inline-item position-relative">
                        <i @click="removeImage(index)" class="fa fa-close position-absolute font-20 text-danger cursor-pointer" style="top:-10px;right:-7px;"></i>
                        <img class="rounded-5 cursor-pointer border border-4" :src="image.thumbnail" :alt="image.title" :title="image.title">
                    </li>
                </ul>
                <h5 v-if="!images.length" class="text-center mt-70">Select Gallery Images.</h5>
            </div>
        </b-col>
    </div>
</script>
