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


	private void  createTableAppInfo(boolean override)
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
						" app_external_id VARCHAR(50), " + 
						" release_date VARCHAR(100), " + 
						" price VARCHAR(100), " + 
						" orig_link VARCHAR(200), " + 
						" img_url VARCHAR(100), " + 
						" requirements VARCHAR(100), " + 
						" genre VARCHAR(100), " + 
						" app_rating VARCHAR(50), " + 
						" screenshots VARCHAR(1000), " + 
						" language VARCHAR(200), " + 
						" created_at DATETIME, " + 
						" updated_at DATETIME, " + 
						" description VARCHAR(10000)" + 
						") engine=InnoDB;";
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


	private void createTableAppReviews( boolean override)
	{
		if(conn == null)
			return;
		String tablename = "AppReviews";
		boolean exists  = tableExists (tablename);
		if(exists && !override)
		{
			System.out.println("Table exists, returning");
		  	return;
		}
		
		if(exists)
			dropTable(tablename);

		String query = "Create TABLE " + tablename  + " ( " + 
						" id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " + 
						" app_id INT NOT NULL, " +
						" app_name VARCHAR(100) NOT NULL, " +
						" title VARCHAR(500), " +
						" review VARCHAR(20000) NOT NULL, " + 
						" reviewer VARCHAR(200), " + 
						" created_at DATETIME, " + 
						" INDEX (app_id), " + 
						" FOREIGN KEY (app_id) REFERENCES AppInfo(id) " +
						" ) engine=InnoDB;";


		System.out.println("Query: " + query);
		try {
			Statement  stmt = conn.createStatement();
			stmt.executeUpdate(query);	
		}catch (SQLException e)
		{
			 e.printStackTrace();
		}
						

		
	}


	private void createTableAppLine(boolean override)
	{
		if(conn == null)
			return;
		String tablename = "AppLine";
		boolean exists  = tableExists (tablename);
		if(exists && !override)
		{
			System.out.println("Table exists, returning");
		  	return;
		}
		
		if(exists)
			dropTable(tablename);

		String query = "Create TABLE " + tablename  + " ( " + 
						" id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " + 
						" app_id INT NOT NULL, " + 
						" app_review_id INT NOT NULL, " +
						" app_name VARCHAR(100) NOT NULL, " +
						" on_date DATETIME, " +
						" created_at DATETIME, " +
						" updated_at DATETIME, " +
						" INDEX (app_id), " + 
						" FOREIGN KEY (app_id) REFERENCES AppInfo(id), " +
						" INDEX (app_review_id), " + 
						" FOREIGN KEY (app_review_id) REFERENCES AppReviews(id) " +
						" ) engine=InnoDB;";


		System.out.println("Query: " + query);
		try {
			Statement  stmt = conn.createStatement();
			stmt.executeUpdate(query);	
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
		

		init.createTableAppInfo(true);
		init.createTableAppReviews(true);
		init.createTableAppLine(true);


		init.terminate();

	}



};
