<div class="row align-items-center align-content-center w-100">
    <div v-if="obj.bulk" class="col-lg-8 col-md-auto">
        <label class="sr-only" for="title">Title (One item per line)</label>
        <textarea id="title" class="form-control" cols="30" rows="5" v-model="obj.title" required placeholder="Title (One item per line)"></textarea>
    </div>
    <div v-if="!obj.bulk" class="col-lg-8 col-md-auto">
        <label class="sr-only" for="title">Title</label>
        <input class="form-control mb-2" id="title" placeholder="Title" type="text" v-model="obj.title" required/>
    </div>
    <div class="col-lg-2 col-md-auto">
        <label class="sr-only" for="status">Status</label>
        <select id="status" class="form-control mb-2" v-model="obj.status" required>
            <option v-for="single in masters.statuses" :value="single.id">{{ single.value }}</option>
        </select>
    </div>
    <div class="col-lg-1 col-md-auto">
        <button class="btn btn-primary mb-2" type="button" @click.prevent="handleSubmit">{{ updateBtnText }}</button>
    </div>
    <div class="col-lg-1 col-md-auto">
        <button class="btn btn-danger mb-2" type="button" @click.prevent="handleCancel">Cancel</button>
    </div>
</div>
