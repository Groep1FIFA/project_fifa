'use strict';

$( ".cross" ).hide();
$( ".team-players" ).hide();
$( ".team-name" ).click(function() {
$( ".team-players" ).slideToggle( "slow", function() {
$( ".team-name" ).hide();
$( ".cross" ).show();
});
});

$( ".cross" ).click(function() {
$( ".team-players" ).slideToggle( "slow", function() {
$( ".cross" ).hide();
$( ".team-name" ).show();
});
});