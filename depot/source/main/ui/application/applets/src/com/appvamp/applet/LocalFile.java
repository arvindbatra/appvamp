
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
import java.io.*;
import com.appvamp.applet.nanoxml.*;
import java.util.zip.*;
import java.util.concurrent.ConcurrentLinkedQueue;
import org.json.simple.*;
import netscape.javascript.JSObject;
import netscape.javascript.JSObject;


public class LocalFile extends Applet {
	public JSObject win;

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
		p.add(new Button("Read Apps"));
		
		p.setBackground(new Color(255, 255, 255));
		
		add("North",p);
		
	}
	
	public void init()
	{
		this.win = JSObject.getWindow(this);
		String fbuid = getParameter("fbuid");
		System.out.println("fbuid = " + fbuid);

	}
	



	public boolean action(Event evt, Object arg) {
		if (arg.equals("Read Apps")) {
			System.out.println("OPEN CLICKED");
			String homedir = System.getProperty("user.home");
			String ostype = System.getProperty("os.name");
			String username = System.getProperty("user.name");


			System.out.println(homedir);
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
				//TODO: notify to user.
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
				//TODO: notify to user.
				return false;

			}

			checkAndReadApps(appDirs);


		} else return false;
		return true;
	}


	public void checkAndReadApps(List<File> appDirs)
	{
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

				System.out.println(arr.toJSONString());
				JSObject doc = (JSObject) this.win.getMember("document");
				Object[] objects = new Object[1];
				objects[0] = arr.toJSONString();
				this.win.call("showApps", objects); 
				

			}catch(java.lang.InterruptedException ie)
			{
				System.out.println(ie.getMessage());
			}catch( Exception e)
			{
				System.err.println("Error in parsing dir " + appDir.getName() + " " + e.getMessage());
			}
			
		}
	}
	private static String joinPath(String[] components, String sep)
	{
		String ret = "";
		for (int i = 0; i < components.length - 1; i++) {
			ret = ret + components[i] + sep;
		}
		return ret + components[(components.length - 1)];
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
