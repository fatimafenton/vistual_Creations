( function( $ )
{
	"use strict";
	wp.customize( 'blogname', function( value )
	{
		value.bind( function( to )
		{
			$( '.site-title a' ).html( to );
		} );
	} );
	
	
	wp.customize( 'blogdescription', function( value )
	{
		value.bind( function( to )
		{
			$( '.site-description' ).html( to );
		} );
	} );


 	wp.customize( 'main_heading_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + to + '">' );
			
			$( 'h1,h2,h3,h4,h5,h6').css( 'font-family', '"' + to + '"' );
		} );
	} );
	
	wp.customize( 'body_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + to + '">' );
			
			$( 'body').css( 'font-family', '"' + to + '"' );
		} );
	} );
	
	
} )( jQuery );