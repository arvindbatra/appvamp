
package com.appvamp.applet;
import java.applet.*;
//import java.awt.*;
import java.awt.Font;
import java.awt.Panel;
import java.awt.Button;
import java.awt.Event;
import java.awt.Color;
import java.util.List;
import java.util.ArrayList;
import java.lang.*;
import java.text.*;
import java.awt.event.*; 
import java.awt.*; 
import java.io.*;
import com.appvamp.applet.nanoxml.*;
import java.util.zip.*;
import java.util.concurrent.ConcurrentLinkedQueue;
import org.json.simple.*;
import netscape.javascript.JSObject;
import netscape.javascript.JSObject;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;



public class LocalFile extends Applet {
	public JSObject win;
	public String fbuid;
	public String fbName;
	public String userid;
	public boolean showApps;
	public int numAppsRead = 0;

	public LocalFile() {
		Panel p = new Panel();
		Font f;
		String osname = System.getProperty("os.name","");
		if (!osname.startsWith("Windows")) {
			f = new Font("Arial",Font.BOLD,10);
		} else {
			f = new Font("Verdana",Font.BOLD,12);
		}
		p.setFont(f);
		Button b = new Button("Read My Apps");
		b.setSize(new Dimension (200, 200));
		p.add(b);
		
		p.setBackground(new Color(255, 255, 255));
		
		add("North",p);
		
	}
	
	public void init()
	{
		this.win = JSObject.getWindow(this);
		this.fbuid = getParameter("fbuid");
		this.fbName = getParameter("fbname");
		this.userid = getParameter("userid");
		String showAppsStr= getParameter("showApps");
		if(showAppsStr == null)
			this.showApps = false;
		else if(showAppsStr.equalsIgnoreCase("true")) 
			this.showApps = true;
		System.out.println("fbuid = " + fbuid + " userid=" + userid);




	}
	



	public boolean action(Event evt, Object arg) {
		if (arg.equals("Read My Apps")) {
			return process();
		}
		else return false;
	}

	
	public boolean process()
	{
		if(fbuid.isEmpty() )
		{
			String message = "Please login with facebook before pressing the sync button";
			System.out.println(message);
			notifyUser(message);
			return false;
		}
		System.out.println("OPEN CLICKED");
		System.out.println(this.userid);
		String homedir = System.getProperty("user.home");
		System.out.println(homedir);
		String ostype = System.getProperty("os.name");
		String username = System.getProperty("user.name");
		notifyUser("Reading your apps. Please wait!");


		System.out.println(ostype);
		System.out.println(username);

		String[] itunesdirs = { "iTunes Media" + File.separator + "Mobile Applications", 
								"iTunes Music" + File.separator + "Mobile Applications", 
								"Mobile Applications" 
								};

		String itunesbase = "";
		if ((ostype.equals("Windows 7")) || (ostype.equals("Windows Vista"))) {
			itunesbase = homedir + File.separator + joinPath(new String[] { "Music", "iTunes" }, File.separator) + File.separator;
		}
		else if (ostype.startsWith("Win")) {
			itunesbase = joinPath(new String[] { "C:", "Documents and Settings", username, "My Documents", "My Music", "iTunes" }, File.separator) + File.separator;
		}
		else if (ostype.startsWith("Mac")) {
			itunesbase = homedir + File.separator + "Music" + File.separator + "iTunes" + File.separator;
		}
		else
		{
			System.out.println("Can not find installed apps. Sorry!");
			notifyUser("We are extremely sorry, we do not recognize your operating system at the moment. Please contact us at contact@appvamp.com to resolve this issue. Thanks!");
			return false;
		}
	
		List<File> appDirs = new ArrayList<File>();
		if(!itunesbase.isEmpty())
		{
			for(int i=0; i<itunesdirs.length; i++)
			{
				File f = new File(itunesbase + itunesdirs[i]);
				if ((f.exists()) && (f.isDirectory())) {
					appDirs.add(f);
				}
			}
		}
		
		if(appDirs.size() == 0)
		{
			System.out.println("Can not find installed apps. Sorry!");
			notifyUser("We are extremely sorry, we could not find any installed apps at the moment. Please contact us at contact@appvamp.com to resolve this issue. Thanks!");
			return false;

		}
		boolean success = checkAndReadApps(appDirs);
		if(!success)
		{
			notifyUser("We are extremely sorry, we could not read your installed apps at the moment. Please contact us at contact@appvamp.com to resolve this issue. We appeciate your patience!");
			return false;
		}
		
		notifyUser("Succesfully read " + numAppsRead + " apps!");
		return true;

	}


