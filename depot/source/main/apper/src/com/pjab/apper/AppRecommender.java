
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
import java.util.Collections;

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
				
				System.out.println("Failed json parsing for " + f.getName() );
				//e.printStackTrace();
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


	class IndexCounter
	{
		int index;
		int count;

	}

	void purgeExtremesInIndex()
	{
		int K = 50;
		System.out.println("index size: " + idToAppIndexMap.size() + " K=" + K);
		PriorityQueue<IndexCounter> topK = new PriorityQueue(K, new Comparator<IndexCounter>() {
				public int compare(IndexCounter a, IndexCounter b)
				{
					if (a.count < b.count)
						return  -1;
					else if(a.count > b.count)
						return +1;
					return 0;
				}
			});
		for(Map.Entry<Integer, PostingList> entry : idToAppIndexMap.entrySet())
		{
			int id = entry.getKey();
			int count = entry.getValue().size();

			if(topK.size() < K)
			{
				IndexCounter ic = new IndexCounter();
				ic.index = id; ic.count = count;
				topK.add(ic);
				continue;
			}

			IndexCounter min = topK.peek();
			if(min.count < count)
			{
				topK.poll();
				IndexCounter ic = new IndexCounter();
				ic.index = id; ic.count = count;
				topK.offer(ic);
			}

		}

		Iterator iter = topK.iterator();
		System.out.println("Extremes are");
		while(iter.hasNext())
		{
			IndexCounter ic = (IndexCounter) iter.next();
			System.out.println(ic.index + "(" + idNameMap.get(ic.index) +") has " + ic.count + " apps");
			idToAppIndexMap.remove(ic.index);
		}
		System.out.println("index size: " + idToAppIndexMap.size() + " K=" + K);

		
	}

	void printIndex()
	{
		for(Map.Entry<Integer, PostingList> entry : idToAppIndexMap.entrySet())
		{
			StringBuffer buffer = new StringBuffer();
			int id = entry.getKey();
			String name = idNameMap.get(id);

			PostingList list = entry.getValue();
			buffer.append(id + "(" + name + ") has " + list.size() + " apps");


			/*Iterator iter = list.iterator();
			while(iter.hasNext())
			{
				int index = (Integer)iter.next();
				AppCatInfo aci = apps.get(index);
				buffer.append(aci.appName + "\t");
			}
			*/
			System.out.println(buffer.toString());
		}


	}


	public PostingList getCandidates(AppCatInfo aci)
	{
		PostingList candidates = new PostingList();
		try {
			for(int i=0; i<aci.mentionList.size(); i++)
			{
				CatInfo ci = aci.mentionList.get(i);

				if(idToAppIndexMap.containsKey(ci.id))
				{
					PostingList ciCandidates = idToAppIndexMap.get(ci.id);
					candidates.addAll(ciCandidates);
				}

			}


			//genre + seller filter
			Iterator giter = candidates.iterator();
			while(giter.hasNext())
			{
				int appIndex = (Integer) giter.next();
				AppCatInfo ai = apps.get(appIndex);
				if(!ai.genre.equalsIgnoreCase(aci.genre))
				{
					giter.remove();
					continue;
			
				}
				
				if(ai.seller.equalsIgnoreCase(aci.seller))
				{
					giter.remove();
					continue;
				}

			}



			//category filter
			Set<Integer> catSet = new HashSet<Integer>();
			for(int i=0; i<aci.categories.size(); i++)
				catSet.add(aci.categories.get(i).id);


			Iterator iter = candidates.iterator();
			while(iter.hasNext())
			{
				int appIndex = (Integer) iter.next();
				AppCatInfo ai = apps.get(appIndex);
				boolean catMatch = false;
				for(int i = 0; i < ai.categories.size(); i++)
				{
					int aiCatId = ai.categories.get(i).id;
					if(catSet.contains(aiCatId))
					{
						catMatch = true;
						break;
					}

				}
				if(!catMatch)
				{
					//System.out.println("Dropping candidate: " + ai.appName + " for app " + aci.appName  + "b/c cat mismatch");
					iter.remove();

				}

			}


		
		} catch (Exception e)
		{
			System.err.println("Exception while getting candidates for " + aci.appName + " " + e.getMessage());
		}

		return candidates;
	}


	public double computeSimilarity (AppCatInfo a, AppCatInfo b)
	{
		double sim = 0;
		int numMentionsA = a.mentionList.size();
		int numMentionsB = b.mentionList.size();

		if(numMentionsA == 0 || numMentionsB == 0)
			return 0.0;
		
		for(int i = 0; i< a.mentionList.size(); i++)
		{
			int aId = a.mentionList.get(i).id;
			double aScore = a.mentionList.get(i).score;
			for(int j=0; j< b.mentionList.size(); j++)
			{
				int bId = b.mentionList.get(j).id;
				if( aId == bId)
				{
					double bScore = b.mentionList.get(j).score;
					sim += 1.0 * aScore * bScore;

					break;
				}
				
			}
		}

		/*Set<Integer> mentionSetA = new HashSet<Integer> ();
		for(int i = 0; i< a.mentionList.size(); i++)
			mentionSetA.add(a.mentionList.get(i).id);

		int matchCount = 0;
		for(int i = 0; i< b.mentionList.size(); i++)
		{
			int bId = b.mentionList.get(i).id;
			if(mentionSetA.contains(bId))
				matchCount++;
		}
		*/
		//sim = 1.0*matchCount ;
		//sim /= numMentionsA;
		//sim /= numMentionsB;

		//System.out.println("sim between " + a.appName + " and " + b.appName + " = " + sim);
		return sim;
	}

	public class SimilarAppInfo implements Comparable
	{
		double score;
		String name;
		int appIndex;

		public int compareTo(Object o)
		{
			SimilarAppInfo other = (SimilarAppInfo) o;

			if(this.score < other.score)
				return +1;
			if(this.score > other.score)
				return -1;
			else return 0;

		}

		public String toString()
		{
			return name + "(" + score + ")";
		}

	}


	public List<SimilarAppInfo> getAppRecommendations (AppCatInfo aci)
	{
		List<SimilarAppInfo> similarList = new ArrayList<SimilarAppInfo>();
		PostingList candidates = getCandidates(aci);
		System.out.println(aci.appName + "has candidate set of size " + candidates.size());

		Iterator iter = candidates.iterator();
		while(iter.hasNext())
		{
			int index = (Integer)iter.next();
			AppCatInfo candidateApp  = apps.get(index);

			double similarity =  computeSimilarity(aci, candidateApp);
			SimilarAppInfo similarAppInfo = new SimilarAppInfo();
			similarAppInfo.name = candidateApp.appName;
			similarAppInfo.appIndex = index;
			similarAppInfo.score = similarity;
			similarList.add(similarAppInfo);
		}

		//System.out.println("Before sort");
		//for(int i=0; i<similarList.size(); i++)
		//	System.out.print(similarList.get(i).toString()+"\t");

		System.out.println("");
		Collections.sort(similarList);
		System.out.println("After sort");
		for(int i=0; i<similarList.size(); i++)
			System.out.print(similarList.get(i).toString() + "\t" );
		System.out.println("");

		return similarList;

	}

	
	public void recommendApps()
	{
		for(int i=0; i<apps.size(); i++)
		{
			AppCatInfo aci = apps.get(i);
			System.out.println("Processing app" + aci.appName);
			getAppRecommendations(aci);
		}
			
	}




	public static void main(String [] args) throws Exception
	{
		Properties prop = Utils.loadProperties("default.properties");		
		String catFile = "app_cat/Kubb";
		
		AppRecommender reco = new AppRecommender();
		//String catDir = prop.getProperty(ApperConstants.OUTPUT_SMALL_CAT_DIR);
		String catDir = prop.getProperty(ApperConstants.OUTPUT_CAT_DIR);
		reco.readApps(catDir);
		reco.createIndex();
		//reco.printIndex();
		reco.purgeExtremesInIndex();


		reco.recommendApps();



	}








	










};
