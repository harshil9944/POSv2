<?php
    $obj = _get_var('obj');
?>
<div class="block">
    <div class="block-content block-content-full border-b d-flex justify-content-between align-items-center">
            <h4 class="mb-0 d-flex align-items-center">
                <?php echo custom_date_format($obj['date'],'d/m/Y'); ?>
                <?php if(isset($obj['current_status']['title'])) { ?>
                <span class="badge badge-pill badge-primary font-14 ml-2">
                    <?php echo $obj['current_status']['title']; ?>
                </span>
                <?php } ?>
            </h4>
        <a class="btn btn-secondary" href="<?php echo _get_var('back_url'); ?>">
            <i class="fa fa-arrow-left text-primary mr-5"></i> All Bookings
        </a>
    </div>
    <div class="block-content block-content-full">
        <div class="row py-20">
            <div class="col-md-6">
                <table class="table table-striped table-borderless mt-20">
                    <tbody>
                    <tr>
                        <td class="font-w600">Booking Name</td>
                        <td><?php echo $obj['bookingName']; ?></td>
                    </tr>
                    <tr>
                        <td class="font-w600">Total Advance</td>
                        <td><?php echo custom_money_format($obj['advance']); ?></td>
                    </tr>
                    <tr>
                        <td class="font-w600">Number of Persons</td>
                        <td><?php echo $obj['numberOfPerson']; ?></td>
                    </tr>
                    <tr>
                        <td class="font-w600">Email</td>
                        <td><?php echo $obj['email']; ?></td>
                    </tr>
                    <tr>
                        <td class="font-w600">Phone</td>
                        <td><?php echo $obj['phone']; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 nice-copy">
                <h4 class="mb-2">Menu</h4>
                <p><?php echo $obj['menu'] ?: '-'; ?></p>
                <h4 class="mb-2">Remark</h4>
                <p><?php echo $obj['remark'] ?: '-';; ?></p>
                <h4 class="mb-2">Description</h4>
                <p><?php echo $obj['description'] ?: '-';; ?></p>
            </div>
        </div>
    </div>
</div>
