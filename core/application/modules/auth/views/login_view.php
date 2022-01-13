<?php $cb = $this->brahma; ?>
<div id="login-container" class="bg-image" style="background-image: url('<?php _easset_url('assets/img/bg/bg.jpg'); ?>');">
    <div class="row mx-0 bg-black-op">
        <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
            <div class="p-30 invisible" data-toggle="appear">
                <p class="font-size-h3 font-w600 text-white">
                    <?php echo _get_setting('company_name',CORE_APP_TITLE); ?>
                </p>
                <p class="font-italic text-white-op">
                    Copyright &copy; <span class="js-year-copy"><?php echo date('Y'); ?></span>
                </p>
            </div>
        </div>
        <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
            <div class="content content-full">
                <div class="px-30 py-10 text-center">
                    <img class="img-fluid w-250p" src="<?php _easset_url('assets/img/logo-vertical.svg'); ?>" alt="<?php echo _get_setting('company_name',CORE_APP_TITLE); ?>">
                    <h1 class="h3 font-w700 mt-30 mb-10 font-14"><?php _eline('text_signin_acccount'); ?></h1>
                </div>
                <login-form></login-form>
            </div>
        </div>
    </div>
</div>
<script type="text/x-template" id="login-template">
    <form id="frm-login" @submit.prevent="handleSubmit" class="js-validation-signin px-30" data-parsley-validate="true">
        <div class="form-group row">
            <div class="col-12">
                <div class="form-material floating">
                    <input id="login-email" class="form-control" type="email" v-model="login.email" required data-parsley-required-message="<?php _eline('error_email'); ?>">
                    <label for="login-email"><?php _eline('entry_email'); ?></label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12">
                <div class="form-material floating">
                    <input id="login-password" class="form-control" type="password" v-model="login.password" required data-parsley-required-message="<?php _eline('error_password'); ?>">
                    <label for="login-password"><?php _eline('entry_password'); ?></label>
                </div>
            </div>
        </div>
        <?php if(1==2){ ?>
        <div class="form-group row">
            <div class="col-12">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="login-remember-me" name="login-remember-me">
                    <label class="custom-control-label" for="login-remember-me">Remember Me</label>
                </div>
            </div>
        </div>
        <?php } ?>
        <p v-if="showMessage" class="text-danger">{{ errorMessage }}</p>
        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-hero btn-alt-primary" :disabled="sendingRequest">
                <i class="si si-login mr-10"></i> <?php _eline('entry_login'); ?>
            </button>
            <?php if(1==2){ ?>
            <div class="mt-30">
                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="<?php _ebase_url(FORGOT_ROUTE); ?>">
                    <i class="fa fa-warning mr-5"></i> <?php _eline('text_having_trouble') ?>
                </a>
            </div>
            <?php } ?>
        </div>
    </form>
</script>
