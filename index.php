<?php 
declare(strict_types=1);
require 'transactions.php';



/**
 * Calculate the total amount of all transactions
 *
 * @return float
 */

function CalculateTotalAmount() : float{
    global $transactions;
    $sum = 0;
    foreach ($transactions as $transaction) {
        $sum += $transaction['amount'];
    }
    return $sum;
}

/**
 * Find transactions by description
 *
 * @param string $desc
 * @return array
 */

function FindByDescription(string $desc) : array{
    global $transactions;
    $desc = trim($desc);
    if(empty($desc)){
        return [];
    }   
    $result = [];
    for($i = 0; $i < count($transactions); $i++){
        if(stripos($transactions[$i]['description'], $desc) !== false){
            $result[$i] = $transactions[$i];
        }
    }
    return array_values($result); // reindex the array
}

/**
 * Find transaction by ID
 *
 * @param int $id
 * @return array
 */
function FindById(int $id) : array {
    global $transactions;
    $result = [];
    foreach ($transactions as $transaction) {
        if($transaction['id'] == $id){
            $result = $transaction;
            echo "ID: " . $result['id'] . " | date: " . $result['date'] . " | amount: " . $result['amount'] . " | description: " . $result['description'] . " | merchant: " . $result['merchant'];
        }
    }
    if(empty($result)){
        echo "No transaction found";
    }
    return $result;
}

/**
 * Calculate the number of days since a transaction
 *
 * @param string $date
 * @return int
 */
function DaySinceTransaction(string $date) : int{
    $date = new DateTime($date);
    $now = new DateTime();
    $interval = $now->diff($date);
    return $interval->days;
}

/**
 * Add a new transaction
 *
 * @param int $id
 * @param string $date
 * @param float $amount
 * @param string $description
 * @param string $merchant
 */
function addTransaction(int $id, string $date, float $amount, string $description, string $merchant) : void {
    global $transactions;

    $transaction = [
        'id' => $id,
        'date' => $date,
        'amount' => $amount,
        'description' => $description,
        'merchant' => $merchant
    ];

    $transactions[] = $transaction;
}
addTransaction(11, '2020-01-01', 1050, 'Salary', 'Employer');
addTransaction(12, '2020-01-02', 41, 'Food', 'Restaurant');
addTransaction(13, '2020-01-03', 3, 'Transport', 'Bus');
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Lab 3</title>
</head>
<body>
    <h4>All transactions</h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Merchant</th>
                <th>Day Since Transaction</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['date']; ?></td>
                    <td><?php echo $transaction['amount']; ?></td>
                    <td><?php echo $transaction['description']; ?></td>
                    <td><?php echo $transaction['merchant']; ?></td>
                    <td><?php echo DaySinceTransaction($transaction['date'])?></td>
                </tr>
                <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total amount: <?=CalculateTotalAmount();?></td>
            </tr>
            <tr>
                <td colspan="3">
                <form method="POST">
                    <h5>Find by Description</h5>
                    <p>Desciption: <input type="text" name="description" value="<?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ' ' ?>"/></p>
                    <input type="submit" value="Find by description" />
                </form>
                </td>
                <td colspan="3">
                    <?php 
                    $desc = $_POST['description'] ?? ' ';
                    $filtered = FindByDescription($desc);
                    if (count($filtered) > 0):
                        foreach($filtered as $transaction): ?>
                            <p><?php echo "ID: " . $transaction['id'] . " | date: " . $transaction['date'] . " | amount: " . $transaction['amount'] . " | description: " . $transaction['description'] . " | merchant: " . $transaction['merchant']; ?></p>
                        <?php endforeach; 
                    elseif(count($filtered) == 0):
                        echo "No transactions found";
                    endif;
                    ?>
                </td>
                <tr>
                    <td colspan="3">
                        <form method="POST">
                            <h5>Find by ID</h5>
                            <p>ID: <input type="text" name="FindById" value="<?= isset($_POST['FindById']) ? htmlspecialchars($_POST['FindById']) : '' ?>" /></p>
                            <input type="submit" value="Find by ID" />
                        </form>
                    </td>
                    <td colspan="3">
                    <?php 
                    if(isset($_POST['FindById'])):
                        $id = (int)$_POST['FindById'];
                        $transaction = FindById($id);?>
                        <?php 
                        if(empty($transaction)){
                            echo " ";   
                        } else { ?>
                            <p id="color">Days since transaction: <?php echo " " . DaySinceTransaction($transaction['date']); ?></p>
                        <?php } ?>
                    <?php endif; ?>
                    </td>
                </tr>
            </tr>
        </tfoot>
    </table>
<form method="post">
    <h5>Add new transaction</h5>
    <p>ID: <input type="text" name="id" value="<?php echo count($transactions) + 1 ?>" readonly/></p>
    <p>Date: <input type="date" name="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ' '?>"/></p>
    <p>Amount: <input type="number" name="amount" step="0.05" value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ' ' ?>"/></p>
    <p>Description: <input type="text" name="desc" value="<?php echo isset($_POST['desc']) ? htmlspecialchars($_POST['desc']) : ' ' ?>"/></p>
    <p>Merchant: <input type="text" name="merchant" value="<?php echo isset($_POST['merchant']) ? htmlspecialchars($_POST['merchant']) : ' ' ?>"/></p>
    <input type="submit" value="Add transaction" />
</form>

    <?php 
        if(isset($_POST['id'], $_POST['date'], $_POST['amount'], $_POST['desc'], $_POST['merchant'])){
            $id = (int)$_POST['id'];
            $date = $_POST['date'];
            $amount = (float)$_POST['amount'];
            $desc = $_POST['desc'];
            $merchant = $_POST['merchant'];
            addTransaction($id, $date, $amount, $desc, $merchant);
        }

    ?>
    <a href="images.php">go to file</a>
</body>
</html>

