package com.pjab.apper;


import java.util.Properties;
import java.util.Map;
import java.io.IOException;

import java.sql.Connection;
import com.pjab.apper.DatabaseConfig;
import com.pjab.apper.ApperConstants;
import com.pjab.apper.DataMapping;



public class AppConfig
{
	private static final AppConfig instance = new AppConfig();
	public static AppConfig getInstance() 
	{
		return instance;
	}

	private Properties props;
	private Map<String, DataMapping> mappings;



	public void init(String[] args)
	{
		try 		
		{
			props = Utils.loadProperties("default.properties");	
			mappings = DataMapping.readJson("data/dataMappings.json");
		}catch(IOException ioe)
		{
		  	ioe.printStackTrace();
		}catch(Exception e)
		{
		  	e.printStackTrace();
		}

	}


	public DataMapping getMapping(String mappingName)
	{
		return mappings.get(mappingName);
	}

	public final Properties getProperties()
	{
	  	return props;
	}

	public String getProperty(String propName)
	{
		return props.getProperty(propName);

	}

	public void addProperty(String propKey, String propValue)
	{
		props.setProperty(propKey, propValue);
	}



}
