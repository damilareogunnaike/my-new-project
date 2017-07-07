<?php
//Request to submit contact form
if(isset($_POST['submit_form']))
{
    include("conn/connect.php");
    $data = $_POST;
    extract($data);
    $sql = "INSERT INTO contact_form (name, phone_no,message) VALUES ('{$name}','{$phone_no}','{$message}')";
    mysqli_query($sql) or die(mysqli_error());
    $status = mysqli_insert_id() > 0 ? TRUE : FALSE;
}

//include header file
include_once("header.php");
?>

<div class="main_bg"><!-- start main -->
	<div class="container">
		<div class="about details row">
                    <div class="col-md-10 col-md-offset-1 text-justify">
                        <h2 class="text-center">Contact</h2>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h3><i class="fa fa-map-marker"></i> Location</h3>
                                <hr>
                                <p class="para">
                                Evangel Model Secondary School,<br>
                                Along Toronto/Roadsafety Road,<br>
                                Uratta,Owerri,<br>
                                Imo State.<br>
                                Within Head Quarters of Owerri East District,<br>
                                Assemblies of God, Nigeria.<br>
								You can Contact us through any of these Hotlines<br>
								08033299824</br>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <h3 class="text-right">Send us a message <i class="fa fa-envelope"></i></h3>
                                <hr>
                                <?php
                                if(isset($status))
                                {
                                    ?>
                                <div class="alert alert-<?php echo ($status) ? "success" : "warning";?>">
                                    <?php echo ($status) ? "Submitted..." : "Retry..";?>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form">
                                    <form action="contact.php" method="post">
                                        <div class="form-group">
                                            <label class="para">Name</label>
                                            <input type="text" required='required' name="name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="para">Phone Number</label>
                                            <input type="tel" required="required" name="phone_no" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="para">Message</label>
                                            <textarea  required="required" name="message" class="form-control"></textarea>
                                        </div>
                                        <input type="submit" value="Submit" name="submit_form" class="btn btn-primary">
                                        <input type="submit" value="Cancel" class="btn btn-warning">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
		</div>
	</div>
</div><!-- end main -->

<?php
//include header file
include_once("footer.php");
?>
