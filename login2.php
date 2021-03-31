

<?php

session_start();

include "dbconfig.php";


// connect to the database 
$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

// default time zone set to NY
date_default_timezone_set('America/New_York');

// check to see if cookie is set 
// if set load customer data
if(!isset($_COOKIE['customer']) and !isset($_COOKIE['customer_id']))

		header("location: p2.html");



// get username from user 
if(isset($_POST["username"]) and isset($_POST["password"]))
{
	$login = mysqli_real_escape_string($con,$_POST["username"]);

// get the password form user 
	$bpassword= mysqli_real_escape_string($con,$_POST["password"]);	
}

// sql statement to get user infromation from the database table 
$sql = "SELECT * FROM CPS3740.Customers WHERE BINARY login = '$login'";

// returning the results from the table 
$result = mysqli_query($con, $sql);
$num=mysqli_num_rows($result);		
$row = mysqli_fetch_array($result);


	// check to see if user name and password match the database 
	// if they do display user infromation
if($num > 0 ) 
{
	if ($row['password'] == $bpassword)
	{
		// set cookie on sucessful login 
		setcookie('customer', $row['login'], time() + (60*50), "/" );
		setcookie('customer_id', $row['id'],time() + (60 * 50), "/");
		$_SESSION['customer_name'] = $row["name"];

		echo '<a href = "logout.php">User Logout</a>';


		checking_ip();		// ip address function
		browser_name ();	// broswer name function
		os_info();			// os info function

		// getting of info in a different way 
		$info = $_SERVER['HTTP_USER_AGENT']; 
		echo ("<br> Your browser and OS: " .$info);

		// user name
		echo("<br> Welcome Customer: " .$row["name"]);

				// user age 
				$dateOfBirth = $row["DOB"];
				$today = date("y-m-d");
				$age = date_diff(date_create($dateOfBirth), date_create($today));
				echo ("<br>Age: " .$age -> format('%y'));

				// user address
				echo ("<br>Address: " .$row['street']. ", " .$row['city']. ", " .$row['zipcode']. "\n"); 

				// display customer image
				echo '<br><img src="data:image/jpeg;base64,'.base64_encode( $row['img'] ).'"/>';

				//  reading money table for user transactions
				$id = $row['id'];
				$moneyTable = "SELECT  m.mid, m.code, s.name, m.type, m.amount, m.mydatetime, m.note
								from CPS3740_2020F.Money_patpanka m, CPS3740.Customers c, CPS3740.Sources s 
							    where m.cid = c.id AND s.id = m.sid AND c.id ='$id'";


				
				// run the query 
				$money_table_result = mysqli_query($con, $moneyTable);

				// if results == true then display the table
				if($money_table_result)
				{	
					
					// if more than 0 rows display the resulta
					if(mysqli_num_rows($money_table_result) > 0)
					{
						// finding how many rows are returned from the query 
						$numTran = mysqli_num_rows($money_table_result);
						// display table with all the transactions
						echo "<br>There are $numTran transactions for customer: " .$row["name"];
						echo "<TABLE border=1>\n";
						echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Date Time<TH>Note</TH>\n";

						$total = 0;

						while($money = mysqli_fetch_array($money_table_result) )
							{
								// must be the same as sql columbs this is case sensetive
								// if you rename the column in the sql satement you use the renamed variable

								$mid = $money["mid"];
								$code=$money["code"];
								$type = $money["type"];

									if($money["type"] == "W")
										$type = "Withdraw";
									else 
										$type = "Deposit";

									if($money["type"] == "D")
									{
									   $amount = "<font color = 'blue'>" .$money["amount"]; 
										$total += $money["amount"];
									}
									else 
									{
										$amount =  "<font color = 'red'>" ."-".$money["amount"];
										$total -= $money["amount"];
									}

								$source = $money["name"]; 

								$mydatetime = $money["mydatetime"];
								$note = $money["note"];

								if($code <>"")
									echo "<br><TR><TD>$mid<TD>$code<TD>$type<TD>$amount<TD>$source<TD>$mydatetime<TD>$note<TD>\n";
							}

							
							echo "</TABLE>\n";
							mysqli_free_result($money_table_result);
							

							// displaying the total balance in the account
							if($total < 0)
								echo ("Total Balance: "."<font color = 'red'>" .$total);
							else 
								echo("Total Balance: ". "<font color = 'blue'>".$total);


							

				
					echo "<br>";
					echo "<br>";

					
					


					}

						

					else 
						{
							echo ("<br> No Records Found");
							$total = 0;
						}
				}
				else
						echo("<br> Something wrong in query");
				
	$_SESSION['balance'] = $total;

	// display add transaction 
					 echo '<br> <a href = "add_transaction.php"><button style = "margin:10px;">Add Transaction</button></a>';
					echo ' <a href = "display_transaction.php" style = "margin:15px;">Display and Update Transactions</a>' ;
					echo '  <a href = "display_stores.php">Display Stores</a>';
				    echo '<br><br> <form action = "search.php" method="get" ><label>Keyword: </label> <input type "text" name="search" required="required"><input type="submit" value="Search">';

					
		
}
	//  check to see if the login exists and the password does not 
	else 
	{
		echo "<br> Login exists but wrong password please ";
	    echo '<a href = "p2.html">try again </a>';
	}

}
	// check to see if the login exists in database ]
