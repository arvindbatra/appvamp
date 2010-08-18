package com.pjab.apper.tests;

import java.io.IOException;
import java.util.Map;

import com.pjab.apper.AppParser;
import com.pjab.apper.DataMapping;

public class DataMappingTest
{
	
	
	public static void main(String [] args) throws Exception
	{
		
		Map<String,DataMapping> mappings = DataMapping.readJson("data/dataMappings.json");
		
		DataMapping dm = mappings.get("itunes_web_html_mapping");
		
		//String appFilename = "apps/linkedin_id288429040";
		String appFilename = "trial4/flipboard_id358801284";
		AppParser parser = new AppParser(appFilename);
		parser.parseWithDataMappings(dm);
		
		
	}

}
