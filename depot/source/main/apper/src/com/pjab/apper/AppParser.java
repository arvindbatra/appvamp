package com.pjab.apper;

import java.io.BufferedReader;

import java.io.FileReader;
import java.io.IOException;
import java.io.StringReader;
import java.io.StringWriter;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.w3c.tidy.Tidy;
import org.xml.sax.EntityResolver;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

import com.google.gson.JsonObject;

public class AppParser
{
	public  Map<String, String> dataMapping;
	String m_appName;
	
	public AppParser (String appfileName)
	{
		dataMapping = new HashMap<String, String>();
		dataMapping.put("price", "//div[@class='price']");
		dataMapping.put("title", "//div[@id='title']/h1");
		dataMapping.put("link", "/html/head/link[@rel='canonical']/@href");
		
		dataMapping.put("img_url", "//div[@id='left-stack']/div/div/img/@src");
		dataMapping.put("requirements", "//div[@id='left-stack']/div/p");
		dataMapping.put("release_date", "//li[@class='release-date']");
		dataMapping.put("genre", "//li[@class='genre']/a");
		dataMapping.put("genre_link", "//li[@class='genre']/a/@href");
		dataMapping.put("language", "//li[@class='language']");
		dataMapping.put("seller", "//div[@id='title']/h2");
		dataMapping.put("app_rating", "//div[@class='app-rating']/a/text()");
		dataMapping.put("description", "//div[@class='product-review']/p");
		
		m_appName = appfileName;
	
	}
	
	public String cleanContent(String content)
	{
		
		Tidy tidy = new Tidy();
		String output = Utils.RemoveTroublesomeCharacters(content);

		tidy.setInputEncoding("utf8");
		tidy.setXmlOut(true);
		tidy.setDropEmptyParas(true);
		tidy.setIndentContent(true);
		tidy.setKeepFileTimes(true);
		tidy.setSmartIndent(true);
		
		//tidy.setOutputEncoding("utf8");
		//tidy.setXmlPi(true);
		tidy.setShowWarnings(false);
		StringReader reader = new StringReader(output);
		StringWriter writer = new StringWriter();
		tidy.parse(reader,writer);
		
		String cleanoutput = writer.toString();
		return Utils.removeMultipleWhiteSpaces(cleanoutput);
		
		
	
	}
	
	public AppData extractField (DataMapping dm, Node node )
	{
		XPathFactory factory = XPathFactory.newInstance();
		XPath xpath = factory.newXPath();
		Object result;
		try {
			XPathExpression expr = xpath.compile(dm.valuePath);
			 result = expr.evaluate(node, XPathConstants.NODESET);
			} catch (XPathExpressionException e) {
				e.printStackTrace(System.err);
				return null;
			}
			
			NodeList nodes = (NodeList) result;
			StringBuilder stringBuilder = new StringBuilder();
			AppData appData = new AppData();
			if(dm.children != null)
				appData.children = new ArrayList<AppData>();
		
			appData.fieldName = dm.key;
			
			for (int i = 0; i < nodes.getLength(); i++) {
				if(dm.children == null)
				{
					String text = nodes.item(i).getTextContent();
					text = text.trim();
					stringBuilder.append(text );
					stringBuilder.append(" ");
				}
				else
				{
					// System.out.println(nodes.item(i).getTextContent());
					for(int j=0; j<dm.children.size(); j++)
					{
						AppData childData = extractField(dm.children.get(j), nodes.item(i));
						appData.children.add(childData);
					}
					
				}
			}
		
			appData.fieldValue = stringBuilder.toString();
			return appData;
		
		
	}
	
