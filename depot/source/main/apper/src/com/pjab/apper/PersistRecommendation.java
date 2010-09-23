
package com.pjab.apper;
import java.io.BufferedReader;
import java.util.*;
import java.lang.*;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.util.Properties;
import java.sql.Connection;



public class PersistRecommendation
{



	public static void main(String[] args) throws Exception
	{
		Connection conn = DatabaseConfig.getInstance().getConnection();
		Properties prop = Utils.loadProperties("default.properties");		


		String recoFile =  prop.getProperty(ApperConstants.OUTPUT_RECO_FILE);
	    
		BufferedReader reader = new BufferedReader( new FileReader (recoFile));
		String line;
	    while( ( line = reader.readLine() ) != null ) 
		{
			int sepIndex = line.indexOf(':');
			String keyString = line.substring(0,sepIndex);
			String valueString = line.substring(sepIndex+1);
			if(valueString.isEmpty())
				continue;
			String[] values = valueString.split("\t");
	
			int appId = Integer.valueOf(keyString);
			System.out.print(appId + ":");
			List<Integer> recommended  = new ArrayList<Integer>();
			for (int i=0; i<values.length; i++)
			{
				int val = Integer.valueOf(values[i]);
				if (val != -1) {
					recommended.add(val);
					System.out.print(val + "\t");
				}
			}

			DatabaseUtils.insertAppReco(conn, appId, recommended);
			System.out.println("");
		}




	}




}

