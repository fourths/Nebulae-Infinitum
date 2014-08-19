<?php
//Get creation ID from URL
//If creation ID not found or is NaN, die
if ( isset( $_GET["id"] ) ) {
 $creationid = htmlspecialchars( $_GET["id"] );
}
else die( "" );
if ( !$creationid || strcspn( $creationid, "0123456789" ) > 0 ){
	die( "" );
}

//Get creation info from database
$result = $mysqli->query( "SELECT * FROM creations WHERE id = $creationid" );
if ( !$result ) {
    die( "" );
}
$creation = $result->fetch_array;

//If creation ID is not a valid creation, die
if ( !$creation ){
	die( "" );
}

//Get creation ratings from database, calculate the average rating, and output it
$result = $mysqli->query( "SELECT rating FROM ratings WHERE creationid=$creation['id']" );
$i = 0;
while( $row = $result->fetch_array ) {
	$ratings[$i] = $row[0];
	$i++;
}
if ( empty( $ratings[0] ) ) {
	$ratings[0] = 0;
}

if ( number_format( array_sum( $ratings ) / count( $ratings ), 1) == 0.0 ) {
	echo "unrated";
}
else {
	echo number_format( array_sum( $ratings ) / count( $ratings ), 1);
}

?>