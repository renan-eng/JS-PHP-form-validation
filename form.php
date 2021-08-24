<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="styles.css">

        <script src="jquery.js"></script>
        <script src="validate.js"></script>

        <title>Form Validation</title> 

        <script>
            $(document).ready(function(){
                $.validator.addMethod( "phoneUS", function( phone_number, element ) {
                    phone_number = phone_number.replace( /\s+/g, "" );
                    return this.optional( element ) || phone_number.length > 9 &&
                        phone_number.match( /^(\+?1-?)?(\([2-9]([02-9]\d|1[02-9])\)|[2-9]([02-9]\d|1[02-9]))-?[2-9]\d{2}-?\d{4}$/ );
                }, "Please specify a valid phone number" );
                $("#form").validate();
            });
        </script>
    </head>
    <body>
        <form action="contact.php" method="post" id="form">
            <fieldset>

                <label for="name">Name: <em>*</em></label>
                <input type="text" name="name" id="name" class="required" value="<?php echo $form['name'];?>"> <?php echo $error['name']?>
                
                <label for="phone">Phone(000-000-0000): <em>*</em></label>
                <input type="phone" name="phone" id="phone" class="required" value="<?php echo $form['phone'];?>"> <?php echo $error['phone']?>

                <label>Fax(000-000-0000):</label>
                <input type="phone" name="fax" id="fax" value="<?php echo $form['fax'];?>"> 

                <label>Email: <em>*</em></label>
                <input type="email" name="email" id="email" class="required email" value="<?php echo $form['email'];?>"><?php echo $error['email']?>

                <label>Comments:</label>
                <textarea type="text" name="comments" id="comments"><?php echo $error['comments'];?></textarea>
                
                <p class="required_msg">* required fields</p>

                <input type="submit" name="submit" id="submit">

            </fieldset>
        </form>
    </body>
</html>