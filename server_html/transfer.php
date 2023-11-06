<?php
// Step 1: Code for generating the anti-CSRF token

session_start(); // Start the session if you haven't already

if (!isset($_SESSION['antiCsrf'])) {
    // Generate and set the CSRF token only if it doesn't exist in the session
    $csrfToken = md5(uniqid(rand(), TRUE));
    $_SESSION['antiCsrf'] = $csrfToken;
}

// Step 2: Code for the form presentation
$form = '
<form name="transferForm" action="transfer.php" method="POST">
        <div class="box">
        <h1>BANK XYZ - Confirm Transfer</h1>
        <p>
        Do You want to confirm a transfer of <b>'. $_REQUEST['amount'] .' &euro;</b> to account: <b>'. $_REQUEST['account'] .'</b> ?
        </p>
        <label>
            <input type="hidden" name="amount" value="' . $_REQUEST['amount'] . '" />
            <input type="hidden" name="account" value="' . $_REQUEST['account'] . '" />
            <input type="hidden" name="antiCsrf" value="' . $_SESSION['antiCsrf'] . '" />
            <input type="submit" class="button" value="Transfer Money" />
        </label>

    <div>
</form>';

echo $form;

// Step 3: Code to check anti-CSRF token and perform the transfer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['antiCsrf'])) {
    if (!empty($_SESSION['antiCsrf']) && $_POST['antiCsrf'] === $_SESSION['antiCsrf']) {
        echo '<p>' . $_POST['amount'] . ' &euro; successfully transferred to account: ' . $_POST['account'] . ' </p>';
    } else {
        echo '<p>Transfer KO</p>';
    }
}
?>
