
package com.appvamp.applet; 

import java.io.*;
import java.util.*;
import com.appvamp.applet.nanoxml.*;
import java.util.zip.*;
import java.util.concurrent.ConcurrentLinkedQueue;
import java.lang.Thread;
import org.json.simple.*;

public class AppChecker  implements Runnable
{
	
	ConcurrentLinkedQueue <File> m_processQueue;
	ConcurrentLinkedQueue <JSONObject> m_outputData;


	public AppChecker( ConcurrentLinkedQueue<File> processQueue, ConcurrentLinkedQueue<JSONObject> appData)
	{
		m_processQueue = processQueue;
		m_outputData = appData;
	}

	public void run()
	{
		while(!m_processQueue.isEmpty())
		{
			File ipaFile = m_processQueue.remove();
			Thread curThread = Thread.currentThread();
			System.out.println("Processing file " + ipaFile.getName() + " by thread id" + curThread.getName());
			JSONObject appData = parseIPAFile(ipaFile);
			if(appData != null)
				m_outputData.add(appData);
			


		}

	}


	
	
	public static String readFile( String file ) throws IOException {
		//BufferedReader reader = new BufferedReader( new FileReader (file));
		FileReader reader = new FileReader (file);
		RandomAccessFile raf = new RandomAccessFile(file, "r");
		ByteArrayOutputStream buf = new ByteArrayOutputStream();
		byte[] byteArr = null;  
		byteArr = new byte [(int)raf.length()];
		raf.readFully(byteArr);
		for(int i=0; i<byteArr.length; i++)
		{
			buf.write(byteArr[i]);
		}

		return Base64.encodeBytes(byteArr);
		
	
	}

	public static String readInputStreamAsString(InputStream in)
		throws IOException
	{
		BufferedInputStream bis = new BufferedInputStream(in);
		ByteArrayOutputStream buf = new ByteArrayOutputStream();
		Base64.OutputStream b64os = new Base64.OutputStream(buf);
		int result = bis.read();
		while (result != -1) {
			byte b = (byte)result;
			b64os.write(b);
			result = bis.read();
		}
		b64os.close();
		return buf.toString();
	}
	

   /**
	 * Reads the specified PList file and returns it as an XMLElement.
	 * This method can deal with XML encoded and binary encoded PList files.
	 */
	private static XMLElement readPList(File plistFile) throws IOException {
		FileReader reader = null;
		XMLElement xml = null;
		try {
			reader = new FileReader(plistFile);
			xml = new XMLElement(new HashMap(), false, false);
			try {
				xml.parseFromReader(reader);
			} catch (XMLParseException e) {
				xml = new BinaryPListParser().parse(plistFile);
			}
		} finally {
			if (reader != null) {
				reader.close();
			}
		}
		return xml;
	}

	/**
	 * Reads the specified PList file and returns it as an XMLElement.
	 * This method can deal with XML encoded and binary encoded PList files.
	 */
	private static XMLElement readPList(String plistContent) throws IOException {
		FileReader reader = null;
		XMLElement xml = null;
		try {
			xml = new XMLElement(new HashMap(), false, false);
			try {
				byte[] byteArr = Base64.decode(plistContent); 
				StringBuffer buf = new StringBuffer();
			  	//TODO: this data will be base 64 encoded. 
				for(int i=0; i<byteArr.length; i++)
				{
					buf.append((char)byteArr[i]);
				}	
				xml.parseString(buf.toString());
			} catch (XMLParseException e) {
				System.out.println("parsing by binary plist" + e.getMessage());
				//byte[] byteArr = plistContent.getBytes();
				byte[] byteArr = Base64.decode(plistContent); 
				xml = new BinaryPListParser().parse(byteArr);
			}
		}finally 
		{		
	
		}
		 
		return xml;
	}



	private static JSONObject parseIPAFile(File ipaFile)
	{
		try {
			JSONObject obj=new JSONObject();

			ZipFile zip = new ZipFile(ipaFile);
			ZipEntry ze = zip.getEntry("iTunesMetadata.plist");
			//System.out.println("parsing ipa file");
			//Base64 encoded string
			String plistString = readInputStreamAsString(zip.getInputStream(ze)); 
			XMLElement elem = readPList(plistString);
			if(elem != null)
			{
				//System.out.println(elem.toString());
				String xmlContent = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				xmlContent += elem.toString();
				//Map<String, Object> data = Plist.fromXml(xmlContent);
				Map<String, Object> data = new PlistParser().parseXML(elem);
				for(Map.Entry<String, Object> entry :data.entrySet())
				{
				//	System.out.println(entry.getKey() + "\t"  + entry.getValue().toString());
				}
				obj.put("AppleID", data.get("com.apple.iTunesStore.downloadInfo_accountInfo_AppleID"));
				obj.put("itemId", data.get("itemId"));
				obj.put("itemName", data.get("itemName"));
				obj.put("purchaseDate", data.get("com.apple.iTunesStore.downloadInfo_purchaseDate"));
				
			}
			return obj;
			
		} catch (Exception e)
		{
			System.out.println("Error in parsing ipa file " + ipaFile.getName()  + " "  + ipaFile.getPath()  + e.getMessage());
			e.printStackTrace();
		}

		return null;
	}
	

	public static void main(String[] args) throws Exception
	{
		String tempDir = System.getProperty("java.io.tmpdir");
		System.out.println("arv_" + tempDir);
		File ipaFile = new File ("Mobile-Applications/Alarm Clock Free.ipa");
		parseIPAFile(ipaFile);


		ConcurrentLinkedQueue<File> processQueue = new ConcurrentLinkedQueue<File>();
		ConcurrentLinkedQueue<JSONObject> appDataList = new ConcurrentLinkedQueue<JSONObject>();
		ArrayList<Thread> parserThreads = new ArrayList<Thread>();

		String appDirName = "Mobile-Applications";
		File appDir = new File(appDirName);

		File[] files = appDir.listFiles();
		for(int i=0; i<files.length; i++)
		{
			processQueue.add(files[i]);

		}
		int NUM_THREADS = 5;

		for(int i=0; i<NUM_THREADS; i++)
		{
			 Thread thread = new Thread(new AppChecker(processQueue, appDataList), "thread" + i);
			 thread.start();
			 parserThreads.add(thread);
		}
		
		for( int i=0; i<parserThreads.size(); i++)
			parserThreads.get(i).join();


		JSONArray arr =  new JSONArray();
		while(appDataList.size() > 0)
		{
			arr.add(appDataList.poll());

		}

		System.out.println(arr.toJSONString());




	}







}

