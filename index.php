<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Paystack payment verification integration in php</title>
  </head>
  <body>
      <div class="container">
          <div class="header p-3 bg-primary m-2 text-center text-white">
            <h1>Paystack payment verification integration in php</h1>
        </div>
        <div class="alert alert-warning">
            Your log here
        </div>
        <div class="main">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter your amount">
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Fullname</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter your fullname">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="button" class="form-control btn btn-dark" id="purchasebtn" value="Make Payment">
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Paystack starts here -->
    <script src="https://js.paystack.co/v1/inline.js"></script> 
    <script>
        //Custom script goes here
        $(document).ready(function () {
		//Trigger submit when make payment button is been clicked
		$("#purchasebtn").click(function (e) { 
			e.preventDefault();
		//Submit details for payment
		var email = $("#email").val();
		var name = $("#name").val();
        var amount = $("#amount").val();
		//Check if email is empty
		if (email == "") {
			alert("Enter your email"); //display notification
			return; //i.e stop running query
		}else if (name == "") {
			alert("Enter your name"); //i.e display notification
			return; //i.e stop running query
		}else if (amount == "") {
			alert("Enter your amount"); //i.e display notification
			return; //i.e stop running query
		}
		//Run paystack query
		var handler = PaystackPop.setup({
              key: 'pk_key',
              email: email,
              amount: amount+'00',
              currency: "NGN",
              ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
              metadata: {
                 custom_fields: [
                    {
                        display_name: name,
                        variable_name: name,
                    }
                 ]
              },
              callback: function(response){
				//   if transaction successful do this

                //   alert('success. transaction ref is ' + response.reference);
                  var referenceid = response.reference;
				  //Make an http request to cart process
                    $.ajax({
                        type: "POST", //Send in POST Method
                        url: "verify.php", //Endpoint for the ajax
                        data: {
                            'checkout' : 'active', //Send checkout is active
							'name' : name, // Submit the customer name
							'email' : email, //Submit the customer email
							'refrence' : referenceid //Payment refrence id for confirmation of payment
                        },
                        beforeSend: function(){
                            console.log("Sending request");
                            $(".alert").text('Sending request');
                        },
                        success: function (response) {
							// console.log(response);
							//Check if response is valid
							if (response == "Transaction reference not found") {
                                $(".alert").text('Transaction reference not found');
							}else{
								//Once transaction completed redirect to complete page
								$(".alert").removeClass("alert-warning");
                                $(".alert").addClass('alert-success');
                                $(".alert").text(response.info);
                                console.log(response.info);
							}
                        }
                    });
              },
              onClose: function(){
                 // window.location.href="pay.php";
              }
            });
            handler.openIframe();
			
		});

	});
    </script>
  </body>
</html>
