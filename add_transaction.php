<?php

session_start();

include "dbconfig.php";
$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

echo '<a href = "logout.php">User Logout</a>';


if(!isset($_COOKIE['customer']) and !isset($_COOKIE['customer_id']))
	header("location: p2.html");


$sql = "SELECT s.id, s.name FROM CPS3740.Sources s";
$result = mysqli_query($con, $sql);






$customer_name = $_SESSION['customer_name'];
$balance = $_SESSION['balance']; 
echo "<h1> Add Transaction</h1>";
echo "<b>$customer_name</b> current balance is <b>$balance</b>";

echo '<form action = "insert_transaction.php" method = "post">';

echo '<br><label> Transaction Code: </label> ';
echo '<input type = "text" name="tranCode" required="required">';

echo '<br> <input type = "radio"  name = "tran_selection"  value = "D" required>Deposit ';
echo '<input type = "radio" name = "tran_selection" value = "W" > Withdraw';

echo '<br><label> Amount: </label> ';
echo '<input type = "number" min = 1 name="amount"required="required">';

echo '<br><label> Select a Source </label>'; 
echo '<select name="source" id = "source" required>
		<option value = ""></option>';

if($result > 0)
{

	
		while($source = mysqli_fetch_array($result))
		{
	
				
			   echo '<option value = "' .$source["id"]. '">'.$source["name"].'</option>';

		}
}

	
		echo '</select>';
		mysqli_free_result($result);



echo '<br> <label> Note: </label>';
echo ' <input type= "text" name = "note">';
echo '<br><input type="submit" value="Submit">';
echo '</form>';




 
mysqli_close($con);

?>