package com.pjab.apper.init;

import java.sql.*;
import java.io.PrintWriter;


class InitializeDatabase
{
  	private final static String ROOT_USER = "vamp1";
	private final static String ROOT_PASSWD = "pa551";
	
	private Connection conn = null;




	public void connect(String userName, String password)
	{
	  try {
	    	PrintWriter pw = new PrintWriter(System.out);
		String url = "jdbc:mysql://localhost/ApperDB?useUnicode=yes&characterEncoding=UTF-8";
		    Class.forName ("com.mysql.jdbc.Driver");
		    DriverManager.setLogWriter(pw);
		    System.out.println(password);
		    conn = DriverManager.getConnection (url, userName, password);
		    System.out.println("Database connection established");
	  } catch (Exception e)
	  {

		System.err.println("Cannot connect to database " + e.getMessage());
		e.printStackTrace();
	  }

	}

	boolean tableExists (String tablename)
	{
		try{
			Statement  stmt = conn.createStatement();
			String query = "select * from " + tablename + " ;"; 
			stmt.executeQuery(query);
		}catch(SQLException sqe)
		{
			System.out.println("sql exception recieved for table " + tablename + " . means it does not exists");
			return false;
		}

		return true;
	}

	private void dropTable(String tablename)
	{
		try{
			Statement  stmt = conn.createStatement();
			String query = "drop table " + tablename + " ;"; 
			System.out.println("Dropping table " + tablename);
			stmt.executeUpdate(query);
		}catch(SQLException sqe)
		{
			System.out.println("sql exception recieved for table " + tablename + " . means it does not exists");
		}



	}


	private void  createTables(boolean override)
	{
		if(conn == null)
			return;
		String tablename = "AppInfo";
		boolean exists  = tableExists (tablename);
		if(exists && !override)
		{
			System.out.println("Table exists, returning");
		  	return;
		}
		
		if(exists)
			dropTable(tablename);
		String query = "Create table " + tablename + " ( " + 
						" id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " + 
						" app_name VARCHAR(100) NOT NULL, " + 
						" seller VARCHAR(100), " + 
						" app_external_id VARCHAR(20), " + 
						" release_date VARCHAR(50), " + 
						" price VARCHAR(20), " + 
						" orig_link VARCHAR(100), " + 
						" img_url VARCHAR(100), " + 
						" requirements VARCHAR(100), " + 
						" genre VARCHAR(20), " + 
						" app_rating VARCHAR(20), " + 
						" screenshots VARCHAR(1000), " + 
						" language VARCHAR(20), " + 
						" description VARCHAR(10000)" + 
						");";
		System.out.println("Query: " + query);


		String indexQuery1 = "CREATE INDEX idx_app_name " + 
						"ON " + tablename + " (app_name);" ;
		String indexQuery2 = "CREATE INDEX idx_app_external_id " + 
						"ON " + tablename + " (app_external_id);" ;
		String indexQuery3 = "CREATE INDEX idx_app_genre " + 
						"ON " + tablename + " (genre);" ;

		try {
			Statement  stmt = conn.createStatement();
			stmt.executeUpdate(query);	
			stmt.executeUpdate(indexQuery1);	
			stmt.executeUpdate(indexQuery2);	
			stmt.executeUpdate(indexQuery3);	
		}catch (SQLException e)
		{
			 e.printStackTrace();
		}
	


	}






	public void terminate()
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


	
	public static void main(String[] args) throws Exception
	{

		InitializeDatabase init = new InitializeDatabase();
		init.connect(ROOT_USER, ROOT_PASSWD);
		

		init.createTables(true);


		init.terminate();

	}



};
