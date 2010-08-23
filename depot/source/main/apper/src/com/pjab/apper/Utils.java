package com.pjab.apper;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
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
	public static void printToFile(String filename, String data)
	{
		try {
			System.out.println("Writing app to file " + filename);
			FileWriter fstream = new FileWriter( filename);
			BufferedWriter out = new BufferedWriter(fstream);
			out.write(data);
			out.close();
			fstream.close();
		} catch(IOException ioe)
		{
			
			System.err.println("IOException while writing file " + filename);
			ioe.printStackTrace(System.err);
			
			System.exit(-5);
		}
		
		
	}
	
};