	public boolean checkAndReadApps(List<File> appDirs)
	{
		boolean success = false;
		for(int fi = 0; fi<appDirs.size(); fi++) 
		{
			File appDir = appDirs.get(fi);
			try {
				ConcurrentLinkedQueue<File> processQueue = new ConcurrentLinkedQueue<File>();
				ConcurrentLinkedQueue<JSONObject> appDataList = new ConcurrentLinkedQueue<JSONObject>();
				ArrayList<Thread> parserThreads = new ArrayList<Thread>();

				//String appDirName = "Mobile-Applications";
				//String appDirName = "C:\\Users\\arvind\\Music\\iTunes\\iTunes Media\\Mobile Applications";

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
				numAppsRead = arr.size();
				System.out.println("sz" + arr.size());
				int offset = 40;	//40 apps at a time. 
				for(int chunker = 0; chunker < arr.size(); chunker += offset)
				{
					int start = chunker;
					int end = Math.min(chunker+offset, arr.size());
					JSONObject postJson = new JSONObject();
					JSONArray chunkArr = new JSONArray();
					for(int cindex = start; cindex < end; cindex++)
						chunkArr.add(arr.get(cindex));
					postJson.put("fbuid", fbuid);
					postJson.put("userid", userid);
					postJson.put("fbname", fbName);
					postJson.put("installedApps", chunkArr);
					System.out.println(postJson.toJSONString());
					postData(postJson.toJSONString());
					
				}

				
				

				if(this.showApps) {	
					JSObject doc = (JSObject) this.win.getMember("document");
					JSONObject postJson = new JSONObject();
					postJson.put("fbuid", fbuid);
					postJson.put("fbname", fbName);
					postJson.put("installedApps", arr);
					Object[] objects = new Object[1];
					objects[0] = postJson.toJSONString();
					this.win.call("showApps", objects); 
				}
				
				success =  true;

			}catch(java.lang.InterruptedException ie)
			{
				System.out.println(ie.getMessage());
			}catch( Exception e)
			{
				System.err.println("Error in parsing dir " + appDir.getName() + " " + e.getMessage());
			}
		}
		return success;
	}
	private static String joinPath(String[] components, String sep)
	{
		String ret = "";
		for (int i = 0; i < components.length - 1; i++) {
			ret = ret + components[i] + sep;
		}
		return ret + components[(components.length - 1)];
	}

	
	private void notifyUser(String message)
	{
		Object[] objects = new Object[1];
		objects[0] = message;
		this.win.call("showMessage", objects); 

	}



	private void postData(String jsonData)
	{
		HttpURLConnection connection = null;
		try
		{
			System.out.println("Posting data to server");
			URL base = getDocumentBase();
			URL postback = new URL(base.getProtocol(), base.getHost(), getDocumentBase().getPort(), "/register/register_apps");
			jsonData = URLEncoder.encode(jsonData,"UTF-8");

			connection = (HttpURLConnection)postback.openConnection();
			connection.setDoOutput(true);

			connection.setRequestMethod("POST");
			connection.setRequestProperty("Content-Type", "application/json");
			connection.setRequestProperty("Content-Length", Integer.toString(jsonData.length()));

			OutputStreamWriter out = new OutputStreamWriter(connection.getOutputStream());
			out.write(jsonData);
			out.close();
		
			System.out.println("Response code = " + connection.getResponseCode());

			if (connection.getResponseCode() > 399) {
				System.err.println("Got error from server " + connection.getResponseMessage());
				out.close();
				connection.disconnect();
				return;
			}
		}
		catch (Exception ie) {
			if (connection != null) {
				System.err.println("Closing connection " + connection);
				connection.disconnect();
				System.err.println("Network connection failed");
				ie.printStackTrace();
			}
		}
		if (connection != null) {
			connection.disconnect();
		}

	}


}





/*
			try {

				String dirName = "C:\\Users\\arvind\\Music\\iTunes\\iTunes Media\\Mobile Applications";
				File dir = new File(dirName);
        		File [] files = dir.listFiles();
				for( File f: files)
				{
					String fn = f.getName();
					System.out.println(fn);
				}
			}catch (Exception ioe)
			{
				System.out.println("Exception found"  + ioe.getMessage());

			}

*/
