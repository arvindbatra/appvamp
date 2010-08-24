package com.pjab.apper;

import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;

public class DatabaseConfig 
{
	private static DatabaseConfig instance = null;
	private Connection conn = null;
	private final static String ROOT_USER = "vamp1";
	private final static String ROOT_PASSWD = "pa551";
	private final static String SERVER_HOST = "173.230.156.127";
	private final static String DATABASE_NAME = "ApperDB";
	
	protected DatabaseConfig() 
	{
		try {
			String url = "jdbc:mysql://" + SERVER_HOST + "/" + DATABASE_NAME + "?useUnicode=yes&characterEncoding=UTF-8";
		    Class.forName ("com.mysql.jdbc.Driver");
		    
		    conn = DriverManager.getConnection (url, ROOT_USER, ROOT_PASSWD);
		    System.out.println("Database connection established");
	  } catch (Exception e)
	  {

		System.err.println("Cannot connect to database " + e.getMessage());
		e.printStackTrace();
	  }
	}
	
	public static DatabaseConfig getInstance() 
	{
		if(instance == null) {
			instance = new DatabaseConfig();
		}
		return instance;
	}
	
	public Connection getConnection()
	{
		return conn;
	}
	
	
	
	public void terminateConnection()
	{
		if(conn != null)
		{
			try {
			  	conn.close();
				System.out.println("Database connection terminated");
			}
			catch (Exception e) {}
		}
	}


}
