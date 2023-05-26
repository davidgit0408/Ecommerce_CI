<section class="main-content">
    <div class="row">
        <div class="col-md-12 col-12 mt-4 pt-2">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade bg-white show active shadow rounded p-4 text-center" id="dash" role="tabpanel" aria-labelledby="dashboard">
                    <i class="fas fa-check-circle fa-4x text-success"></i>
                    <h4 class="h4 text-success"><?= !empty($this->lang->line('payment_completed')) ? $this->lang->line('payment_completed') : 'Payment Completed' ?></h4>
                    <p><?= !empty($this->lang->line('payment_completed_message')) ? $this->lang->line('payment_completed_message') : 'Payment Completed Succesfully.' ?></p>
                    <p><?= !empty($this->lang->line('thank_you_for_shopping')) ? $this->lang->line('thank_you_for_shopping') : 'Thank you for Shopping with Us.' ?></p>
                    <a class="btn btn-primary" href="<?=base_url('products')?>"><?= !empty($this->lang->line('continue_shopping')) ? $this->lang->line('continue_shopping') : 'Continue Shopping' ?></a>
                </div>
            </div>
        </div>
    </div>
</section>