<?php

session_start();
$customer_name = $_SESSION["customer_name"];

include "dbconfig.php";

$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

echo '<a href = "logout.php">User Logout</a>';

$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

// default time zone set to NY
date_default_timezone_set('America/New_York');


if(!isset($_COOKIE['customer']) and !isset($_COOKIE['customer_id']))
	header("location: p2.html");


if(isset($_GET["search"]))
{
	$keyword = $_GET["search"];
	$id = $_COOKIE['customer_id'];
}
else 
	echo "No Keyword Entered";




	$search = "SELECT  m.mid, m.code, s.name, m.type, m.amount, m.mydatetime, m.note
				from CPS3740_2020F.Money_patpanka m, CPS3740.Customers c, CPS3740.Sources s 
				where m.cid = c.id AND s.id = m.sid AND c.id ='$id' AND m.note LIKE concat('%','$keyword','%')";

	$moneyTable = "SELECT  m.mid, m.code, s.name, m.type, m.amount, m.mydatetime, m.note
					from CPS3740_2020F.Money_patpanka m, CPS3740.Customers c, CPS3740.Sources s 
					 where m.cid = c.id AND s.id = m.sid AND c.id ='$id'";

	$search_result = mysqli_query($con, $search);
	$money_table_result = mysqli_query($con, $moneyTable);


if ($keyword == "*")
{

				// if results == true then display the table
				if($money_table_result)
				{	
			
					// if more than 0 rows display the resulta
					if(mysqli_num_rows($money_table_result) > 0)
					{
						$numTran = mysqli_num_rows($money_table_result);
						echo "<br>There are $numTran transactions for customer: <b>$customer_name </b>";
						echo "<TABLE border=1>\n";
						echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Date Time<TH>Note<TH>\n";
						$total =0; 
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
								if($total < 0)
								echo ("Total Balance: "."<font color = 'red'>" .$total);
							else 
								echo("Total Balance: ". "<font color = 'blue'>".$total);
					}

				}			

}	

else
{

				if($search_result)
				{

					if(mysqli_num_rows($search_result) > 0)
					{

						$numTran = mysqli_num_rows($search_result);
						echo "<br>There are $numTran transactions for customer: <b>$customer_name </b>";
						echo "<TABLE border=1>\n";
						echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Date Time<TH>Note<TH>\n";
					
							$total=0;
							while($result = mysqli_fetch_array($search_result) )
												{
												// must be the same as sql columbs this is case sensetive
												// if you rename the column in the sql satement you use the renamed variable
												$mid = $result["mid"];
												$code=$result["code"];
												$type = $result["type"];

													if($result["type"] == "W")
														$type = "Withdraw";
													else 
														$type = "Deposit";

													if($result["type"] == "D")
													{
													   $amount = "<font color = 'blue'>" .$result["amount"]; 
														$total += $result["amount"];
													}
													else 
													{
														$amount =  "<font color = 'red'>" ."-".$result["amount"];
														$total -= $result["amount"];
													}

												$source = $result["name"]; 

												$mydatetime = $result["mydatetime"];
												$note = $result["note"];

												if($code <>"")
													echo "<br><TR><TD>$mid<TD>$code<TD>$type<TD>$amount<TD>$source<TD>$mydatetime<TD>$note<TD>\n";
											}

											
											echo "</TABLE>\n";
											mysqli_free_result($search_result);

							if($total < 0)
								echo ("Total Balance: "."<font color = 'red'>" .$total);
							else 
								echo("Total Balance: ". "<font color = 'blue'>".$total);



					}
					else 
						echo (" <br> No record found with the search keyword: $keyword ");

				}
	
					else
			
						 echo ("<br> Something wrong in query");
}

			mysqli_close($con);

?>