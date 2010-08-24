package com.pjab.apper;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;



public class DataMapping 
{
	public enum DataType 
	{
		DATA_TYPE_STRING,	//this is also the default value
		DATA_TYPE_BOOLEAN,
		DATA_TYPE_VECTOR,
		DATA_TYPE_INTEGER
		
	};
	
	public static DataType getDataType(String typeString)
	{
		if(typeString.compareToIgnoreCase("boolean") == 0)
				return DataType.DATA_TYPE_BOOLEAN;
		else if (typeString.compareToIgnoreCase("vector") == 0)
			return DataType.DATA_TYPE_VECTOR;
		else if (typeString.compareToIgnoreCase("integer") == 0)
			return DataType.DATA_TYPE_INTEGER;
		
		return DataType.DATA_TYPE_STRING;
		
		
	}
	public String key;
	public String valuePath;
	public DataType dataType;
	public String cleanFunction;
	List<DataMapping> children;
	
	public DataMapping()
	{
		key = ""; valuePath = "";
		dataType = DataType.DATA_TYPE_STRING;
		cleanFunction = "";
		children = null;
	}
	
	public String toString()
	{
		StringBuilder builder = new StringBuilder();
		builder.append(key + "\t" + valuePath + "\t" + dataType + "\t" + cleanFunction);
		if(children != null)
		{
			builder.append("\n");
			for (Object o : children)
			{
				DataMapping child = (DataMapping) o;
				builder.append("\t" + child.toString());
				builder.append("\n");
				
			}
		}
		return builder.toString(); 
	}
	
	
	public static Map<String,DataMapping> readJson(String filename) throws IOException
	{
		Map<String,DataMapping> map = new HashMap<String, DataMapping>();
		String jsonContent = Utils.readFile(filename);
		//System.out.println(jsonContent);
		JsonParser jparser  = new JsonParser();
		JsonArray jarray = (jparser.parse(jsonContent)).getAsJsonArray();
		
		for(Object o : jarray)
		{
			JsonObject json = (JsonObject) o;
			DataMapping dm = createDataMapping(json);
			map.put(dm.key, dm);
			
		}
		return map;
		
	}
	
	
	
	public static DataMapping createDataMapping(JsonObject json)
	{
		String name = json.get("name").getAsString();
		DataMapping root = new DataMapping();
		root.key = name;
		root.dataType = DataType.DATA_TYPE_VECTOR;
		JsonArray mappings = json.get("mappings").getAsJsonArray();
		List<DataMapping> dataMappingVector = getDataMappingChildren(mappings);
		if(dataMappingVector == null)
		{
			System.err.println("Skipping data mappings for :" + name);
			return root;
		}
		root.children = dataMappingVector;
		//for(int i=0; i<dataMappingVector.size(); i++)
			//System.out.println(dataMappingVector.get(i).toString());
		
		return root;
		
	}
	
	public static List<DataMapping> getDataMappingChildren(JsonArray mappings)
	{
		List<DataMapping> list = new ArrayList<DataMapping>();
		for(Object o : mappings)
		{
			JsonObject obj = (JsonObject)o;
			DataMapping dmapping = parseDataMapping(obj);
			if(dmapping == null)
			{
				
				return null;
			}
			
			list.add(dmapping);
			
		}
		
		return list;
		
		
	}
	
	public static DataMapping parseDataMapping(JsonObject json)
	{
		DataMapping dataMapping = new DataMapping();
		
		
		if(json.has("key"))
			dataMapping.key = json.get("key").getAsString();
		else
		{
			System.err.println("Missing name mapping in " + json.toString());
			return null;
		}
		if (json.has("xpath"))
				dataMapping.valuePath = json.get("xpath").getAsString();
		
		if(json.has("type"))
		{
			String typeString = json.get("type").getAsString();
			dataMapping.dataType = getDataType(typeString);
		}
		if(json.has("cleanFunction"))
		{
			dataMapping.cleanFunction = json.get("cleanFunction").getAsString();
		}
		
		if(dataMapping.dataType ==  DataType.DATA_TYPE_VECTOR)
		{
			//dataMapping.children = new ArrayList<DataMapping>();
			if(json.has("children"))
			{
				JsonArray children = json.get("children").getAsJsonArray();
				dataMapping.children = getDataMappingChildren(children);
				
			}
			
			
		}
		
		
		
		return dataMapping;
	
	}
}
