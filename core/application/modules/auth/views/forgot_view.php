<script type="text/x-template" id="forgot-template">
    <div class="form-side">
        <a href="javascript:void(0);">
            <span class="logo-single"></span>
        </a>
        <h6 class="mb-4"><?php _eline('text_reset_password'); ?></h6>
        <form id="frm-forgot" @submit.prevent="handleSubmit" data-parsley-validate="true">
            <label class="form-group has-float-label mb-4">
                <input class="form-control" placeholder="<?php _eline('entry_email'); ?>" type="email" v-model="email" required>
                <span><?php _eline('entry_email'); ?></span>
            </label>

            <div v-if="showMessage" class="d-flex">
                <p class="font-14 text-danger">{{ errorMessage }}</p>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php _ebase_url(LOGIN_ROUTE); ?>"><?php _eline('text_remember_password') ?></a>
                <button :disabled="sendingRequest" class="btn btn-primary btn-lg btn-shadow" type="submit"><?php _eline('entry_reset'); ?></button>
            </div>
        </form>
    </div>
    <?php if(1==2) { ?>
    <div class="col-xl-12 pa-0">
        <div class="auth-form-wrap pt-xl-0 pt-70">
            <div class="auth-form w-xl-20 w-lg-30 w-sm-75 w-100">
                <a class="auth-brand text-center d-block mb-20" href="javascript:void(0);">
                    <img class="brand-img" src="assets/img/logo.svg" alt="brand"/>
                </a>
                <form id="frm-forgot" @submit.prevent="handleSubmit" data-parsley-validate="true">
                    <p class="text-center mb-30"><?php _eline('text_reset_password'); ?></p>
                    <div class="form-group">
                        <input class="form-control" placeholder="<?php _eline('entry_email'); ?>" type="email" v-model="email" required>
                    </div>
                    <p v-if="showMessage" class="font-14 text-center mt-15 mb-15 text-danger">{{ errorMessage }}</p>
                    <button class="btn btn-primary btn-block" type="submit"><?php _eline('entry_reset'); ?></button>
                    <p class="font-14 text-center mt-15"><a href="<?php _ebase_url(LOGIN_ROUTE); ?>"><?php _eline('text_remember_password'); ?></a></p>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
</script>
<div id="forgot-container" class="container">
    <div class="row h-100">
        <div class="col-12 col-md-10 mx-auto my-auto">
            <div class="card auth-card">
                <div class="position-relative image-side ">

                    <p class="text-white h2"><?php _eline('text_reset_password'); ?></p>

                    <p class="white mb-0">
                        Please use your credentials to login.
                    </p>
                </div>
                <forgot-form></forgot-form>
            </div>
        </div>
    </div>
</div>
<?php if(1==2){ ?>
<div id="forgot-container" class="hk-wrapper">
    <div class="hk-pg-wrapper hk-auth-wrapper">
        <div class="container-fluid">
            <div class="row">
                <forgot-form></forgot-form>
            </div>
        </div>
    </div>
</div>
<?php } ?>
