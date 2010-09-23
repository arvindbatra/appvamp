
package com.pjab.apper;
import java.util.Properties;


import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.File;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.*;
import java.lang.*;
import java.util.ArrayList;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import com.pjab.apper.*;


public class AppRecommender
{

	List<AppCatInfo> apps = new ArrayList<AppCatInfo>();
	public class PostingList extends HashSet<Integer>
	{

	}


	Map<Integer, PostingList  > idToAppIndexMap;

	Map<Integer, String> idNameMap;


	public AppRecommender()
	{
		idToAppIndexMap = new HashMap<Integer, PostingList > ();
		idNameMap = new HashMap<Integer, String>();

	}

	void readApps(String dirName)
	{
	  	File catDir = new File(dirName);
        File [] files = catDir.listFiles();
		for( File f: files)
		{
			try {
				String jsonContent = Utils.readFile(f.getPath());
				JsonParser jparser  = new JsonParser();
				JsonObject jobj = (jparser.parse(jsonContent)).getAsJsonObject();
		
				AppCatInfo app = AppCatInfo.parseAppCatInfo(jobj);
				if(app != null)
				{
					apps.add(app);
				}
			}
			catch(Exception e)
			{

				System.out.println(e.getMessage());
			}
		}

	}


	void createIndex()
	{
		for(int i=0; i<apps.size(); i++)
		{
			AppCatInfo app = apps.get(i);
			
/*			//look categories
			for(int ci = 0; ci<app.categories.size(); ci++)
			{
				CatInfo info = app.categories.get(ci);
				int id = info.id;
				if(!idNameMap.containsKey(id))
					idNameMap.put(id, info.name);

				if(!idToAppIndexMap.containsKey(id))
					idToAppIndexMap.put(id ,  this.new PostingList());

				PostingList plist = idToAppIndexMap.get(id);
				plist.add(i);

			}
*/			
			//look mentions
			for(int ci = 0; ci<app.mentionList.size(); ci++)
			{
				CatInfo info = app.mentionList.get(ci);

				int id = info.id;
				if(!idNameMap.containsKey(id))
					idNameMap.put(id, info.name);

				if(!idToAppIndexMap.containsKey(id))
					idToAppIndexMap.put(id ,  this.new PostingList());

				PostingList plist = idToAppIndexMap.get(id);
				plist.add(i);

			}

		}

	}


	void printIndex()
	{
		for(Map.Entry<Integer, PostingList> entry : idToAppIndexMap.entrySet())
		{
			StringBuffer buffer = new StringBuffer();
			int id = entry.getKey();
			String name = idNameMap.get(id);
			PostingList list = entry.getValue();

			Iterator iter = list.iterator();
			buffer.append(id + "(" + name + "):");
			while(iter.hasNext())
			{
				int index = (Integer)iter.next();
				AppCatInfo aci = apps.get(index);
				buffer.append(aci.appName + "\t");
			}
			System.out.println(buffer.toString());
		}


	}


	public PostingList getCandidate(AppCatInfo aci)
	{
		PostingList list = new PostingList();


		return list;
	}







	public static void main(String [] args) throws Exception
	{
		Properties prop = Utils.loadProperties("default.properties");		
		String catFile = "app_cat/Kubb";
		
		AppRecommender reco = new AppRecommender();
		String catDir = prop.getProperty(ApperConstants.OUTPUT_SMALL_CAT_DIR);
		reco.readApps(catDir);
		reco.createIndex();
		reco.printIndex();



	}








	










};
