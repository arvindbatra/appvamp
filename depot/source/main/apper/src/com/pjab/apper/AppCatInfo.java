
package com.pjab.apper;
import java.util.Properties;


import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.File;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.ArrayList;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import com.pjab.apper.*;

public class AppCatInfo
{

	
	String appName;
	public List<CatInfo> categories = new ArrayList<CatInfo>();
	public List<CatInfo> mentionList = new ArrayList<CatInfo>();
	String seller;
	String genre;



	public static AppCatInfo parseAppCatInfo(JsonObject obj)
	{
		AppCatInfo appCatInfo = new AppCatInfo();

		if(obj.has("name"))
			appCatInfo.appName = obj.get("name").getAsString();
		
		System.out.println("Proceesing app: " +  appCatInfo.appName);
		if(appCatInfo.appName.isEmpty())
			return null;

		if(obj.has("seller"))
			appCatInfo.seller = obj.get("seller").getAsString();

		if(obj.has("genre"))
			appCatInfo.genre = obj.get("genre").getAsString();


		if(obj.has("categories"))
		{
			JsonArray categories = obj.get("categories").getAsJsonArray();
			for(int i=0; i<categories.size(); i++)
			{
				JsonObject c = categories.get(i).getAsJsonObject();
				CatInfo cInfo = new CatInfo();
				if(c.has("name"))
					cInfo.name = c.get("name").getAsString();
				if(c.has("id"))
					cInfo.id = c.get("id").getAsInt();
				if(c.has("score"))
					cInfo.score = c.get("score").getAsFloat();


				//System.out.println(cInfo.toString());
				
				appCatInfo.categories.add(cInfo);
			}
		}
		
		if(obj.has("mentions"))
		{
			JsonArray mentions = obj.get("mentions").getAsJsonArray();
			for(int i=0; i<mentions.size(); i++)
			{
				JsonObject c = mentions.get(i).getAsJsonObject();
				CatInfo cInfo = new CatInfo();
				if(c.has("EntityName"))
					cInfo.name = c.get("EntityName").getAsString();
				if(c.has("EntityID"))
					cInfo.id = c.get("EntityID").getAsInt();
				if(c.has("EntityScore"))
					cInfo.score = c.get("EntityScore").getAsFloat();
				
				if(c.has("EntityClassName"))
					cInfo.ecName = c.get("EntityClassName").getAsString();

				if(c.has("EntityClassID"))
				{
					String ecId = c.get("EntityClassID").getAsString();
					if(!ecId.isEmpty())
						cInfo.ecId = Integer.valueOf(ecId);
				}
				//System.out.println(cInfo.toString());
				
				appCatInfo.mentionList.add(cInfo);
			}
		}


	return appCatInfo;




	}

















}
