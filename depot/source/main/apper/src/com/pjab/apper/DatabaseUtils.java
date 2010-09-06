package com.pjab.apper;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.ResultSet;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Date;
import java.text.SimpleDateFormat;



public class DatabaseUtils 
{
	
	public static boolean insertAppInfoIfNotExists(Connection conn, AppData appData)
	{
		if (conn == null)
			return false;
		
		List<AppData> children = appData.children;
		if(children == null)
		{
			System.out.println("children are null");
			return false;
		}
		
	  Map<String,String> map = new HashMap<String, String>();
		for(int i= 0; i<children.size(); i++)
		{
			AppData child = children.get(i);
			if(child.children == null)
			{
				map.put(child.fieldName, child.fieldValue);
				//System.out.println(child.fieldName + "\t" +  child.fieldValue);;
			}
			else
			{
				map.put(child.fieldName, child.children.toString());
			}
			
		}
		String query = "select * from AppInfo where  app_name=\'" + map.get("title") + "\';";
		System.out.println(query);
		try {
			PreparedStatement stmt = conn.prepareStatement("select * from AppInfo where app_name = ? ;"); 
			stmt.setString(1,map.get("title"));	
			ResultSet rs = stmt.executeQuery(query);
			if(rs.next())
			{
			  	//found a result
				rs.close();
				System.out.println("App " + map.get("title") + " was found in db, not writing again");
				return true;
			}
			else
			{
				rs.close();
				return insertAppInfo(conn, appData);
			}
		}
		catch( SQLException e)
		{
			 e.printStackTrace();
			 return false;

		}


	}



	public static  boolean insertAppInfo(Connection conn, AppData appData)
	{
		if (conn == null)
			return false;
		boolean success = false;
		List<AppData> children = appData.children;
		if(children == null)
		{
			System.out.println("children are null");
			return false;
		}
		Map<String,String> map = new HashMap<String, String>();
		for(int i= 0; i<children.size(); i++)
		{
			AppData child = children.get(i);
			if(child.children == null)
			{
				map.put(child.fieldName, child.fieldValue);
				//System.out.println(child.fieldName + "\t" +  child.fieldValue);;
			}
			else
			{
				map.put(child.fieldName, child.children.toString());
			}
			
		}
		
		String appName = map.get("title");
		String seller = map.get("seller");
		//TODO 	String app_exernal_id =- " app_external_id VARCHAR(20), " +
		String releaseDate = map.get("release_date");
		String price = map.get("price");
		String originalLink = map.get("link");
		String imageURL = map.get("img_url");
		String requirements = map.get("requirements");
		String genre = map.get("genre");
		String appRating = map.get("app_rating");
		String screenshots = map.get("screenshots");
		String language = map.get("language");
		String description = map.get("description");
		
		
		
		String query = "Insert into AppInfo(app_name, seller, release_date, price, orig_link, img_url, requirements, genre, app_rating, screenshots, language, description)"
				+ "Values ( " + appName + "," + seller + "," + releaseDate + "," + price + "," + originalLink + "," + imageURL + "," + requirements + "," + genre + 
				"," + appRating + "," + screenshots + "," + language + "," + 
				//description + 
				");";
				
		
		System.out.println("query:" + query);
		java.util.Date dt = new java.util.Date();
		java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		String currentTime = sdf.format(dt);
		try {
			PreparedStatement stmt = conn.prepareStatement("Insert into AppInfo(app_name, seller, release_date, price, orig_link, img_url, requirements, genre, app_rating, screenshots, language, description, created_at, updated_at)  " 
										+ "values (?, ?, ?, ? , ?, ?, ? , ?, ?, ?, ?, ?,?,?)");
			
			stmt.setString(1, appName);
			stmt.setString(2, seller);
			stmt.setString(3, releaseDate);
			stmt.setString(4, price);
			stmt.setString(5, originalLink);
			stmt.setString(6, imageURL);
			stmt.setString(7, requirements);
			stmt.setString(8, genre);
			stmt.setString(9, appRating);
			stmt.setString(10, screenshots);
			stmt.setString(11, language);
			stmt.setString(12, description);
			stmt.setString(13, currentTime);
			stmt.setString(14, currentTime);
			
			
			stmt.executeUpdate();
			success = true;
		}catch (SQLException e)
		{
			 e.printStackTrace();
			 success  = false;
		}
		
		
		
		
		
		
		return success;
		
	}
	
	

}
