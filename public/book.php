<?php

    require __DIR__ . '/../connec.php';

    function createConnection(): PDO
    {
        return new PDO(DSN . ";charset=utf8", USER, PASS);
    }

    function getAllPay(): array
    {
        $connection = createConnection();

        $statement = $connection->query("SELECT name, payment FROM bribe");
        $bribes = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $bribes;
    }

    function addPay()
    {
        $name = '';
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {

            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $name = $_POST['name'];
            }
            $payment = 0;

            if (isset($_POST['payment']) && $_POST['payment'] > 0) {
                $payment = $_POST['payment'];
            }

            if(!empty($name) && $payment > 0) {
                $connection = createConnection();
        
                $query = "INSERT INTO bribe(name, payment) VALUES (:name, :payment)";
                $statement = $connection->prepare($query);
                $statement->bindValue(':name', $name, PDO::PARAM_STR);
                $statement->bindValue(':payment', $payment, PDO::PARAM_INT);
        
                return $statement->execute();
            }
        }

    }

    if(isset($_POST['submit']))
    {
        addPay();
    }
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/book.css">
        <title>Checkpoint PHP 1</title>
    </head>
    <body>

    <?php include 'header.php'; ?>

    <main class="container">

        <section class="desktop">
            <img src="image/whisky.png" alt="a whisky glass" class="whisky"/>
            <img src="image/empty_whisky.png" alt="an empty whisky glass" class="empty-whisky"/>

            <div class="pages">
                <div class="page leftpage">
                    Add a bribe
                    <form class="additionForm"  action="" method="POST">

                    <div class="addition">
                            <label for="name">Name</label>
                            <input class="formInput" type="text" id="name" name="name">
                            <label for="payment">Payment</label>
                            <input class="formInput" type="number" id="payment" name="payment">
                    </div>

                            <button  class="formButton" type="submit" name="submit">Pay!</button>

                    </form>
                </div>

                <div class="page rightpage">
                    
                    <table>
                        <?php $sum = 0;  ?>
                            <?php foreach (getAllPay() as $bribe) :?>
                            <tr>
                                <td>
                                    <?= $bribe['name'] ?>
                                </td>
                                <td>
                                    <?=$bribe['payment'] ?>€</br>
                                </td>
                            </tr>
                                <?php   
                                      $sum += $bribe['payment'];   
                                ?>
                            <?php endforeach ?>
                            <td>
                            </td>
                            <tfoot>
                                <tr>
                                    <td>Sum</td>
                                    <?php

                                    ?>
                                    <td><?php echo $sum . " €"; ?></td>
                                </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
            <img src="image/inkpen.png" alt="an ink pen" class="inkpen"/>
        </section>
    </main>
    </body>
</html>
