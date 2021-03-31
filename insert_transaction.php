<?php

session_start();

// $balance = $_SESSION['balance'];

 
date_default_timezone_set('America/New_York');

include "dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

echo '<a href = "logout.php">User Logout</a>';

$datetime = date_create()->format('Y-m-d H:i:s');


if(!isset($_COOKIE['customer']) and !isset($_COOKIE['customer_id']))
	header("location: p2.html");

$customer_id = $_COOKIE["customer_id"];

if(isset($_POST["tranCode"]))
	$tranCode = $_POST["tranCode"];


if(isset($_POST["tran_selection"]))
	$tranSelection = $_POST["tran_selection"];


if(isset($_POST["amount"]))
	$amount = $_POST["amount"];


if(isset($_POST["source"]))
	$sourceId = $_POST["source"];


 if(isset($_POST["note"]))
 	$notes = $_POST["note"];

$code = "SELECT m.code from CPS3740_2020F.Money_patpanka m  where m.code like '$tranCode' and m.cid = '$customer_id'"; 
$code_result = mysqli_query($con,$code);
$result = mysqli_fetch_array($code_result);

$money ="SELECT m.type,m.amount from CPS3740_2020F.Money_patpanka m where m.cid = '$customer_id' ";
$money_result = mysqli_query($con,$money);


if($money_result)
{
	if(mysqli_num_rows($money_result)>0)
	{
				$total = 0; 
				while($row = mysqli_fetch_array($money_result))
				{
								

									if($row["type"] == "D")
									{
									
										$total += $row["amount"];
									}
									else 
									{
										
										$total -= $row["amount"];
									}
				}
	}
	else
	{
			echo"<br>There are no records";
					$total = 0;
	}


}
else 
	echo"something wrong in query";





if($result["code"] == $tranCode)
{
	echo "This code already exists please try another one";
}
else
{

		if($tranSelection == 'W' and $total < $amount)
		{
			echo "<br>The withdrawl amount is larger than the balance. Transaction cannot be done"; 
		}
		else 
		{
			$sqlInsert = "INSERT INTO CPS3740_2020F.Money_patpanka (code,cid,sid,type,amount,mydatetime,note) 
							VALUES ('$tranCode', '$customer_id', '$sourceId', '$tranSelection' , '$amount', '$datetime', '$notes')";

						if(mysqli_query($con,$sqlInsert))
						{
							echo "<br>Successfully added the transaction";

						
						}
						else 
							echo "something wrong in query";

						if($tranSelection == "D")
							$total += $amount;
						else 
							$total -= $amount;



		}
}

	echo "<br> New balance: $" .$total;








			






  mysqli_close($con);




?>