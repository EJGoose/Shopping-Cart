<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart Handler</title>
</head>
<body>

    <h2>How many birds would you like to purchase?</h2>
    <!-- set up form, the action is take values strip out special characters  and use them on this page -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Quanity of Ducks: <input type="number" name = "quantity_ducks" value="0">
        <br><br>
        Quantity of Geese: <input type = "number" name = "quantity_geese" value="0">
        <br><br>
        Quantity of Pheasants: <input type = "number" name = "quantity_pheasants" value="0">
        <br><br>
        <input type = "submit" name = "submit" value = "Submit">
    </form>

    <?php
    //shopping cart calculator 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $duck_q = test_input($_POST["quantity_ducks"]);
        $geese_q = test_input($_POST["quantity_geese"]);
        $pheasant_q = test_input($_POST["quantity_pheasants"]);
        $quantities = [$duck_q, $geese_q, $pheasant_q];
        displayCheckout($quantities); 
    }

    //clean the data to make sure it is safe
    function test_input($data) {
        $data = trim($data); // remove exta whitespaces
        $data = stripslashes($data); //remove any backslashes
        $data = htmlspecialchars($data); //convert any special characters into html to prevent cross site scripting attacks
        return $data; //return the fresh and shiny data back
    }

    // Main program
    function displayCheckout($quantities){
        
        //Function to calculate total price for a product
        function calculateProductTotal($price, $quantity) {
            return $price * $quantity;
        }
    

         //if the total is more than the threshold value, calculate the discount, otherwise just return the total.
        function applyDiscount($total, $discountThreshold, $discountRate) {
            if ($total > $discountThreshold) {
                return $total * (1-$discountRate);
            }
            return $total;
        }

        // product prices 
        $products = [
            "duck" => ["price" => 0.5, "quantity" => 0],
            "goose" => ["price" => 3.0, "quantity" => 0],
            "pheasant" => ["price" => 2.5, "quantity" => 0]
        ];
        
        //shopping cart 
        $cart = [];

        //discount settings 
        $discountThreshold = 10; 
        $discountRate = 0.1; //10% discount
        $quantity_cycle = 0; // loop manager

        foreach ($products as $name => $details) {
            echo  "<br> $name (price: £" . number_format($details["price"], 2) . "): ";
            $quantity = intval($quantities[$quantity_cycle]);
            
            $products[$name]["quantity"] = $quantity;
            $cart[$name] = calculateProductTotal($details["price"], $quantity);
            $quantity_cycle += 1;
        }

        // calculate grand total 
        $grandTotal = array_sum($cart);

        //Calculate final total by calling apply discount
        $finalTotal = applyDiscount($grandTotal, $discountThreshold, $discountRate);

        //display results 
        echo "<h3>Thank you for your order</h3>" . "Your order is number: ";
        //Choose a random number between 32 and 7439
        echo rand(32, 7493);
        // display a formatted calculated total to the user 
        echo "<br>Subtotal: £" . number_format($grandTotal,2);
        //decide if the user should get a discount, update this to meet buisness needs, in this instance the discount is always applied
        if ($finalTotal < $grandTotal) {
            echo "<br>Discount applied: £" . number_format($grandTotal - $finalTotal, 2);
        }
        echo "<br>Total price: £" . number_format($finalTotal,2);

        //maths function demonstration
        echo "<h3>PHP Maths Function demonstration:</h3>";
        echo "Pi value: " . pi();
        //format the results into a standard currency format
        echo "<br>Minimum price: £" . number_format(min(array_column($products, "price")),2);
        echo "<br>Maximum price: £" . number_format(max(array_column($products, "price")),2);
        echo "<br>Rounded total: £" . round($finalTotal,1);
    };
    ?>
</body>
</html>