else
	{ 
		echo "<br> Login '$login' doesnt exist in the database please ";
	    echo '<a href = "p2.html">try again </a>';
	}

			

function checking_ip ()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
			{ $ip = $_SERVER['HTTP_CLIENT_IP']; }
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{ $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
			else { $ip = $_SERVER['REMOTE_ADDR']; }
			
			echo "<br>Your IP: $ip\n";
			 $host = gethostbyaddr($ip);
			 $IPv4= explode(".",$ip);
			 if($IPv4[0] == "10" || ($IPv4[0] == "131" && $IPv4[1] == "125"))
			 	echo ("<br> You are on Kean domain");
			 else 
			 	echo ("<br> You are NOT on Kean domain");
}

function browser_name() 
{
		$browser = "";
		$info = $_SERVER['HTTP_USER_AGENT'];
		$browser_array = array( 
									'/msie/i' => 'Internet explorer',
									'/firefox/i' => 'Firefox',
									'/safari/i' => 'Safari',
									'/chrome/i' => 'Chrome',
									'/edge/i' => 'Edge',
									'/opera/i' => 'Opera',
									'/mobile/i' => 'Mobile browser'  
								    );

		foreach ($browser_array as $key => $testValue)
				if(preg_match($key,$info)) 
					$browser = $testValue;
					echo("<br> Browser: ".$browser);
}

function os_info()
{
	$os = "";
	$info = $_SERVER['HTTP_USER_AGENT'];
	$osArray       =   array(
								'/windows nt 6.2/i'     =>  'Windows 8',
								'/windows nt 6.1/i'     =>  'Windows 7',
								'/windows nt 6.0/i'     =>  'Windows Vista',
								'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
								'/windows nt 5.1/i'     =>  'Windows XP',
								'/windows xp/i'         =>  'Windows XP',
								'/windows nt 5.0/i'     =>  'Windows 2000',
								'/windows me/i'         =>  'Windows ME',
								'/win98/i'              =>  'Windows 98',
								'/win95/i'              =>  'Windows 95',
								'/win16/i'              =>  'Windows 3.11',
								'/macintosh|mac os x/i' =>  'Mac OS X',
								'/mac_powerpc/i'        =>  'Mac OS 9',
								'/linux/i'              =>  'Linux',
								'/ubuntu/i'             =>  'Ubuntu',
								'/iphone/i'             =>  'iPhone',
								'/ipod/i'               =>  'iPod',
								'/ipad/i'               =>  'iPad',
								'/android/i'            =>  'Android',
								'/blackberry/i'         =>  'BlackBerry',
								'/webos/i'              =>  'Mobile' );

		foreach ($osArray as $key => $testValue)
				if(preg_match($key,$info)) 
					$os = $testValue;
					echo("<br> Operating System: " .$os);
}


mysqli_close($con); 



?>