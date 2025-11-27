<?php echo view('header'); ?>

<body>
    <?php echo view('topHeader');?>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header">
                    <h5>transaction History</h5>
                </div>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer Name</th>
                                <th> Amount</th>
                                <th> Before Paid Amount</th>
                                <th>after_paid_amount</th>
                                <th>Paid Date</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($transactions as $index => $transaction):
                                ?>
                                    <tr>
                                        <td><?= $start + $index ?></td>
                                        <td><?= esc($transaction['customer_name']) ?></td>
                                        <td><?= esc($transaction['amount']) ?></td>
                                        <td><?= esc($transaction['before_paid_amount']) ?></td>
                                        <td><?= esc($transaction['after_paid_amount']) ?></td>
                                        <td><?=esc($transaction['created_at'])?></td>
                                        <td><?=esc($transaction['remark'])?></td>
                                        <td class="text-center">
                                            <?php
                                            $role = session()->get('role');
                                            $actionUrl = ($role === 'admin')
                                                ? ('admin/transaction/edit/' . $transaction['id'])
                                                : ('user/transaction/edit/' . $transaction['id']);
                                            ?>
                                            <a href="<?=base_url($actionUrl) ?>" class="btn-icon edit" title="Edit User">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                              <a href="<?=base_url('user/transaction-history/' .$transaction['id']) ?>" class="btn-icon edit " title="Transaction History" >
                                                  <ion-icon name="document-text-outline"></ion-icon>
                                            </a>
                                            <form method="post" action="<?= base_url('client/delete/' . $transaction['id']) ?>" style="display:inline;">
                                                <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php echo view('sidebar'); ?>
    <?php echo view('footerlink'); ?>