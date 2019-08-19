<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$first = $last = $dob = $category = $product = "";
$first_err = $last_err = $dob_err = $category_err = $product_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
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
        // Prepare an update statement
        $sql = "UPDATE dtleads SET firstname=:first, lastname=:last, DOB=:dob, category=:category, product=:product WHERE id=:id";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":first", $param_first);
            $stmt->bindParam(":last", $param_last);
            $stmt->bindParam(":dob", $param_dob);
            $stmt->bindParam(":category", $param_category);
            $stmt->bindParam(":product", $param_product);
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_first = $first;
            $param_last = $last;
            $param_dob = $dob;
            $param_category = $category;
            $param_product = $product;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM dtleads WHERE id = :id";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    // Retrieve individual field value
                    $first = $row["firstname"];
                    $last = $row["lastname"];
                    $dob = $row["DOB"];
                    $category = $row["category"];
                    $product = $row["product"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
        
        // Close connection
        unset($pdo);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($first_err)) ? 'has-error' : ''; ?>">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>