package com.pjab.apper;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;



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
	
	
	private static final Pattern p = Pattern.compile("\\\\u([0-9A-F]{4})");
	public static String U2U(String s) {
		int i=0,len=s.length(); char c; StringBuffer sb = new StringBuffer(len);
		while (i<len) {
		c = s.charAt(i++);
		if (c=='\\') {
		if (i<len) {
		c = s.charAt(i++);
		if (c=='u') {
		c = (char) Integer.parseInt(s.substring(i,i+4),16);
		i += 4;
		} // add other cases here as desired...
		}} // fall through: \ escapes itself, quotes any character but u
		sb.append(c);
		}
		return sb.toString();
	}
	
	public static String RemoveTroublesomeCharacters(String inString)
	{
	    if (inString == null) return null;

	    StringBuilder newString = new StringBuilder();
	    char ch;
	    boolean prevWhitespace = false;
	    for (int i = 0; i < inString.length(); i++)
	    {

	        ch = inString.charAt(i);
	        // remove any characters outside the valid UTF-8 range as well as all control characters
	        // except tabs and new lines
	        if(Character.isISOControl(ch))
	        	continue;
	        if ((ch < 0x00FD && ch > 0x001F) || ch == '\t' || ch == '\n' || ch == '\r')
	        {
	            newString.append(ch);
	        }
	        
	    }
	    return newString.toString();

	}
	
	public static String removeMultipleWhiteSpaces(String inString)
	{
		StringBuilder newString = new StringBuilder();
	    char ch;
	    boolean prevWhitespace = false;
	    for (int i = 0; i < inString.length(); i++)
	    {

	        ch = inString.charAt(i);
	        if(Character.isWhitespace(ch))
	        {
	        	
	        	if(prevWhitespace)
	        		continue;
	        
	        	prevWhitespace = true;
	        }
	        else
	        	prevWhitespace = false;
	        newString.append(ch);
	    }
	    return newString.toString();
	}
	

	
};
