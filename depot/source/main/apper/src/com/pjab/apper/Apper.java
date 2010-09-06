package com.pjab.apper;


import java.util.Properties;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import java.sql.Connection;
import com.pjab.apper.DatabaseConfig;
import com.pjab.apper.ApperConstants;
import com.pjab.apper.AppConfig;
import com.pjab.apper.AppFetcher;



public class Apper
{
	private Connection m_conn;



	public Apper()
	{
		m_conn = DatabaseConfig.getInstance().getConnection();

	}


	

	public String processQuery (final Map<String, String> qpacket)
	{
		String action = qpacket.get(ApperConstants.APPER_ACTION);	

		if(action == null || action.length() == 0)
		{
			System.out.println("no action specified, returning ");
			return "ERROR";
		}

		if(action.compareTo("fetch_app") == 0)
		{
			return fetchApp(qpacket);
		}


		System.out.println("Unknown action specified, returning ");
		return "ERROR";
	}
	
	
	public String fetchApp (final Map<String, String> qpacket)
	{
		String appUrl = qpacket.get(ApperConstants.APP_URL);

		if(appUrl == null || appUrl.length() == 0)
		{
			System.out.println("No url specified, returning");	  	
			return "";
		}	
		System.out.println("Fetching app " + appUrl);

		AppFetcher fetcher = new AppFetcher();
		boolean success = fetcher.fetchApp(appUrl);

		return success ?"true":"false";

		
				

	}





};











