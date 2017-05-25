<?php
$pinInfo =  array(
                array('3.3v', '', ''), 
                array('5v', '', ''), 
                array('SDA.1', '8', '2'), 
                array('5v', '', ''), 
                array('SCL.1', '9', '3'), 
                array('0v', '', ''), 
                array('GPIO. 7', '7', '4'), 
                array('TxD', '15', '14'), 
                array('0v', '', ''), 
                array('RxD', '16', '15'), 
                array('GPIO. 0', '0', '17'), 
                array('GPIO. 1', '1', '18'), 
                array('GPIO. 2', '2', '27'), 
                array('0v', '', ''), 
                array('GPIO. 3', '3', '22'), 
                array('GPIO. 4', '4', '23'), 
                array('3.3v', '', ''), 
                array('GPIO. 5', '5', '24'), 
                array('MOSI', '12', '10'), 
                array('0v', '', ''), 
                array('MISO', '13', '9'), 
                array('GPIO. 6', '6', '25'), 
                array('SCLK', '14', '11'), 
                array('CE0', '10', '8'), 
                array('0v', '', ''), 
                array('CE1', '11', '7'), 
                array('SDA.0', '30', '0'), 
                array('SCL.0', '31', '1'), 
                array('GPIO.21', '21', '5'), 
                array('0v', '', ''), 
                array('GPIO.22', '22', '6'), 
                array('GPIO.26', '26', '12'), 
                array('GPIO.23', '23', '13'), 
                array('0v', '', ''), 
                array('GPIO.24', '24', '19'), 
                array('GPIO.27', '27', '16'), 
                array('GPIO.25', '25', '26'), 
                array('GPIO.28', '28', '20'), 
                array('0v', '', ''), 
                array('GPIO.29', '29', '21')
            );
?>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta name="description" content="Web page replication of GPIO status" />
<meta name="author" content="Ben Shorey ©2016 http://www.instructables.com/member/BenS226/" />
<title>GPIO Status</title>
<style>
body {
    font: 10pt Arial;
    margin: 10px;
}
table, th, td {
    font: 10pt Arial;
    border: 1px solid gray;
    text-align: center;
    border-collapse: collapse;
    padding: 3px;
}
table {
    border: 2px solid gray;
}
.buttonLow {
    color: #c00000;
}
.buttonHigh {
    color: #008000;
}
</style>
</head>
        <body>
        <form method="get" action="">

        <table width=80% id="gpioTable">
        <td>BCM#</td>
        <td>wPi#</td>
        <td>Name</td>
        <td>Mode</td>
        <td>Value</td>

        <td colspan=2>Phys#</td>

        <td>Value</td>
        <td>Mode</td>
        <td>Name</td>
        <td>wPi#</td>
        <td>BCM#</td>
        <?php
            for ($i = 0; $i < 40; $i+=2) { ?>
            <tr>
                <?php
                // for each physical pin look up name and equivalent BCM and wPi number and create table
                // and add in buttons to control the pin values and modes
                // left column "a"
                // formats table to mimic table given by "gpio readall" command but could be adjusted to give any format

                $a_pin_name = $pinInfo[$i][0];
                $a_pin_wPi = $pinInfo[$i][1];
                $a_pin_BCM = $pinInfo[$i][2];


                if ($a_pin_BCM == "") { ?>
                <td colspan=5><?php echo $a_pin_name ?></td>
                <td><?php echo $i ?></td>
                <?php } else { ?>
                <td><?php echo $a_pin_BCM  ?></td>
                <td><?php echo $a_pin_wPi  ?></td>
                <td><?php echo $a_pin_name ?></td>
                <td><input type=button onclick='change_pin_mode(<?php echo $a_pin_BCM ?>, 0)' value='' id='mode<?php echo $a_pin_BCM; ?>'></td>
                <td><input type=button onclick='change_pin_value(<?php echo $a_pin_BCM ?>, 0)' value='' id='value<?php echo $a_pin_BCM; ?>'></td>
                <td><?php echo $i ?></td>
                <?php }
                // right column "b"
                $b_pin_name = $pinInfo[$i+1][0];
                $b_pin_wPi = $pinInfo[$i+1][1];
                $b_pin_BCM = $pinInfo[$i+1][2];
                if ($b_pin_BCM == "") { ?>
                <td><?php echo $i+1 ?></td>
                <td colspan="5"><?php echo $b_pin_name ?></td>
                <?php } else { ?>
                <td><?php echo $i+1 ?></td>
                <td><input type=button onclick='change_pin_value(<?php echo $b_pin_BCM ?>, 0)' value='' id='value<?php echo $b_pin_BCM; ?>'></td>
                <td><input type=button onclick='change_pin_mode(<?php echo $b_pin_BCM ?>, 0)' value='' id='mode<?php echo $b_pin_BCM; ?>'></td>
                <td><?php echo $b_pin_name ?></td>
                <td><?php echo $b_pin_wPi  ?></td>
                <td><?php echo $b_pin_BCM  ?></td>
                <?php } ?>
            <tr>
            <?php } ?>

        </table>
        </form>
        <table width="80%">
            <tr>
                <td nowrap><button type="button" onclick="get_status()" id="update_button">Update</button></td>
                <td nowrap><input type="checkbox" onclick="toggle_update()" id="update_checkbox">Auto Update</input></td>
                <td width="80%"><div id="workspace" style="text-align: center;"></div></td>
            </tr>
        </table>
    </body>

<script src="script.js"></script><!-- javascript file has to go here otherwise initial update script wont work -->
</html>