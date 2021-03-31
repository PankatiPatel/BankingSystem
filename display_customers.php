<?php 



include "dbconfig.php";

$con = mysqli_connect($host,$username,$password,$dbname)
or die("<br> Connot connect to DB: $dbname on $host\n"); 

$sql = "SELECT ID,Login,Password,Name,Gender,DOB,Street,City,State,Zipcode FROM CPS3740.Customers";

$result = mysqli_query($con, $sql); 

if($result) 
{	// checks to see if there are rows in the table
	if(mysqli_num_rows($result)>0)
	{
		echo "<br>The following customers are in the bank system:";
		echo "<TABLE border=1>\n";
		echo "<TR><TH>ID<TH>Login<TH>Password<TH>Name<TH>Gender<TH>DOB<TH>Street<TH>City<TH>State<TH>Zipcode\n";
	
		// loop to print the rows 
		while($row = mysqli_fetch_array($result))
		{
			// must be the same as sql columbs this is case sensetive
			// if you rename the column in the sql satement you use the renamed variable
			$ID = $row["ID"];
			$Login=$row["Login"];
			$Password=$row["Password"];
			$Name=$row["Name"];
			$Gender = $row["Gender"];
			$DOB=$row["DOB"];
			$Street=$row["Street"];
			$City=$row["City"];
			$State=$row["State"];
			$Zipcode=$row["Zipcode"];

			// if fname is empty done display record
			if ($ID <>"")
				echo "<br><TR><TD>$ID<TD>$Login<TD>$Password<TD>$Name<TD>$Gender<TD>$DOB<TD>$Street<TD>$City<TD>$State<TD>$Zipcode\n";
			

		}

		echo "</TABLE>\n";
		//release everything from memory, this is null
		mysqli_free_result($result);

	}	
	else 
	{
		echo "<br> No record found. \n";
	}
}
else 
{
	echo "<br> Something wrong in the query. $sql\n";
}



// close connection 
mysqli_close($con);

?>


