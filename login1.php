
<?php


include "dbconfig.php";

// connect to the database 
$con = mysqli_connect($host,$username,$password,$dbname)
or die("<br> Connot connect to DB: $dbname on $host" .mysqli_connect_error()); 

// default time zone set to NY
date_default_timezone_set('America/New_York');


$login= mysqli_real_escape_string($con,$_POST["username"]);
$bpassword= mysqli_real_escape_string($con,$_POST["password"]);	


// sql statement to get information from table 
$sql= "SELECT * FROM CPS3740.Customers WHERE BINARY login='$login' ";

// returning the results 
$result = mysqli_query($con, $sql);
$num=mysqli_num_rows($result);		
$row = mysqli_fetch_array($result);



// if row is  greater than 1 
if ($num > 0) 
{
		// compare user password with the password in database 
		if ( $row["password"]==$bpassword)
		{ 
				
				setcookie("userInfo", .$row["name"], time() + 86400,"/");
			
				echo '<a href = "logout.php">User Logout</a>';

				checking_ip();		// ip address
				browser_name ();	// broswer name 
				os_info();			// os info

				$info = $_SERVER['HTTP_USER_AGENT']; 
				echo ("<br> Your browser and OS: " .$info);

				// name 
				echo("<br> Welcome Customer: " .$row["name"]);

				// age 
				$dateOfBirth = $row["DOB"];
				$today = date("y-m-d");
				$age = date_diff(date_create($dateOfBirth), date_create($today));
				echo ("<br>Age: " .$age -> format('%y'));

				// addressw
				echo ("<br>Address: " .$row['street']. ", " .$row['city']. ", " .$row['zipcode']. "\n"); 

				//table 
				$id = $row['id'];
				$moneyTable = "SELECT  m.cid, m.code, m.sid, m.type, m.amount, m.mydatetime, m.note from CPS3740_2020F.Money_patpanka m, CPS3740.Customers c where m.cid = c.id AND c.id ='$id'";

				
				$money_table_result = mysqli_query($con, $moneyTable);

				if($money_table_result)
				{	
					if(mysqli_num_rows($money_table_result) > 0)
					{
						$numTran = mysqli_num_rows($money_table_result);
						echo "<br>There are $numTran transactions for customer: " .$row["name"];
						echo "<TABLE border=1>\n";
						echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Date Time<TH>Note\n";

						$total = 0;
						while($money = mysqli_fetch_array($money_table_result) )
							{
								// must be the same as sql columbs this is case sensetive
								// if you rename the column in the sql satement you use the renamed variable
								$cid = $money["cid"];
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

					
								$sid = $money["sid"];
									if($money["sid"] == 1)
										$sid = "ATM";
									else if($money["sid"] == 2)
										$sid = "Online";
									else if($money["sid"] == 3)
										$sid = "Branch";
									else if($money["sid"] == 4)
										$sid = "Wired";
									else 
										$sid = "New3";

								$mydatetime = $money["mydatetime"];
								$note = $money["note"];
								if($code <>"")
									echo "<br><TR><TD>$cid<TD>$code<TD>$type<TD>$amount<TD>$sid<TD>$mydatetime<TD>$note\n";
								
							}
				
							
							echo "</TABLE>\n";
							mysqli_free_result($money_table_result);
							
							if($total < 0)
								echo ("Total Balance: "."<font color = 'red'>" .$total);
							else 
								echo("Total Balance: ". "<font color = 'blue'>".$total);





					}

					else 
						echo ("<br> No Records Found");
				}
				else
						echo("<br> Something wrong in query");
				

											

			


		}
		else 
			echo "<br> Login exists but wrong password";
			exit();
			
}
else  
		{
			echo "Login $login doesn't exist in the database\n";
				exit();
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




