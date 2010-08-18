package com.pjab.apper;

import java.util.List;

import com.google.gson.Gson;

public class AppData 
{
	
	public String fieldName;
	public String fieldValue;
	public DataMapping.DataType type;
	List<AppData> children;
	
	public AppData()
	{
		type = DataMapping.DataType.DATA_TYPE_STRING;
		children = null;
	}
	
	public String toString()
	{
		StringBuilder builder = new StringBuilder();
		builder.append(fieldName + "\t" + fieldValue);
		if(children != null)
		{
			builder.append("\n");
			for(int i=0; i<children.size(); i++)
			{
				builder.append("\t" + children.get(i).toString() + "\n");
			}
		}
		return builder.toString();
	}
	
	
	public String toJSON()
	{
		Gson gson = new Gson();
		StringBuilder builder = new StringBuilder();
		builder.append("{"  );
		if(fieldName == null)
			fieldName = "-";
		builder.append( gson.toJson(fieldName) );
		builder.append(":");
		if(children == null)
		{
			builder.append( gson.toJson(fieldValue) );
		}
		else
		{
			builder.append("[");
			boolean first = true;
			for(int i=0; i<children.size(); i++)
			{
				
				if(first)
					first = false;
				else
					builder.append(",");
			
				builder.append(children.get(i).toJSON());
			}
			
			builder.append("]");
				
			
			
		}
	
		builder.append("}");
		
		
		return builder.toString();
		
	}

}