	public AppData extractFields(String content, DataMapping root)
	{
		AppData rootAppData = new AppData();
		rootAppData.fieldName = "root";
		rootAppData.children = new ArrayList<AppData>();
		DocumentBuilderFactory domFactory = DocumentBuilderFactory.newInstance();
		domFactory.setValidating(false);
		
	    //domFactory.setNamespaceAware(true); // never forget this!
	    domFactory.setNamespaceAware(false);
	    DocumentBuilder builder;
	    
		try {
			builder = domFactory.newDocumentBuilder();
			builder.setEntityResolver(new EntityResolver() {
		        
		        public InputSource resolveEntity (String publicId, String systemId) throws SAXException, IOException 
		        {
		            if (systemId.contains("w3")) {
		                return new InputSource(new StringReader(""));
		            } else {
		                return null;
		            }
		        }
		    });

			Document doc = builder.parse(new InputSource(new StringReader(content)));
			
			for(int i=0; i<root.children.size(); i++)
			{
				AppData childData = extractField(root.children.get(i), doc);
				rootAppData.children.add(childData);
			}
			
		//	System.out.println("Arv_" + rootAppData.toJSON());
			return rootAppData;
		
			
		} catch (ParserConfigurationException e) {
			//	System.err.println("Parser config exception while parsing content " + content);
				System.out.println("Parser config exception found " + e.toString());
				e.printStackTrace(System.err);
				
			}catch (SAXException e) {
				System.out.println("SAX exception found " + e.toString());
				
				e.printStackTrace(System.err);
			} catch (IOException e) {
				System.out.println("IO exception found " + e.toString());
				
				e.printStackTrace(System.err);
			}
		return null;
		
	}
	
	
	public Map<String, String> extractFields(String content, Map<String, String> dataMapping)
	{
		Map<String,String> dataVal = new HashMap<String, String>();
		
		DocumentBuilderFactory domFactory = DocumentBuilderFactory.newInstance();
		domFactory.setValidating(false);
		
	    //domFactory.setNamespaceAware(true); // never forget this!
	    domFactory.setNamespaceAware(false);
	    DocumentBuilder builder;
	    
		try {
			builder = domFactory.newDocumentBuilder();
			builder.setEntityResolver(new EntityResolver() {
		        
		        public InputSource resolveEntity (String publicId, String systemId) throws SAXException, IOException 
		        {
		            if (systemId.contains("w3")) {
		                return new InputSource(new StringReader(""));
		            } else {
		                return null;
		            }
		        }
		    });

			Document doc = builder.parse(new InputSource(new StringReader(content)));
			
		

			XPathFactory factory = XPathFactory.newInstance();
			XPath xpath = factory.newXPath();
		  
				for( Map.Entry<String, String> entry : dataMapping.entrySet())
				{
					String dataKey = entry.getKey();
					String dataXpath = entry.getValue();
					Object result;
					try {
					XPathExpression expr = xpath.compile(dataXpath);
					 result = expr.evaluate(doc, XPathConstants.NODESET);
					} catch (XPathExpressionException e) {
						e.printStackTrace(System.err);
						continue;
					}
					NodeList nodes = (NodeList) result;
					StringBuilder stringBuilder = new StringBuilder();
					for (int i = 0; i < nodes.getLength(); i++) {
						String text = nodes.item(i).getTextContent();
						text = text.trim();
						stringBuilder.append(text );
						stringBuilder.append(" " );
						
					}
					
					String data = stringBuilder.toString();
					dataVal.put(dataKey, data);
				}
			
		    
		    
		} catch (ParserConfigurationException e) {
		//	System.err.println("Parser config exception while parsing content " + content);
			System.out.println("Parser config exception found " + e.toString());
			e.printStackTrace(System.err);
		}catch (SAXException e) {
			System.out.println("SAX exception found " + e.toString());
			
			e.printStackTrace(System.err);
		} catch (IOException e) {
			System.out.println("IO exception found " + e.toString());
			
			e.printStackTrace(System.err);
		}
		
		return dataVal;
	}
	
	public Map<String, String> parse() throws Exception
	{
		String appContent = Utils.readFile(m_appName);
		String cleanAppContent = cleanContent(appContent);
		
		Map<String, String> fieldData = extractFields(cleanAppContent, dataMapping);
		
		return fieldData;
		
		
	}
	
	public AppData parseWithDataMappings(DataMapping dm) throws Exception
	{
		String appContent = Utils.readFile(m_appName);
		String cleanAppContent = cleanContent(appContent);
		
		return extractFields(cleanAppContent, dm);
		
		
	}
	
	public String toString(Map<String, String> map)
	{
		StringBuilder builder = new StringBuilder();
		builder.append(m_appName);
		builder.append("\n");
		for( Map.Entry<String, String> entry : map.entrySet())
		{
			builder.append(entry.getKey()).append("::::").append(entry.getValue()).append("\n");
		
		}
		
		return builder.toString();
	
	}
	

	public static void main(String[] args) throws Exception 
	{
		
		//String appFilename = "trial4/chad-ochocinco-official-app_id327288264";
		String appFilename = "apps/linkedin_id288429040";
		
	

		AppParser parser = new AppParser(appFilename);
		Map<String, String> fieldData = parser.parse();
		
		String output = parser.toString(fieldData);
		System.out.println(output);
		
		
		
		
		
		
	}
	
	
	
}
