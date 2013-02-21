<?php
/* CB's "Get my todo list" script
 * 
 * Purpose: run as a cron job, and every minute update the local
 * ~/todo.txt with items found in the mytinytodo mysql database
 * on the raspberry pi
 * 
 * Then, we can take that file and have conky show us its contents
 * 
 * - CB 2-20-2013
	
NOTE: I'm a totally beginner coder, so forgive my errors...!
*/

include 'config.php';			// get the settings from the config file

// OPEN UP A FILE
$fh = fopen($file, 'w') or die("can't open file");

// CONNECT TO THE DATABASE
$con = mysql_connect($DBurl,$DBuser,$DBpass);
if (!$con)
  {
  die("<div id = 'debug'>Oops, could not connect:"  . mysql_error()) . "</div>";        // error m$
  }
  
// Choose the database
        mysql_select_db($DBdb, $con);
      

// THE QUERY
// this query looks for todo items in list id 1 that are not completed
$result = mysql_query("
        SELECT title 
        FROM mtt_todolist 
        WHERE list_id = '1' 
        AND compl = '0'
			");

// tell us what you found, yo
while($row = mysql_fetch_array($result))
        {

		$title = $row['title'];

		// print to stdout
		echo $title;
		print "\n";			// for CLI usage, etc.
		
		// print to file		// for writing to a file
		fwrite($fh, $title);
		fwrite($fh, "\n");

	}

// when you're done, close the file
fclose($fh);

// the end
?>
