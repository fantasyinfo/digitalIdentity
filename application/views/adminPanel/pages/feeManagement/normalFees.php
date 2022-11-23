<?php





$sql = "SELECT DISTINCT(fee_group_id) FROM " . Table::newfeeclasswiseTable . " WHERE class_id = '{$studentData['class_id']}' AND section_id = '{$studentData['section_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND student_id = '{$studentData['id']}'  GROUP BY fee_group_id";

$feesDetails = $this->db->query($sql)->result_array();

$gAmount = 0.00;
$gFine = 0.00;
$gdiscount = 0.00;
$gFine = 0.00;
$gFineD = 0.00;
$gPaid = 0.00;
$gBalance = 0.00;

$todayDate = date('Y-m-d');

$j = 1;
$a = 1;
$b = 1;


foreach ($feesDetails as $f) {
    $sqln = "SELECT nfm.id as fmtId, nfm.amount, nfm.fineType,nfm.finePercentage,nfm.fineFixAmount, nfm.dueDate,
nft.id as nftId, nft.feeTypeName, nft.shortCode, nfg.id as nfgId, nfg.feeGroupName FROM
" . Table::newfeemasterTable . " nfm 
JOIN " . Table::newfeestypesTable . " nft ON nft.id = nfm.newFeeType
JOIN " . Table::newfeesgroupsTable . " nfg ON nfg.id = nfm.newFeeGroupId
WHERE nfm.newFeeGroupId = '{$f['fee_group_id']}' ";

    $groupWiseFeeDetails = $this->db->query($sqln)->result_array();
    $fGN = @$groupWiseFeeDetails[0]['feeGroupName'];

// echo $this->db->last_query();
?>



<?php

    foreach ($groupWiseFeeDetails as $gwf) {
        // search student all depoists
        $fineAmount = 0.00;
        if ($todayDate > $gwf['dueDate']) {
            if ($gwf['fineType'] == '1') {
                $fineAmount = 0.00;
            } else if ($gwf['fineType'] == '2') {
                // percenrtage
                $fineAmount = ceil($gwf['amount'] * @$gwf['finePercentage'] / 100);
            } else if ($gwf['fineType'] == '3') {
                // fixed amount
                $fineAmount = @$gwf['fineFixAmount'];
            }
        } else {
            $fineAmount = 0.00;
        }

        if ($fineAmount == 0) {
            $fShow = false;
        } else {
            $fShow = true;
        }



        $feesDeposits = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::newfeessubmitmasterTable . " WHERE stuId = '{$_GET['stu_id']}' AND classId = '{$studentData['class_id']}' AND sectionId = '{$studentData['section_id']}' AND fmtId = '{$gwf['fmtId']}' AND nftId = '{$gwf['nftId']}' AND nfgId = '{$gwf['nfgId']}' AND status = '1' AND session_table_id = '{$_SESSION['currentSession']}'");


        $depositAmt = 0.00;
        $fineAmt = 0.00;
        $discountAmt = 0.00;
        if (!empty($feesDeposits)) {


            foreach ($feesDeposits as $fd) {
                $depositAmt = $depositAmt + $fd['depositAmount'];
                $fineAmt = $fineAmt + $fd['fine'];
                $discountAmt = $discountAmt + $fd['discount'];
                $b++;
            }
        }

        $amountNow = $gwf['amount'] - $depositAmt;
    ?>
<tr>
    <td><input type="checkbox" class="feeTypeCheckbox" id="fees_id_<?= $j ?>" value="<?= $j ?>"></td>
    <td>
        <?= $fGN; ?>
    </td>
    <td>
        <?= $gwf['feeTypeName']; ?>
    </td>
    <td>
        <?= date('d-m-Y', strtotime($gwf['dueDate'])); ?>
    </td>
    <td>
        <?php

        $bstatusBalance = ($gwf['amount'] - $depositAmt) - $discountAmt;
        if ($bstatusBalance > 0 && $bstatusBalance < $gwf['amount']) {
            // partail
            echo "<span class='badge badge-warning'>Partial</span>";
        } else if ($amountNow == $gwf['amount']) {
            // due
            echo "<span class='badge badge-danger'>UnPaid</span>";
        } else if ($bstatusBalance == 0) {
            // paid
            echo "<span class='badge badge-success'>Paid</span>";
        }


            ?>
    </td>
    <td>
        <?php
        $gAmount = $gAmount + $gwf['amount'];
        $gFine = $gFine + $fineAmount;
        if ($fShow) {
            echo number_format($gwf['amount'], 2) . " + <span style='color:red;'> " . number_format($fineAmount, 2) . "</span>";
        } else {
            $aaount = $gwf['amount'];
            echo number_format($aaount, 2);
        }

            ?>
    </td>
    <td></td>
    <td></td>
    <td></td>
    <td>
        <?php echo number_format($discountAmt, 2);
        $gdiscount = $gdiscount + $discountAmt;
            ?>
    </td>
    <td>
        <?php echo number_format($fineAmt, 2);
        $gFineD = $gFineD + $fineAmt;

            ?>
    </td>
    <td>
        <?php echo number_format($depositAmt, 2);
        $gPaid = $gPaid + $depositAmt;

            ?>
    </td>
    <td>
        <?php
        if ($amountNow > 0) {

            $bblance = $amountNow - $discountAmt;
            if ($bblance > 0) {
                echo number_format($bblance, 2);
                $gBalance = $gBalance + $bblance;
            } else {
                echo 0.00;
                $gBalance = $gBalance;
            }
        } else {

            $bblance = ($gwf['amount'] - $depositAmt) - $discountAmt;

            if ($bblance > 0) {
                echo number_format($bblance, 2);
                $gBalance = $gBalance + $bblance;
            } else {
                echo 0.00;
                $gBalance = $gBalance;
            }
        }

            ?>
    </td>
    <td>

        <input type="hidden" id="deposit_<?= $j ?>" value="<?= $depositAmt ?>">
        <input type="hidden" id="discount_<?= $j ?>" value="<?= $discountAmt ?>">
        <input type="hidden" id="fineD_<?= $j ?>" value="<?= $fineAmt ?>">
        <input type="hidden" id="fgName_<?= $j ?>" value="<?= $fGN . ' ( ' . $gwf['feeTypeName'] . ' ) '; ?>">
        <input type="hidden" id="todayDate_<?= $j ?>" value="<?= $todayDate ?>">
        <input type="hidden" id="amount_<?= $j ?>" value="<?= $gwf['amount'] ?>">
        <input type="hidden" id="fine_<?= $j ?>" value="<?= $fineAmount ?>">
        <input type="hidden" id="fmtId_<?= $j ?>" value="<?= $gwf['fmtId']; ?>">
        <input type="hidden" id="nftId_<?= $j ?>" value="<?= $gwf['nftId']; ?>">
        <input type="hidden" id="nfgId_<?= $j ?>" value="<?= $gwf['nfgId']; ?>">
        <input type="hidden" id="stuId_<?= $j ?>" value="<?= $_GET['stu_id']; ?>">
        <input type="hidden" id="classId_<?= $j ?>" value="<?= $studentData['class_id']; ?>">
        <input type="hidden" id="sectionId_<?= $j ?>" value="<?= $studentData['section_id']; ?>">
        <?php

        if ($bblance > 0) {
            echo ' <button type="button" class="btn btn-dark" onclick="submitFees(' . $j . ')"><i class="fa-solid fa-plus"></i></button>';
        } else {
            echo "<a disabled class='badge badge-success'>Paid</a>";
        } ?>
    </td>

</tr>

<?php

        $a = 1;
        $depositAmt = 0.00;
        $fineAmt = 0.00;
        $discountAmt = 0.00;
        if (!empty($feesDeposits)) {

            foreach ($feesDeposits as $fd) {


                $depositAmt = @$depositAmt + $fd['depositAmount'];
                $fineAmt = @$fineAmt + $fd['fine'];
                $discountAmt = @$discountAmt + $fd['discount'];


        ?>

<tr class="bg-light-dark">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td><img src="<?= base_url() . HelperClass::uploadImgDir . 'table-arrow.png' ?>"></td>
    <td>
        <?= $fd['invoiceId']; ?>
    </td>
    <td>
        <?=($fd['paymentMode']=='1') ? 'Offline' : 'Online'; ?>
    </td>
    <td>
        <?= date('d-m-y', strtotime($fd['depositDate'])); ?>
    </td>
    <td>
        <?= number_format($fd['discount'], 2); ?>
    </td>
    <td>
        <?= number_format($fd['fine'], 2); ?>
    </td>
    <td>
        <?= number_format($fd['depositAmount'], 2); ?>
    </td>
    <td></td>
    <td>
        <a target="_blank" href="<?= base_url('feesInvoice?fees_id=') . $fd['randomToken'] ?>" class="btn btn-info"> <i
                class="fa-solid fa-file-invoice"></i> </a>&nbsp;&nbsp;&nbsp;
        <a href="?action=deleteInvoice&delete_id=<?= $fd['id'] ?>&stu_id=<?= $_GET['stu_id'] ?>"
            onclick="return confirm('Are you sure want to delete this?');"><i
                class="fa-sharp fa-solid fa-trash"></i></a>
    </td>

</tr>
<?php }
        } ?>

<?php $j++;
    }
}

?>
