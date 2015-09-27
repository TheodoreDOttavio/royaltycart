<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
?>

<div class="rcbox_plain">
 <div class="rctitle">Order #<?php echo $order_id;?></div>
</div>

    <table>
        <p>Order ID: #<?php echo $order_id;?></p>
        <?php if($txn_id){?>
        <p>Transaction ID: #<?php echo $txn_id;?></p>
        <?php } ?>
        <tr>
            <td>First Name</td>
            <td><input type="text" size="40" name="royaltycart_first_name" value="<?php echo $first_name; ?>" /></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" size="40" name="royaltycart_last_name" value="<?php echo $last_name; ?>" /></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><input type="text" size="40" name="royaltycart_phone" value="<?php echo $phone; ?>" /></td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td><input type="text" size="40" name="royaltycart_email" value="<?php echo $email; ?>" /></td>
        </tr>
        <tr>
            <td>IP Address</td>
            <td><input type="text" size="40" name="royaltycart_ipaddress" value="<?php echo $ip_address; ?>" /></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><textarea name="royaltycart_address" cols="85" rows="2"><?php echo $address;?></textarea></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><input type="text" size="20" name="royaltycart_total_amount" value="<?php echo $total_amount; ?>" /></td>
        </tr>
        <tr>
            <td>Item(s) Ordered:</td>
            <td><textarea name="royaltycart_items_ordered" cols="85" rows="5"><?php echo $items_ordered;?></textarea></td>
        </tr>
        <tr>
            <td>Buyer Email Sent?</td>
            <td><input type="text" size="20" name="royaltycart_buyer_email_sent" value="<?php echo $email_sent_field_msg; ?>" readonly /></td>
        </tr>
    </table>
    
    <p>Stuff to do - add an admin Order Box with these things:<br>
    Display who was paid and how much<br>
    Add a count of downloads and an option to reset the downloads<br>
    Add an option to add products to this order<br>
    Add a button to re-email the download link (thanks for ordering) button<br>
    </p>
