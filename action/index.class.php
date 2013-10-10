<?php
	 class indexAction extends action{
		function __construct(){
			assign("mysite",MY_SITE);
			assign("public",PUBLICS);
			
		}
		
		function index(){					
			display("index.html");			
		}
		
		
	 }
