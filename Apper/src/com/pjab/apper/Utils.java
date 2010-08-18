package com.pjab.apper;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class Utils {

	
	public static String readFile( String file ) throws IOException {
	    BufferedReader reader = new BufferedReader( new FileReader (file));
	    String line  = null;
	    StringBuilder stringBuilder = new StringBuilder();
	    String ls = System.getProperty("line.separator");
	    while( ( line = reader.readLine() ) != null ) {
	        stringBuilder.append( line );
	        stringBuilder.append( ls );
	    }
	    return stringBuilder.toString();
	    
	
	}

	
};
