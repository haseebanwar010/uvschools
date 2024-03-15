<div class="table-responsive">
    <table class="myTable display nowrap" cellspacing="0" width="100%" border="1px">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo lang('lbl_name');?></th>
                <?php for ($i = $from; $i <= $to; $i++) { ?>
                    <th><?= $i ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th><?php echo lang('lbl_name');?></th>
                <?php for ($i = $from; $i <= $to; $i++) { ?>
                    <th><?= $i ?></th>
                <?php } ?>
            </tr>
        </tfoot>
        <tbody id="myTableBody3">
            <?php if (count($attendance) > 0) {
                foreach ($attendance as $attend) {
                    ?>
                    <tr>
                        <td><?php echo $attend->id; ?></td>
                        <td><?php echo $attend->name; ?></td>
                            <?php for ($i = $from; $i <= $to; $i++) { ?>
                            <td>
                                <?php
                                $attends_dates = explode(",", $attend->attendance);
                                $dayss = array();
                                $statuss = array();
                                for ($ii = 0; $ii < count($attends_dates); $ii++) {
                                    $date_and_status = explode("=>", $attends_dates[$ii]);
                                    $date = $date_and_status[0];
                                    $status = $date_and_status[1];
                                    $date_day = explode("-", $date)[2];
                                    $statuss[$date_day] = $status;
                                    array_push($dayss, $date_day);
                                }
                                //print_r($statuss);
                                if (array_key_exists($i, $statuss)) {
                                    echo $statuss[$i];
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } } else { ?>
                <tr><td colspan="3"><?php echo lang('no_record') ?></td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

