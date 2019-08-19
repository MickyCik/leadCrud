<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$first = $last = $dob = $category = $product = "";
$first_err = $last_err = $dob_err = $category_err = $product_err = "";
 


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate firstname
    $input_first = trim($_POST["first"]);
    if(empty($input_first)){
        $first_err = "Please enter a name.";
    } elseif(!filter_var($input_first, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $first_err = "Please enter a valid name.";
    } else{
        $first = $input_first;
    }
    // Validate lastname
    $input_last = trim($_POST["last"]);
    if(empty($input_last)){
        $last_err = "Please enter a name.";
    } elseif(!filter_var($input_last, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $last_err = "Please enter a valid name.";
    } else{
        $last = $input_last;
    }
    // Validate dob
    $input_dob = trim($_POST["dob"]);
    if(empty($input_dob)){
        $dob_err = "Please enter an address.";  
     
    } else{
        $dob = $input_dob;
    }
    
    // Validate category
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please enter the salary amount.";     
    
    } else{
        $category = $input_category;
    }

    // Validate product
    $input_product = trim($_POST["product"]);
    if(empty($input_product)){
        $product_err = "Please enter the salary amount.";     
    
    } else{
        $product = $input_product;
    }
    
    // Check input errors before inserting in database
    if(empty($first_err) && empty($last_err) && empty($dob_err) && empty($category_err) && empty($product_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO dtleads (firstname, lastname, DOB, category, product) VALUES (:first, :last, :dob, :category, :product)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":first", $param_first);
            $stmt->bindParam(":last", $param_last);
            $stmt->bindParam(":dob", $param_dob);
            $stmt->bindParam(":category", $param_category);
            $stmt->bindParam(":product", $param_product);
            
            // Set parameters
            $param_first = $first;
            $param_last = $last;
            $param_dob = $dob;
            $param_category = $category;
            $param_product = $product;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="first" class="form-control" value="<?php echo $first; ?>">
                            <span class="help-block"><?php echo $first_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($last_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="last" class="form-control" value="<?php echo $last; ?>">
                            <span class="help-block"><?php echo $last_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value="<?php echo $dob; ?>">
                            <span class="help-block"><?php echo $dob_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($category_err)) ? 'has-error' : ''; ?>">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
                            <span class="help-block"><?php echo $category_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($product_err)) ? 'has-error' : ''; ?>">
                            <label>Product</label>
                            <input type="text" name="product" class="form-control" value="<?php echo $product; ?>">
                            <span class="help-block"><?php echo $product_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>