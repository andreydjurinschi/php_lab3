<?php 
declare(strict_types=1);
require 'transactions.php';

$desc = $_POST['description'] ?? ' ';

function CalculateTotalAmount(array $transactions) : float{
    $sum = 0;
    foreach ($transactions as $transaction) {
        $sum += $transaction['amount'];
    }
    return $sum;
}

function FindByDescription(array $transactions, string $desc = '') : array{
    $desc = trim($desc);
    if(isset($_POST['description'])){
        $desc = htmlspecialchars($_POST['description']);
    }
    if(empty($desc)){
        return [];
    }   
    $result = [];
    for($i = 0; $i < count($transactions); $i++){
        if(stripos($transactions[$i]['description'], $desc) !== false){
            $result[$i] = $transactions[$i];
        }
    }
    return $result;
}

function FindById(int $id, $transactions) : void {
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Document</title>
</head>
<body>
    <h4>All transactions
    </h4>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Merchant</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['date']; ?></td>
                    <td><?php echo $transaction['amount']; ?></td>
                    <td><?php echo $transaction['description']; ?></td>
                    <td><?php echo $transaction['merchant']; ?></td>
                </tr>
                <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total amount: <?=CalculateTotalAmount($transactions);?></td>
            </tr>
            <tr>
                <td colspan="3">
                <form method="POST">
                    <h5>Find by Description</h5>
                    <p>Desciption: <input type="text" name="description" /></p>
                    <input type="submit" value="Find by description" />
                </form>
                </td>
                <td colspan="2">
                    <?php 
                    $filtered = FindByDescription($transactions);
                    if (count($filtered) > 0):
                        foreach($filtered as $transaction): ?>
                            <p><?php echo "ID: " . $transaction['id'] . " | date: " . $transaction['date'] . " | amount: " . $transaction['amount'] . " | description: " . $transaction['description'] . " | merchant: " . $transaction['merchant']; ?></p>
                        <?php endforeach; 
                    elseif(count($filtered) == 0 || $desc == ' '):
                        echo "No transactions found";
                    endif;
                    ?>
                </td>
                <tr>
                    <td colspan="3">
                        <form method="POST">
                            <h5>Find by ID</h5>
                            <p>ID: <input type="text" name="id" /></p>
                            <input type="submit" value="Find by ID" />
                        </form>
                    </td>
                    <td colspan="2">
                    <?php 
                    if(isset($_POST['id'])){
                        $id = (int)$_POST['id'];
                        FindById($id, $transactions);
                    }
                    ?>
                </tr>
            </tr>
        </tfoot>
    </table>
</body>
</html>

