<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-envelope"></i><?= lang('contacts'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php if ($contacts) { ?>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('name'); ?></th>
                                <th><?= lang('email'); ?></th>
                                <th><?= lang('phone'); ?></th>
                                <th><?= lang('subject'); ?></th>
                                <th><?= lang('message'); ?></th>
                                <th><?= lang('created_at'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($contacts as $contact) { ?>
                                <tr>
                                    <td class="text-center"><?= $i; ?></td>
                                    <td class="text-center"><?= ucfirst($contact->name); ?></td>
                                    <td class="text-center"><?= $contact->email; ?></td>
                                    <td class="text-center"><?= $contact->phone; ?></td>
                                    <td class="text-center"><?= $contact->subject; ?></td>
                                    <td class="text-center"><?= $contact->message; ?></td>
                                    <td class="text-center"><?= $contact->created_at; ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <?= lang('no_contacts_found'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
